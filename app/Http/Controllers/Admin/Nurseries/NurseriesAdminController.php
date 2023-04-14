<?php

namespace App\Http\Controllers\Admin\Nurseries;

use App\Exceptions\ErrorView;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Filters\Admin\AdminUsersFilter;
use App\Http\Requests\Admin\NewsAdminRequest;
use App\Http\Requests\Admin\NurseriesAdminRequest;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class NurseriesAdminController
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
        $last_page = $this->paginate->lastPage('nurseries');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $query = "SELECT * FROM nurseries ORDER BY created_at DESC OFFSET ? LIMIT ?";
        $result = DB::select($query, [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();


        return new View('admin.nurseries.nurseries', ['result' => $result, 'paginate' => $paginate]);
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

        $result = DB::select("SELECT * FROM nurseries WHERE id = ?", [$_GET['id']])->fetch();

        return new View('admin.nurseries.edit', ['result' => $result]);
    }

    public function update()
    {
        if (!Authorization::authCheck()) header('Location: /');

        $request = [
            'id' => $_GET['id'],
            'title' => StrService::stringFilter($_POST['title']),
            'text' => StrService::stringFilter($_POST['text']),
            'address' => StrService::stringFilter($_POST['address']),
            'phone' => StrService::stringFilter($_POST['phone']),
            'created_at' => StrService::stringFilter($_POST['created_at']),
            'updated_at' => StrService::stringFilter($_POST['updated_at']),
        ];

        $errorPost = NurseriesAdminRequest::validated($request);

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
                $url = MediaService::generateUrl($photo, 'resources/images/tmp/', 'nurseries', 'image');
                if (!move_uploaded_file($photo['tmp_name'], $url)){
                    $url = null;
                }
                setcookie('load_image_nurseries', $url, 0, '/admin/nurseries');

                return new View('admin.nurseries.edit', ['error' => $error, 'errorPost' => $errorPost, 'result' => $request, 'tmpRoute' => $url]);
            }else{
                $url = $_COOKIE['load_image_nurseries'] ?? '';
                return new View('admin.nurseries.edit', ['error' => $error, 'errorPost' => $errorPost, 'result' => $request, 'tmpRoute' => $url]);
            }
        }

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');

        if ($photo){
            MediaService::tmpClear();
            $url = MediaService::generateUrl($photo, 'resources/images/nurseries/', 'nurseries', 'image');
            $oldImageUrl = DB::select("SELECT image FROM nurseries WHERE id = ?", [$request['id']])->fetch();
            MediaService::deletePhoto($oldImageUrl['image']);
            move_uploaded_file($photo['tmp_name'], $url);
            DB::update("UPDATE nurseries SET image = ?, title = ?, text = ?, address = ?, phone = ?, updated_at = ? WHERE id = ?",
                [$url, $request['title'], $request['text'], $request['address'], $request['phone'], $dateNow, $request['id']]);
        }else{
            if (isset($_COOKIE['load_image_nurseries'])){
                    if (file_exists($_COOKIE['load_image_nurseries'])){
                        $url = MediaService::generateUrlFromString($_COOKIE['load_image_nurseries'], 'resources/images/nurseries/', 'nurseries', 'image');
                        rename($_COOKIE['load_image_nurseries'], $url);
                        setcookie('load_image_nurseries', '', -60*60*24*365, '/admin/nurseries');
                        $oldImageUrl = DB::select("SELECT image FROM nurseries WHERE id = ?", [$request['id']])->fetch();
                        MediaService::deletePhoto($oldImageUrl['image']);

                        DB::update("UPDATE nurseries SET image = ?, title = ?, text = ?, address = ?, phone = ?, updated_at = ? WHERE id = ?",
                            [$url, $request['title'], $request['text'], $request['address'], $request['phone'], $dateNow, $request['id']]);
                    }
            }else{
                DB::update("UPDATE nurseries SET title = ?, text = ?, address = ?, phone = ?, updated_at = ? WHERE id = ?",
                    [$request['title'], $request['text'], $request['address'], $request['phone'], $dateNow, $request['id']]);
            }

        }

        header("Location: /admin/nurseries?page={$this->paginate->getPage()}");
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