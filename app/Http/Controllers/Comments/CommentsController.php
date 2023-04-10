<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Requests\Media\AudioRequest;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Services\MediaService;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class CommentsController
{
    public function create():string
    {
        $user = DB::select("SELECT * FROM users WHERE email = ?", [$_SESSION['authorize']])->fetch();

        $dateTime = new DateTime();
        $dateNow = $dateTime->format('Y-m-d H:i:s');


        $id = DB::insert("INSERT INTO news_comments (news_id, user_id, text, created_at, updated_at) VALUES (?,?,?,?,?)",
            [StrService::stringFilter($_POST['news_id']), $user['id'], StrService::stringFilter($_POST['text']), $dateNow, $dateNow]);

//        $comment = DB::select("SELECT * FROM news_comments WHERE id = ?", [$id])->fetch();
////        $requestBody = file_get_contents('php://input');
////        $data = json_decode($requestBody);
//        $response = [
//            'login' => $user['login'],
//            'avatar' => $user['avatar'],
//            'id' => $comment['id'],
//            'created_at' => StrService::format($comment['created_at']),
//            'text' => $comment['text'],
//            'comment_count' => 0,
//        ];
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