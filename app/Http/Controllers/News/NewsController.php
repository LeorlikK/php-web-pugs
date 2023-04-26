<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Services\NewsService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use Views\View;

class NewsController extends Controller
{
    private PaginateService $paginate;

    const LIMIT_ITEM_PAGE = 14;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function dopComments():string
    {
        $id = StrService::stringFilter($_GET['id']);
        $offset = StrService::stringFilter($_GET['offset']);
        $comments = DB::select("
            SELECT comment_relations.*, users.login, users.avatar FROM comment_relations
            JOIN users ON comment_relations.user_id = users.id 
            WHERE comment_relations.comment_id = ?
            ORDER BY comment_relations.created_at
            LIMIT 3
            OFFSET ?
            ", [$id, $offset])->fetchAll();

        foreach ($comments as &$comment) {
            $comment['created_at'] = StrService::format($comment['created_at']);
        }

        if (count($comments) < 1) return false;
        return json_encode($comments);
    }

    public function index():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPageForNews('news');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $news = DB::select("
            SELECT news.*, COUNT(DISTINCT news_comments.id) + COUNT(comment_relations.id) AS count
            FROM news
            LEFT JOIN news_comments ON news_comments.news_id = news.id
            LEFT JOIN comment_relations ON news_comments.id = comment_relations.comment_id  
            WHERE news.publish = true
            GROUP BY news.id
            ORDER BY created_at DESC
            LIMIT ?
            OFFSET ?
            ",
            [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();

        NewsService::saveCookiePage($paginate['current_page']??0);

        return new View('news.news', ['files' => $news, 'paginate' => $paginate]);
    }

    public function show():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPageForComments('news_comments', $this->paginate->getId());
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $news = DB::select("SELECT * FROM news  WHERE news.id = ?", [$this->paginate->getId()])->fetch();
        $comments = DB::select("
            SELECT news_comments.*, users.login, users.avatar,
           (SELECT COUNT(*) FROM comment_relations WHERE comment_id = news_comments.id) AS comment_count
            FROM news_comments
            JOIN users ON news_comments.user_id = users.id 
            WHERE news_id = ? 
            ORDER BY created_at 
            LIMIT ?
            OFFSET ?
            ",
            [$this->paginate->getId(), self::LIMIT_ITEM_PAGE, $offset])->fetchAll();

        return new View('news.show', ['files' => $news, 'comments' => $comments, 'paginate' => $paginate]);
    }
}