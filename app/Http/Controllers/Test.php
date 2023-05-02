<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Services\MediaService;
use App\Http\Services\MediaSizeService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use App\Http\Controllers\Render\View;

class Test extends Controller
{
    public function sayTest()
    {
//        parse_url();
//        parse_str();
//        parse_ini_file();
//        parse_ini_string();

//        return new View('test', []);
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

//        $query = "ALTER TABLE users ADD COLUMN verify varchar(255) DEFAULT false;";
//        $query = "CREATE TABLE media_size(
//    id serial PRIMARY KEY,
//    name varchar(32),
//    size bigint
//)";
//
//        $query = "ALTER TABLE audio ADD COLUMN size bigint";
//        DB::connect()->query($query);

//        $getFile = file_get_contents('views/components/mail.html');
//        print $getFile;

//        $query = "CREATE INDEX comment_relation_comment_id_idx ON comment_relations (comment_id)";
//        DB::connect()->query($query);
//          test
//        die();
    }
}