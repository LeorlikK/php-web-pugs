<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class PhotosController extends Controller
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
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $photos = DB::select("SELECT * FROM photos OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();

        return new View('media.photos', ['files' => $photos, 'paginate' => $paginate]);
    }

    public function store():?View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $photo = $_FILES['photos'];
        $error = PhotoRequest::validated($photo);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($error){
            $photos = DB::select("SELECT * FROM photos OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('media.photos', ['error' => $error, 'files' => $photos, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUniqueUrl($photo, 'resources/images/photos/', 'photos', 'url');
        $name = MediaService::createName($photo['name']);

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        DB::insert("INSERT INTO photos (url, name, created_at, updated_at) VALUES (?, ?, ?, ?)",
            [$url, $name, $dateNow, $dateNow]);

        move_uploaded_file($photo['tmp_name'], $url);
        header("Location: /media/photos?page={$this->paginate->getPage()}");
        exit();
    }

    public function delete()
    {
        if (!Authorization::authCheck()) header('Location: /');

        $url = StrService::stringFilter($_POST['delete']);

        if (file_exists($url)){
            $res = unlink($url);
            if ($res){
                DB::delete("DELETE FROM photos WHERE url = ?", [$url]);
            }
        }

        header("Location: /media/photos?page={$this->paginate->getPage()}");
        exit();
    }
}