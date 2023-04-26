<?php

namespace App\Http\Controllers\Admin\News;

use App\Exceptions\Error;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsAdminRequest;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class NewsAdminController extends Controller
{
    const LIMIT_ITEM_PAGE = 10;

    private PaginateService $paginate;

    public function __construct()
    {
        if (!Authorization::authCheck()) header('Location: /');
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('news');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $result = DB::select("SELECT * FROM news ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();

        return new View('admin.news.news', ['result' => $result, 'paginate' => $paginate]);
    }

    public function create():View
    {
        setcookie('create_image_news', '', -60*60*24*365, '/admin/news');
        return new View('admin.news.create', []);
    }

    public function store():View
    {
        $request = [
            'title' => StrService::stringFilter($_POST['title']),
            'short' => StrService::stringFilter($_POST['short']),
            'text' => StrService::stringFilter($_POST['text']),
            'publish' => StrService::stringFilter($_POST['publish']??'0')
        ];

        if ($_FILES['photos']['size'] !== 0){
            $photo = $_FILES['photos'];
            $errorsFile = PhotoRequest::validated($photo);
        }else{
            $photo = null;
            $errorsFile = null;
        }

        $errors = NewsAdminRequest::validated($request);

        if ($errorsFile || $errors){
            if (isset($photo['name']) && $photo['name'] !== ''){
                MediaService::tmpClear();
                $url = MediaService::generateFolderUniqueUrl($photo, 'resources/images/tmp/');
                if (!move_uploaded_file($photo['tmp_name'], $url)){
                    $url = null;
                }
                setcookie('create_image_news', $url, 0, '/admin/news');

                return new View('admin.news.create', ['errorsFile' => $errorsFile, 'errors' => $errors, 'result' => $request, 'tmpRoute' => $url]);
            }else{
                $url = $_COOKIE['create_image_news'] ?? '';
                return new View('admin.news.create', ['errorsFile' => $errorsFile, 'errors' => $errors, 'result' => $request, 'tmpRoute' => $url]);
            }
        }

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');

        if ($photo){
            MediaService::tmpClear();
            $url = MediaService::generateUniqueUrl($photo, 'resources/images/news/', 'news', 'image');
            move_uploaded_file($photo['tmp_name'], $url);
        }else{
            if (isset($_COOKIE['create_image_news'])){
                if (file_exists($_COOKIE['create_image_news'])){
                    $url = MediaService::generateUrlFromString($_COOKIE['create_image_news'], 'resources/images/news/', 'news', 'image');
                    rename($_COOKIE['create_image_news'], $url);
                }else{
                    throw new Error('Нет изображения на которое ссылаются куки', 400);
                }
            }else{
                throw new Error('Нет куков с изображением', 400);
            }
        }

        setcookie('create_image_news', '', -60*60*24*365, '/admin/news');
        $user = DB::select("SELECT id FROM users WHERE email = ?", [$_SESSION['authorize']])->fetch();
        $publish = $request['publish'] === 'on' ? true : 0;
        DB::insert("INSERT INTO news (user_id, image, title, short, text, publish, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$user['id'], $url, $request['title'], $request['short'], $request['text'], $publish, $dateNow, $dateNow]);

        header("Location: /admin/news?page={$this->paginate->getPage()}");
        exit();
    }

    public function edit():View
    {
        $result = DB::select("SELECT * FROM news WHERE id = ?", [StrService::stringFilter($_GET['id'])])->fetch();

        return new View('admin.news.edit', ['result' => $result]);
    }

    public function update():View
    {
        $request = [
            'id' => $_GET['id'],
            'title' => StrService::stringFilter($_POST['title']),
            'short' => StrService::stringFilter($_POST['short']),
            'text' => StrService::stringFilter($_POST['text']),
            'publish' => StrService::stringFilter($_POST['publish']??'0')
        ];

        $errors = NewsAdminRequest::validated($request);

        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] !== ''){
            $photo = $_FILES['image'];
            $errorsFile = PhotoRequest::validated($photo);
        }else{
            $request['image'] = StrService::stringFilter($_POST['default_image']);
            $photo = null;
            $errorsFile = null;
        }

        if ($errorsFile || $errors){
            if ($photo){
                MediaService::tmpClear();
                $url = MediaService::generateFolderUniqueUrl($photo, 'resources/images/tmp/');
                if (!move_uploaded_file($photo['tmp_name'], $url)){
                    $url = null;
                }
                setcookie('load_image_news', $url, 0, '/admin/news');

                return new View('admin.news.edit', ['errorsFile' => $errorsFile, 'errors' => $errors, 'result' => $request, 'tmpRoute' => $url]);
            }else{
                $url = $_COOKIE['load_image_news'] ?? '';
                return new View('admin.news.edit', ['errorsFile' => $errorsFile, 'errors' => $errors, 'result' => $request, 'tmpRoute' => $url]);
            }
        }

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        $publish = $request['publish'] === 'on' ? true : 0;

        if ($photo){
            MediaService::tmpClear();
            $url = MediaService::generateUniqueUrl($photo, 'resources/images/news/', 'news', 'image');
            $oldImageUrl = DB::select("SELECT image FROM news WHERE id = ?", [$request['id']])->fetch();
            MediaService::deletePhoto($oldImageUrl['image']);
            move_uploaded_file($photo['tmp_name'], $url);
            DB::update("UPDATE news SET image = ?, title = ?, short = ?, text = ?, publish = ?, updated_at = ? WHERE id = ?",
                [$url, $request['title'], $request['short'], $request['text'], $publish, $dateNow, $request['id']]);
        }else{
            if (isset($_COOKIE['load_image_news'])){
                if (file_exists($_COOKIE['load_image_news'])){
                    $url = MediaService::generateUrlFromString($_COOKIE['load_image_news'], 'resources/images/news/', 'news', 'image');
                    rename($_COOKIE['load_image_news'], $url);
                    setcookie('load_image_news', '', -60*60*24*365, '/admin/news');
                    $oldImageUrl = DB::select("SELECT image FROM news WHERE id = ?", [$request['id']])->fetch();
                    MediaService::deletePhoto($oldImageUrl['image']);

                    DB::update("UPDATE news SET image = ?, title = ?, short = ?, text = ?, publish = ?, updated_at = ? WHERE id = ?",
                        [$url, $request['title'], $request['short'], $request['text'], $publish, $dateNow, $request['id']]);
                }
            }else{
                DB::update("UPDATE news SET title = ?, short = ?, text = ?, publish = ?, updated_at = ? WHERE id = ?",
                    [$request['title'], $request['short'], $request['text'], $publish, $dateNow, $request['id']]);
            }
        }

        header("Location: /admin/news?page={$this->paginate->getPage()}");
        exit();
    }

    public function delete():bool
    {
        $id = StrService::stringFilter($_POST['id']);

        DB::delete("DELETE FROM comment_relations WHERE comment_id IN (SELECT id FROM news_comments WHERE news_id = ?)", [$id]);
        DB::delete("DELETE FROM news_comments WHERE news_id = ?", [$id]);
        $news = DB::select("SELECT image FROM news WHERE id = ?", [$id])->fetch();
        MediaService::deletePhoto($news['image']);
        DB::delete("DELETE FROM news WHERE id = ?", [$id]);

        return true;
    }
}