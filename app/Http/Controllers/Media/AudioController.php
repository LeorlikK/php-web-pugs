<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Requests\Media\AudioRequest;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class AudioController
{
    private PaginateService $paginate;

    const LIMIT_ITEM_PAGE = 2;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function index():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('audio');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $audio = DB::select("SELECT * FROM audio OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();

        return new View('media.audio', ['files' => $audio, 'paginate' => $paginate]);
    }

    public function store():?View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $audio = $_FILES['audio'];
        $error = AudioRequest::validated($audio);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('audio');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($error){
            $audio = DB::select("SELECT * FROM audio OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('media.photos', ['error' => $error, 'files' => $audio, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUniqueUrl($audio, 'resources/audio/', 'audio', 'url');
        $name = MediaService::createName($audio['name']);

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        DB::insert("INSERT INTO audio (url, name, created_at, updated_at) VALUES (?, ?, ?, ?)",
            [$url, $name, $dateNow, $dateNow]);

        move_uploaded_file($audio['tmp_name'], $url);
        header("Location: /media/audio?page={$this->paginate->getPage()}");
        exit();
    }

    public function delete()
    {
        if (!Authorization::authCheck()) header('Location: /');

        $url = StrService::stringFilter($_POST['delete']);

        if (file_exists($url)){
            $res = unlink($url);
            if ($res){
                DB::delete("DELETE FROM audio WHERE url = ?", [$url]);
            }
        }

        header("Location: /media/audio?page={$this->paginate->getPage()}");
        exit();
    }
}