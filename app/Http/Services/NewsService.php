<?php

namespace App\Http\Services;

use Database\DB;

class NewsService
{
    public static function getDopComments($id):array
    {
        return DB::select("
            SELECT comment_relations.*, users.login, users.avatar FROM comment_relations
            JOIN users ON comment_relations.user_id = users.id 
            WHERE comment_relations.comment_id = ?
            ORDER BY comment_relations.created_at
            OFFSET 0 LIMIT 3 
            ", [$id])->fetchAll();
    }

    public static function saveCookiePage(int $page):void
    {
        setcookie('news_page', $page, 0,  path:'/news');
    }

    public static function getCookiePage():string
    {
        return $_COOKIE['news_page'] ?? 1;
    }
}