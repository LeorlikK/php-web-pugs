<?php

namespace App\Http\Controllers;

use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class Test extends Controller
{
    public function sayTest()
    {
//        $query = "CREATE TABLE nurseries
//    (
//        id serial PRIMARY KEY,
//        photos_id bigint,
//        title varchar(255) NOT NULL,
//        text text,
//        address varchar(255),
//        created_at timestamp,
//        updated_at timestamp,
//        FOREIGN KEY (photos_id) REFERENCES photos(id)
//    )
//    ";

//    $query = "
//    CREATE TABLE news
//    (
//      id serial PRIMARY KEY,
//      user_id bigint,
//      image VARCHAR(255),
//      title VARCHAR(255) NOT NULL,
//      short text,
//      text text,
//      created_at timestamp,
//      updated_at timestamp,
//      FOREIGN KEY (user_id) REFERENCES users(id)
//    )
//    ";

//        $query = "
//    CREATE TABLE news_comments
//    (
//      id serial PRIMARY KEY,
//      news_id bigint,
//      user_id bigint,
//      text text,
//      created_at timestamp,
//      updated_at timestamp,
//      FOREIGN KEY (news_id) REFERENCES news(id),
//      FOREIGN KEY (user_id) REFERENCES users(id)
//    )
//    ";

//        $query = "
//        ALTER TABLE users
//    ADD COLUMN avatar varchar(255)
//    ";

//                $query = "
//    CREATE TABLE comment_relations
//    (
//      id serial PRIMARY KEY,
//      comment_id bigint NOT NULL,
//      related_comment_id bigint NOT NULL,
//      FOREIGN KEY (comment_id) REFERENCES news_comments(id),
//      FOREIGN KEY (related_comment_id) REFERENCES news_comments(id)
//    )
//    ";
//        $query = "
//    CREATE TABLE comment_relations
//    (
//      id serial PRIMARY KEY,
//      comment_id bigint NOT NULL,
//      user_id bigint,
//      text text,
//      created_at timestamp,
//      updated_at timestamp,
//      FOREIGN KEY (comment_id) REFERENCES news_comments(id),
//      FOREIGN KEY (user_id) REFERENCES users(id)
//    )
//    ";


//        $query = "ALTER TABLE users ALTER COLUMN role TYPE TEXT";
////        $query = "CREATE UNIQUE INDEX photo_url_unique ON photos (url)";
//        DB::connect()->query($query);


        $url = "http://php-website/admin/users?page=2&id=1&find=y";
        $url2 = "http://php-website/admin/users?select=login&find=a";

        $str = "01 - Вьетнам.mp3";
        $name = StrService::stringFilter($str);
        $pos = mb_strripos($name, '.');
        $res = mb_substr($name, 0, $pos);
        var_dump($res, $pos);

        die();
    }
}