<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\AudioRequest;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class CommentsController extends Controller
{
    public function __construct()
    {
        if (!Authorization::authCheck()) header('Location: /');
    }

    public function create():string
    {
        $user = DB::select("SELECT * FROM users WHERE email = ?", [$_SESSION['authorize']])->fetch();

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');


        $id = DB::insert("INSERT INTO news_comments (news_id, user_id, text, created_at, updated_at) VALUES (?,?,?,?,?)",
            [StrService::stringFilter($_POST['news_id']), $user['id'], StrService::stringFilter($_POST['text']), $dateNow, $dateNow]);

        return json_encode($id);
    }

    public function createDop():string
    {
        $user = DB::select("SELECT * FROM users WHERE email = ?", [$_SESSION['authorize']])->fetch();

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');

        $id = DB::insert("INSERT INTO comment_relations (comment_id, user_id, text, created_at, updated_at) VALUES (?,?,?,?,?)",
            [StrService::stringFilter($_POST['comment_id']), $user['id'], StrService::stringFilter($_POST['text']), $dateNow, $dateNow]);

        return json_encode($id);
    }
}