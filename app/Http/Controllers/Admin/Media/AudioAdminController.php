<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PhotosAdminUpdateRequest;
use App\Http\Requests\Media\AudioRequest;
use App\Http\Services\MediaService;
use App\Http\Services\MediaSizeService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use App\Http\Controllers\Render\View;

class AudioAdminController extends Controller
{
    const LIMIT_ITEM_PAGE = 14;

    private PaginateService $paginate;

    public function __construct()
    {
        if (!Authorization::authCheck()) header('Location: /');
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('audio');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $result = DB::select("SELECT * FROM audio ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();

        return new View('admin.media.audio.audio', ['result' => $result, 'paginate' => $paginate]);
    }

    public function store():View
    {
        $audio = $_FILES['audio'];
        $errors = AudioRequest::validated($audio);

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('audio');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);


        if ($errors){
            $result = DB::select("SELECT * FROM audio LIMIT ? OFFSET ?",
                [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();
            return new View('admin.media.audio.audio', ['errors' => $errors, 'result' => $result, 'paginate' => $paginate]);
        }

        $url = MediaService::generateUniqueUrl($audio, 'resources/audio/', 'audio', 'url');
        $name = MediaService::createName($audio['name']);

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');
        DB::insert("INSERT INTO audio (url, name, created_at, updated_at, size) VALUES (?, ?, ?, ?, ?)",
            [$url, $name, $dateNow, $dateNow, $audio['size']]);

        move_uploaded_file($audio['tmp_name'], $url);
        MediaSizeService::plusAudioSize($audio['size']);
        header("Location: /admin/audio?page={$this->paginate->getPage()}");
        exit();
    }

    public function edit():View
    {
        setcookie('old_value', '', -60*60*24);
        $result = DB::select("SELECT * FROM audio WHERE id = ?", [$_GET['id']])->fetch();

        return new View('admin.media.audio.edit', ['result' => $result]);
    }

    public function update():View
    {
        $request = [
            'name' => StrService::stringFilter($_POST['name']),
            'id' => $_GET['id']
        ];
        $errors = PhotosAdminUpdateRequest::validated($request);

        if (!$errors) {
            $name = MediaService::createName($request['name']);
            $dateTime = new DateTime();
            $dateNow = $dateTime->format('Y-m-d H:i:s');
            setcookie('old_value', '', -60*60*24);
            DB::update("UPDATE audio SET name = ?, updated_at = ? WHERE id = ?",
                [$name, $dateNow, $request['id']]);

            header('Location: /admin/audio/edit?id=' . $request['id']);
            exit();
        }

        $result = DB::select("SELECT * FROM audio WHERE id = ?", [$request['id']])->fetch();
        foreach ($request as $key => $value){
            $result[$key] = $value;
        }

        return new View('admin.media.audio.edit', ['result' => $result, 'errors' => $errors]);
    }

    public function delete():bool
    {
        $id = StrService::stringFilter($_POST['id']);

        $audio = DB::select("SELECT * FROM audio WHERE id = ?", [$id])->fetch();
        DB::delete("DELETE FROM audio WHERE id = ?", [$id]);
        if (file_exists($audio['url'])){
            MediaSizeService::minusAudioSize($audio['size']);
            unlink($audio['url']);
        }
        return true;
    }
}