<?php

namespace App\Http\Controllers\Media;

use App\Exceptions\ErrorView;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Requests\Media\VideoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use Database\DB;
use DateTime;
use Views\View;

class VideoController
{
    private PaginateService $paginate;

    const LIMIT_ITEM_PAGE = 8;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)((int)($_GET['page'] ?? 1));
    }

    public function index():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('video');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $video = DB::select("SELECT * FROM video OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();

        return new View('media.video', ['files' => $video, 'paginate' => $paginate]);
    }

    public function create():?View
    {
        $video = $_FILES['video'];
        $error = VideoRequest::validated($video);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('video');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($error){
            $video = DB::select("SELECT * FROM video OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('media.video', ['error' => $error, 'files' => $video, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUrl($video, 'resources/video/', 'video', 'url');
        $name = MediaService::createName($video);

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        DB::insert("INSERT INTO video (url, name, created_at, updated_at) VALUES (?, ?, ?, ?)",
            [$url, $name, $dateNow, $dateNow]);

        move_uploaded_file($video['tmp_name'], $url);
        header("Location: /media/video?page={$this->paginate->getPage()}");
        exit();
    }

    public function delete()
    {
        $url = $_POST['delete'];

        if (file_exists($url)){
            $res = unlink($url);
            if ($res){
                DB::delete("DELETE FROM video WHERE url = ?", [$url]);
            }
        }

        header("Location: /media/video?page={$this->paginate->getPage()}");
        exit();
    }
}