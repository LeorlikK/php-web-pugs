<?php

namespace App\Http\Controllers\Admin\Media;

use App\Exceptions\ErrorView;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Filters\Admin\AdminUsersFilter;
use App\Http\Requests\Admin\VideoAdminUpdateRequest;
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
        if (!Authorization::authCheck()) header('Location: /');
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('video');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $result = DB::select("SELECT * FROM video ORDER BY created_at DESC OFFSET ? LIMIT ?",
            [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();

        return new View('admin.media.video.video', ['result' => $result, 'paginate' => $paginate]);
    }

    public function store():?View
    {
        $video = $_FILES['video'];
        $errors = VideoRequest::validated($video);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('video');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($errors){
            $result = DB::select("SELECT * FROM video OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('admin.media.video.video', ['errors' => $errors, 'result' => $result, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUniqueUrl($video, 'resources/video/', 'video', 'url');
        $name = MediaService::createName($video['name']);

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
        setcookie('old_value', '', -60*60*24);
        $result = DB::select("SELECT * FROM video WHERE id = ?", [$_GET['id']])->fetch();

        return new View('admin.media.video.edit', ['result' => $result]);
    }

    public function update():?View
    {
        $request = [
            'name' => StrService::stringFilter($_POST['name']),
            'id' => StrService::stringFilter($_GET['id'])
        ];
        $errors = VideoAdminUpdateRequest::validated($request);

        if (!$errors){
            $name = MediaService::createName($request['name']);
            $dateTime = new DateTime();
            $dateNow = $dateTime->format('Y-m-d H:i:s');
            setcookie('old_value', '', -60*60*24);
            DB::update("UPDATE video SET name = ?, updated_at = ? WHERE id = ?",
                [$name, $dateNow, $request['id']]);

            header('Location: /admin/video/edit?id=' . $request['id']);
            exit();
        }

        $result = DB::select("SELECT * FROM video WHERE id = ?", [$request['id']])->fetch();
        foreach ($request as $key => $value){
            $result[$key] = $value;
        }

        return new View('admin.media.video.edit', ['result' => $result, 'errors' => $errors]);
    }

    public function delete():bool
    {
        $id = StrService::stringFilter($_POST['id']);

        $video = DB::select("SELECT * FROM video WHERE id = ?", [$id])->fetch();
        DB::delete("DELETE FROM video WHERE id = ?", [$id]);
        if (file_exists($video['url'])){
            unlink($video['url']);
        }
        return true;
    }
}