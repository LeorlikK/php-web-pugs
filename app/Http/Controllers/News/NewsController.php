<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Services\NewsService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use Views\View;

class NewsController
{
    private PaginateService $paginate;

    const LIMIT_ITEM_PAGE = 10;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)((int)($_GET['page'] ?? 1), (int)($_GET['id'] ?? 1));
    }

    public function dopComments():string
    {
        $id = $_GET['id'];
        $offset = $_GET['offset'];
        $comments = DB::select("
            SELECT comment_relations.*, users.login, users.avatar FROM comment_relations
            JOIN users ON comment_relations.user_id = users.id 
            WHERE comment_relations.comment_id = ?
            ORDER BY comment_relations.created_at
            OFFSET ? LIMIT 3
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
        $last_page = $this->paginate->lastPage('news');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $news = DB::select("
            SELECT news.*, COUNT(DISTINCT news_comments.id) + COUNT(comment_relations.id) AS count
            FROM news
            LEFT JOIN news_comments ON news_comments.news_id = news.id
            LEFT JOIN comment_relations ON news_comments.id = comment_relations.comment_id
            GROUP BY news.id
            ORDER BY created_at DESC
            OFFSET ?
            LIMIT ?
            ",
            [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();

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
            OFFSET ? 
            LIMIT ? ",
            [$this->paginate->getId(), $offset, self::LIMIT_ITEM_PAGE])->fetchAll();


//        $comments = DB::select("SELECT news_comments.*, users.login, users.avatar, ARRAY_AGG(comment_relations.related_comment_id) AS comments FROM news_comments
//            JOIN users ON news_comments.user_id = users.id JOIN comment_relations ON news_comments.id = comment_relations.comment_id WHERE news_id = ?
//            GROUP BY news_comments.id, users.login, users.avatar ORDER BY created_at OFFSET ? LIMIT ? ",
//            [$this->paginate->getId(), $offset, self::LIMIT_ITEM_PAGE])->fetchAll();

//        foreach ($comments as $comment) {
//                var_dump($comment['id']);
//                preg_match_all('/[0-9]/', $comment['comments'], $matches);
//                echo '<pre>';
//                $comments = DB::select("SELECT news_comments.*, users.login, users.avatar FROM news_comments
//                    JOIN users ON news_comments.user_id = users.id WHERE news_comments.id IN (4,5) ORDER BY news_comments.created_at LIMIT 3
//                    ")->fetchAll();
//                echo '</pre>';
//        }


//        $commentsId = array_column($comments, 'id');
//        $commentsDop = DB::select("SELECT ARRAY_AGG(comment_relations.*, users.login, users.avatar) AS com FROM comment_relations
//        JOIN users ON comment_relations.user_id = users.id WHERE comment_relations.comment_id IN(1, 2, 3) GROUP BY comment_relations.comment_id
//        ")->fetchAll();

//        $commentsDop = DB::select("SELECT comment_relations.comment_id, ARRAY_AGG(comment_relations.*) AS com, users.login, users.avatar
//FROM comment_relations
//JOIN users ON comment_relations.user_id = users.id
//WHERE comment_relations.comment_id IN (1, 2, 3)
//GROUP BY comment_relations.comment_id, users.login, users.avatar;
//        ")->fetchAll();

//        $commentsDop = DB::select("
//SELECT comment_relations.comment_id, (
//    SELECT JSON_AGG(json_build_object(
//        'relation_id', comment_relations.id,
//        'parent_id', comment_relations.comment_id,
//        'comment_text', comment_relations.text,
//        'created_at', comment_relations.created_at,
//        'updated_at', comment_relations.updated_at,
//        'user', json_build_object(
//            'id', users.id,
//            'login', users.login,
//            'avatar', users.avatar
//        )
//    ))
//    FROM comment_relations
//    JOIN users ON comment_relations.user_id = users.id
//    WHERE comment_relations.comment_id = comment_relations.comment_id
//) AS com
//FROM comment_relations
//WHERE comment_relations.comment_id IN (1, 2, 3)
//GROUP BY comment_relations.comment_id;
//        ")->fetchAll();
//        foreach ($comments as $comment) {
//            $dop = NewsService::getDopComments($comment['id']);

//        }
//        echo '<pre>';
//        var_dump($comments);
//        echo '</pre>';
//        exit();
        return new View('news.show', ['files' => $news, 'comments' => $comments, 'paginate' => $paginate]);
    }
}