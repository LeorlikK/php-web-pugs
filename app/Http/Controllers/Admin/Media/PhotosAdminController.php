<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Requests\Admin\PhotosAdminUpdateRequest;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class PhotosAdminController
{
    const LIMIT_ITEM_PAGE = 8;

    private PaginateService $paginate;

    public function __construct()
    {
        if (!Authorization::authCheck()) header('Location: /');
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $result = DB::select("SELECT * FROM photos ORDER BY created_at DESC OFFSET ? LIMIT ?",
            [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();

        return new View('admin.media.photos.photos', ['result' => $result, 'paginate' => $paginate]);
    }

    public function store():?View
    {
        $photo = $_FILES['photos'];
        $errors = PhotoRequest::validated($photo);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($errors){
            $result = DB::select("SELECT * FROM photos OFFSET ? LIMIT ?",
                [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('admin.media.photos.photos', ['errors' => $errors, 'result' => $result, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUniqueUrl($photo, 'resources/images/photos/', 'photos', 'url');
        $name = MediaService::createName($photo['name']);
        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        DB::insert("INSERT INTO photos (url, name, created_at, updated_at) VALUES (?, ?, ?, ?)",
            [$url, $name, $dateNow, $dateNow]);

        move_uploaded_file($photo['tmp_name'], $url);
        header("Location: /admin/photos?page={$this->paginate->getPage()}");
        exit();
    }

    public function edit():View
    {
        setcookie('old_value', '', -60*60*24);
        $result = DB::select("SELECT * FROM photos WHERE id = ?", [StrService::stringFilter($_GET['id']?? null)])->fetch();

        return new View('admin.media.photos.edit', ['result' => $result]);
    }

    public function update():?View
    {
        $request = [
            'name' => StrService::stringFilter($_POST['name']),
            'id' => StrService::stringFilter($_GET['id'])
        ];
        $errors = PhotosAdminUpdateRequest::validated($request);

        if (!$errors){
            setcookie('old_value', '', -60*60*24);
            $name = MediaService::createName($request['name']);
            $dateTime = new DateTime();
            $dateNow = $dateTime->format('Y-m-d H:i:s');
            setcookie('old_value', '', -60*60*24);
            DB::update("UPDATE photos SET name = ?, updated_at = ? WHERE id = ?",
                [$name, $dateNow, $request['id']]);

            header('Location: /admin/photos/edit?id=' . $request['id']);
            exit();
        }

        $result = DB::select("SELECT * FROM photos WHERE id = ?", [$request['id']])->fetch();
        foreach ($request as $key => $value){
            $result[$key] = $value;
        }

        return new View('admin.media.photos.edit', ['result' => $result, 'errors' => $errors]);
    }

    public function delete():bool
    {
        $id = StrService::stringFilter($_POST['id']);

        $photos = DB::select("SELECT * FROM photos WHERE id = ?", [$id])->fetch();
        DB::delete("DELETE FROM photos WHERE id = ?", [$id]);
        if (file_exists($photos['url'])){
            unlink($photos['url']);
        }
        return true;
    }
}