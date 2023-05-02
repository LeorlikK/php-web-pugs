<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\VideoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\MediaSizeService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use App\Http\Controllers\Render\View;

class VideoController extends Controller
{
    private PaginateService $paginate;

    const LIMIT_ITEM_PAGE = 8;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function index():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('video');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $video = DB::select("SELECT * FROM video LIMIT ? OFFSET ?", [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();

        return new View('media.video', ['files' => $video, 'paginate' => $paginate]);
    }

    public function store():?View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $video = $_FILES['video'];
        $error = VideoRequest::validated($video);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('video');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($error){
            $video = DB::select("SELECT * FROM video LIMIT ? OFFSET ?", [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();
            return new View('media.video', ['error' => $error, 'files' => $video, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUniqueUrl($video, 'resources/video/', 'video', 'url');
        $name = MediaService::createName($video['name']);

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        DB::insert("INSERT INTO video (url, name, created_at, updated_at, size) VALUES (?, ?, ?, ?, ?)",
            [$url, $name, $dateNow, $dateNow, $video['size']]);

        move_uploaded_file($video['tmp_name'], $url);
        MediaSizeService::plusVideoSize($video['size']);
        header("Location: /media/video?page={$this->paginate->getPage()}");
        exit();
    }

    public function delete():void
    {
        if (!Authorization::authCheck()) header('Location: /');

        $id = StrService::stringFilter($_POST['delete']);

        $video = DB::select("SELECT * FROM video WHERE id = ?", [$id])->fetch();
        DB::delete("DELETE FROM video WHERE id = ?", [$id]);
        if (file_exists($video['url'])){
            MediaSizeService::minusVideoSize($video['size']);
            unlink($video['url']);
        }

        header("Location: /media/video?page={$this->paginate->getPage()}");
        exit();
    }
}