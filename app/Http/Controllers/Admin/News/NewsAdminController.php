<?php

namespace App\Http\Controllers\Admin\News;

use App\Exceptions\ErrorView;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Filters\Admin\AdminUsersFilter;
use App\Http\Requests\Admin\NewsAdminRequest;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class NewsAdminController
{
    const LIMIT_ITEM_PAGE = 8;

    private PaginateService $paginate;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('news');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $query = "SELECT * FROM news ORDER BY created_at DESC OFFSET ? LIMIT ?";
        $result = DB::select($query, [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();


        return new View('admin.news.news', ['result' => $result, 'paginate' => $paginate]);
    }

    public function create()
    {
        return new View('admin.news.create', []);
    }

    public function store():?View
    {
        $request = [
            'title' => StrService::stringFilter($_POST['title']),
            'short' => StrService::stringFilter($_POST['short']),
            'text' => StrService::stringFilter($_POST['text']),
        ];
        $photo = $_FILES['photos'];
        $error = PhotoRequest::validated($photo);
        $errorPost = NewsAdminRequest::validated($request);

        if ($error || $errorPost){
            $oldImages = scandir('resources/images/tmp/', SCANDIR_SORT_NONE);
            $oldImages = array_slice($oldImages, 2);
            foreach ($oldImages as $oldImage){
                unlink('resources/images/tmp/' . $oldImage);
            }
            $url = $photo['tmp_name'];
            $tmpRoute = 'resources/images/tmp/' . $photo['name'];
            if (!move_uploaded_file($url, $tmpRoute)){
                $tmpRoute = null;
            }
            return new View('admin.news.create', ['error' => $error, 'errorPost' => $errorPost, 'result' => $request, 'tmpRoute' => $tmpRoute]);
        }

        $url = MediaService::generateUrl($photo, 'resources/images/news/', 'news', 'image');

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        $user = DB::select("SELECT id FROM users WHERE email = ?", [$_SESSION['authorize']])->fetch();

        DB::insert("INSERT INTO news (user_id, image, title, short, text, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$user['id'], $url, $request['title'], $request['short'], $request['text'], $dateNow, $dateNow]);

        move_uploaded_file($photo['tmp_name'], $url);
        header("Location: /admin/news?page={$this->paginate->getPage()}");
        exit();
    }

    public function edit():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $result = DB::select("SELECT * FROM news WHERE id = ?", [$_GET['id']])->fetch();

        return new View('admin.news.edit', ['result' => $result]);
    }

    public function update()
    {
        if (!Authorization::authCheck()) header('Location: /');

        $request = [
            'id' => $_GET['id'],
            'title' => StrService::stringFilter($_POST['title']),
            'short' => StrService::stringFilter($_POST['short']),
            'text' => StrService::stringFilter($_POST['text']),
        ];

        $errorPost = NewsAdminRequest::validated($request);

        if (isset($_FILES['image']) && $_FILES['image']['name'] !== ''){
            $photo = $_FILES['image'];
            $error = PhotoRequest::validated($photo);
        }else{
            $request['image'] = StrService::stringFilter($_POST['default_image']);
            $photo = null;
            $error = null;
        }

        if ($error || $errorPost){
            if ($photo){
                MediaService::tmpClear();
                $url = MediaService::generateUrl($photo, 'resources/images/tmp/', 'news', 'image');
                if (!move_uploaded_file($photo['tmp_name'], $url)){
                    $url = null;
                }
                setcookie('load_image_news', $url, 0, '/admin/news');

                return new View('admin.news.edit', ['error' => $error, 'errorPost' => $errorPost, 'result' => $request, 'tmpRoute' => $url]);
            }else{
                $url = $_COOKIE['load_image_news'] ?? '';
                return new View('admin.news.edit', ['error' => $error, 'errorPost' => $errorPost, 'result' => $request, 'tmpRoute' => $url]);
            }
        }

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');

        if ($photo){
            MediaService::tmpClear();
            $url = MediaService::generateUrl($photo, 'resources/images/news/', 'news', 'image');
            $oldImageUrl = DB::select("SELECT image FROM news WHERE id = ?", [$request['id']])->fetch();
            MediaService::deletePhoto($oldImageUrl['image']);
            move_uploaded_file($photo['tmp_name'], $url);
            DB::update("UPDATE news SET image = ?, title = ?, short = ?, text = ?, updated_at = ? WHERE id = ?",
                [$url, $request['title'], $request['short'], $request['text'], $dateNow, $request['id']]);
        }else{
            if (isset($_COOKIE['load_image_news'])){
                if (file_exists($_COOKIE['load_image_news'])){
                    $url = MediaService::generateUrlFromString($_COOKIE['load_image_news'], 'resources/images/news/', 'news', 'image');
                    rename($_COOKIE['load_image_news'], $url);
                    setcookie('load_image_news', '', -60*60*24*365, '/admin/news');
                    $oldImageUrl = DB::select("SELECT image FROM news WHERE id = ?", [$request['id']])->fetch();
                    MediaService::deletePhoto($oldImageUrl['image']);

                    DB::update("UPDATE news SET image = ?, title = ?, short = ?, text = ?, updated_at = ? WHERE id = ?",
                        [$url, $request['title'], $request['short'], $request['text'], $dateNow, $request['id']]);
                }
            }else{
                DB::update("UPDATE news SET title = ?, short = ?, text = ?, updated_at = ? WHERE id = ?",
                    [$request['title'], $request['short'], $request['text'], $dateNow, $request['id']]);
            }

        }

        header("Location: /admin/news?page={$this->paginate->getPage()}");
        exit();
    }

    public function delete()
    {
        if (!Authorization::authCheck()) header('Location: /');

        try {
            $id = StrService::stringFilter($_POST['id']);

            DB::delete("DELETE FROM comment_relations WHERE comment_id IN (SELECT id FROM news_comments WHERE news_id = ?)", [$id]);
            DB::delete("DELETE FROM news_comments WHERE news_id = ?", [$id]);
            $news = DB::select("SELECT image FROM news WHERE id = ?", [$id])->fetch();
            MediaService::deletePhoto($news['image']);
            DB::delete("DELETE FROM news WHERE id = ?", [$id]);

            return true;
        }catch (\Exception $exception){
            return new ErrorView('Не удалось удалить изображение');
        }
    }
}