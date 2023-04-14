<?php

namespace App\Http\Controllers\Admin\Media;

use App\Exceptions\ErrorView;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Filters\Admin\AdminUsersFilter;
use App\Http\Requests\Media\VideoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class VideoAdminController
{
    const LIMIT_ITEM_PAGE = 2;

    private PaginateService $paginate;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('video');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $query = "SELECT * FROM video ORDER BY created_at DESC OFFSET ? LIMIT ?";
        $result = DB::select($query, [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();


        return new View('admin.media.video.video', ['result' => $result, 'paginate' => $paginate]);
    }

    public function create():?View
    {
        $video = $_FILES['video'];
        $error = VideoRequest::validated($video);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('video');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($error){
            $result = DB::select("SELECT * FROM video OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('admin.media.video.video', ['error' => $error, 'result' => $result, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUrl($video, 'resources/video/', 'video', 'url');
        $name = MediaService::createName($video);

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        DB::insert("INSERT INTO video (url, name, created_at, updated_at) VALUES (?, ?, ?, ?)",
            [$url, $name, $dateNow, $dateNow]);

        move_uploaded_file($video['tmp_name'], $url);
        header("Location: /admin/video?page={$this->paginate->getPage()}");
        exit();
    }

    public function edit():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $result = DB::select("SELECT * FROM video WHERE id = ?", [$_GET['id']])->fetch();

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
            DB::update("UPDATE video SET name = ?, updated_at = ?
                WHERE id = ?", [$name, $dateNow, $request['id']]);

            header('Location: /admin/video/edit?id=' . $_GET['id']);
            exit();
        }catch (\Exception $exception){
            return new ErrorView('Не удалось обновить изображение');
        }
    }

    public function delete()
    {
        try {
            $id = StrService::stringFilter($_POST['id']);

            $video = DB::select("SELECT * FROM video WHERE id = ?", [$id])->fetch();
            DB::delete("DELETE FROM video WHERE id = ?", [$id]);
            if (file_exists($video['url'])){
                unlink($video['url']);
            }
            return true;
        }catch (\Exception $exception){
            return new ErrorView('Не удалось удалить видео');
        }
    }
}