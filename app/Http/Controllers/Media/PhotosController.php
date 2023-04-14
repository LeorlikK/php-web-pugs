<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
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
        if (!Authorization::authCheck()) header('Location: /');

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $photos = DB::select("SELECT * FROM photos OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();

        return new View('media.photos', ['files' => $photos, 'paginate' => $paginate]);
    }

    public function create():?View
    {
        $photo = $_FILES['photos'];
        $error = PhotoRequest::validated($photo);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($error){
            $photos = DB::select("SELECT * FROM photos OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
            return new View('media.photos', ['error' => $error, 'files' => $photos, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUrl($photo, 'resources/images/photos/', 'photos', 'url');
        $name = MediaService::createName($photo);

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
        $url = $_POST['delete'];

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