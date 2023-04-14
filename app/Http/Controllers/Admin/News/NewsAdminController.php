<?php

namespace App\Http\Controllers\Admin\Media;

use App\Exceptions\ErrorView;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Filters\Admin\AdminUsersFilter;
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
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $query = "SELECT * FROM photos ORDER BY created_at DESC OFFSET ? LIMIT ?";
        $result = DB::select($query, [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();


        return new View('admin.media.photos.photos', ['result' => $result, 'paginate' => $paginate]);
    }

    public function create():?View
    {
        $photo = $_FILES['photos'];
        $error = PhotoRequest::validated($photo);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($error){
            $result = DB::select("SELECT * FROM photos OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('admin.media.photos.photos', ['error' => $error, 'result' => $result, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUrl($photo, 'resources/images/photos/', 'photos', 'url');
        $name = MediaService::createName($photo);

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
        if (!Authorization::authCheck()) header('Location: /');

        $result = DB::select("SELECT * FROM photos WHERE id = ?", [$_GET['id']])->fetch();

        return new View('admin.media.video.edit', ['result' => $result]);
    }

    public function update()
    {
        if (!Authorization::authCheck()) header('Location: /');

        try {
            $request = [
                'name' => StrService::stringFilter($_POST['name']),
                'id' => $_GET['id']
            ];

            $pos = strripos($request['name'], '.');
            if ($pos){
                $name = mb_substr($request['name'], 0, $pos);
            }else{
                $name = $request['name'];
            }

            $dateTime = new DateTime();
            $dateNow = $dateTime->format('Y-m-d H:i:s');
            DB::update("UPDATE photos SET name = ?, updated_at = ?
                WHERE id = ?", [$name, $dateNow, $request['id']]);

            header('Location: /admin/photos/edit?id=' . $_GET['id']);
            exit();
        }catch (\Exception $exception){
            return new ErrorView('Не удалось обновить изображение');
        }
    }

    public function delete()
    {
        if (!Authorization::authCheck()) header('Location: /');

        try {
            $id = StrService::stringFilter($_POST['id']);

            $photos = DB::select("SELECT * FROM photos WHERE id = ?", [$id])->fetch();
            DB::delete("DELETE FROM photos WHERE id = ?", [$id]);
            if (file_exists($photos['url'])){
                unlink($photos['url']);
            }
            return true;
        }catch (\Exception $exception){
            return new ErrorView('Не удалось удалить изображение');
        }
    }
}