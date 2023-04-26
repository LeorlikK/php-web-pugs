<?php

namespace App\Http\Controllers\Admin\Nurseries;

use App\Exceptions\Error;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NurseriesAdminRequest;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class NurseriesAdminController extends Controller
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
        $last_page = $this->paginate->lastPage('nurseries');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $result = DB::select("SELECT * FROM nurseries ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();

        return new View('admin.nurseries.nurseries', ['result' => $result, 'paginate' => $paginate]);
    }

    public function create():View
    {
        setcookie('create_image_nurseries', '', -60*60*24*365, '/admin/nurseries');
        return new View('admin.nurseries.create', []);
    }

    public function store():View
    {
        $request = [
            'title' => StrService::stringFilter($_POST['title']),
            'text' => StrService::stringFilter($_POST['text']),
            'address' => StrService::stringFilter($_POST['address']),
            'phone' => StrService::stringFilter($_POST['phone']),
        ];

        if (empty($_COOKIE['create_image_nurseries'])){
            $photo = $_FILES['image'];
            $errorsFile = PhotoRequest::validated($photo);
        }else{
            $photo = null;
            $errorsFile = null;
        }

        $errors = NurseriesAdminRequest::validated($request);

        if ($errorsFile || $errors){
            if (isset($photo) && $photo['name'] !== ''){
                MediaService::tmpClear();
                $url = MediaService::generateFolderUniqueUrl($photo, 'resources/images/tmp/');
                if (!move_uploaded_file($photo['tmp_name'], $url)){
                    $url = null;
                }
                setcookie('create_image_nurseries', $url, 0, '/admin/nurseries');

                return new View('admin.nurseries.create', ['errorsFile' => $errorsFile, 'errors' => $errors, 'result' => $request, 'tmpRoute' => $url]);
            }else{
                $url = $_COOKIE['create_image_nurseries'] ?? '';
                return new View('admin.nurseries.create', ['errorsFile' => $errorsFile, 'errors' => $errors, 'result' => $request, 'tmpRoute' => $url]);
            }
        }

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');

        if ($photo){
            MediaService::tmpClear();
            $url = MediaService::generateUniqueUrl($photo, 'resources/images/nurseries/', 'nurseries', 'image');
            move_uploaded_file($photo['tmp_name'], $url);
        }else{
            if (isset($_COOKIE['create_image_nurseries'])){
                if (file_exists($_COOKIE['create_image_nurseries'])){
                    $url = MediaService::generateUrlFromString($_COOKIE['create_image_nurseries'], 'resources/images/nurseries/', 'nurseries', 'image');
                    rename($_COOKIE['create_image_nurseries'], $url);
                }else{
                    throw new Error('Нет изображения на которое ссылаются куки', 400);
                }
            }else{
                throw new Error('Нет куков с изображением', 400);
            }
        }

        setcookie('create_image_nurseries', '', -60*60*24*365, '/admin/nurseries');
        DB::insert("INSERT INTO nurseries (image, title, text, address, phone, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$url, $request['title'], $request['text'], $request['address'], $request['phone'], $dateNow, $dateNow]);

        header("Location: /admin/nurseries?page={$this->paginate->getPage()}");
        exit();
    }

    public function edit():View
    {
        setcookie('old_value', '', -60*60*24);
        $result = DB::select("SELECT * FROM nurseries WHERE id = ?", [StrService::stringFilter($_GET['id'])])->fetch();

        return new View('admin.nurseries.edit', ['result' => $result]);
    }

    public function update():View
    {
        $request = [
            'id' => StrService::stringFilter($_GET['id']),
            'title' => StrService::stringFilter($_POST['title']),
            'text' => StrService::stringFilter($_POST['text']),
            'address' => StrService::stringFilter($_POST['address']),
            'phone' => StrService::stringFilter($_POST['phone']),
            'created_at' => StrService::stringFilter($_POST['created_at']),
            'updated_at' => StrService::stringFilter($_POST['updated_at']),
        ];

        $errors = NurseriesAdminRequest::validated($request);

        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] !== ''){
            $photo = $_FILES['image'];
            $errorFile = PhotoRequest::validated($photo);
        }else{
            $request['image'] = StrService::stringFilter($_POST['default_image']);
            $photo = null;
            $errorFile = null;
        }

        if ($errorFile || $errors){
            if ($photo){
                MediaService::tmpClear();
                $url = MediaService::generateFolderUniqueUrl($photo, 'resources/images/tmp/');
                if (!move_uploaded_file($photo['tmp_name'], $url)){
                    $url = null;
                }
                setcookie('load_image_nurseries', $url, 0, '/admin/nurseries');

                return new View('admin.nurseries.edit', ['errorFile' => $errorFile, 'errors' => $errors, 'result' => $request, 'tmpRoute' => $url]);
            }else{
                $url = $_COOKIE['load_image_nurseries'] ?? '';
                return new View('admin.nurseries.edit', ['errorFile' => $errorFile, 'errors' => $errors, 'result' => $request, 'tmpRoute' => $url]);
            }
        }

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        setcookie('old_value', '', -60*60*24);

        if ($photo){
            MediaService::tmpClear();
            $url = MediaService::generateUniqueUrl($photo, 'resources/images/nurseries/', 'nurseries', 'image');
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

    public function delete():bool
    {
        $id = StrService::stringFilter($_POST['id']);
        $image = DB::select("SELECT image FROM nurseries WHERE id = ?", [$id])->fetch();
        MediaService::deletePhoto($image['image']);
        DB::delete("DELETE FROM nurseries WHERE id = ?", [$id]);
        return true;
    }
}