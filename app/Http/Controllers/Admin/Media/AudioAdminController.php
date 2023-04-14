<?php

namespace App\Http\Controllers\Admin\Media;

use App\Exceptions\ErrorView;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Filters\Admin\AdminUsersFilter;
use App\Http\Requests\Media\AudioRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class AudioAdminController
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
        $last_page = $this->paginate->lastPage('audio');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $query = "SELECT * FROM audio ORDER BY created_at DESC OFFSET ? LIMIT ?";
        $result = DB::select($query, [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();


        return new View('admin.media.audio.audio', ['result' => $result, 'paginate' => $paginate]);
    }

    public function create():?View
    {
        $audio = $_FILES['audio'];
        $error = AudioRequest::validated($audio);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('audio');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($error){
            $result = DB::select("SELECT * FROM audio OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('admin.media.audio.audio', ['error' => $error, 'result' => $result, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUrl($audio, 'resources/audio/', 'audio', 'url');
        $name = MediaService::createName($audio);

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        DB::insert("INSERT INTO audio (url, name, created_at, updated_at) VALUES (?, ?, ?, ?)",
            [$url, $name, $dateNow, $dateNow]);

        move_uploaded_file($audio['tmp_name'], $url);
        header("Location: /admin/audio?page={$this->paginate->getPage()}");
        exit();
    }

    public function edit():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $result = DB::select("SELECT * FROM audio WHERE id = ?", [$_GET['id']])->fetch();

        return new View('admin.media.audio.edit', ['result' => $result]);
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
            DB::update("UPDATE audio SET name = ?, updated_at = ?
                WHERE id = ?", [$name, $dateNow, $request['id']]);

            header('Location: /admin/audio/edit?id=' . $_GET['id']);
            exit();
        }catch (\Exception $exception){
            return new ErrorView('Не удалось обновить изображение');
        }
    }

    public function delete()
    {
        try {
            $id = StrService::stringFilter($_POST['id']);

            $audio = DB::select("SELECT * FROM audio WHERE id = ?", [$id])->fetch();
            DB::delete("DELETE FROM audio WHERE id = ?", [$id]);
            if (file_exists($audio['url'])){
                unlink($audio['url']);
            }
            return true;
        }catch (\Exception $exception){
            return new ErrorView('Не удалось удалить аудио');
        }
    }
}