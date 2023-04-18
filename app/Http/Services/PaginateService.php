<?php

namespace App\Http\Services;

use Database\DB;

class PaginateService
{
    private ?int $page;
    private ?string $query;


    public function __construct($query)
    {
        $this->page = $query['page'] ?? 1;
        if (isset($query['q']))unset($query['q']);
        if (isset($query['page']))unset($query['page']);
        $this->query = http_build_query(array_filter($query));

    }

    public function getPage():int
    {
        return $this->page;
    }

    public function getId():?int
    {
//        return $this->id;
        return $_GET['id'] ?? null;
    }

    public function offset(int $limit):int
    {
        return ($this->page - 1) * $limit;
    }

    public function lastPage(string $table, string $field='id'):int
    {
        return DB::select("SELECT COUNT($field) FROM $table")->fetch()['count'];
    }

    public function lastPageForNews(string $table, string $field='id'):int
    {
        return DB::select("SELECT COUNT($field) FROM $table WHERE publish = true")->fetch()['count'];
    }

    public function lastPageForComments(string $table, $relations_id, string $field='id'):int
    {
        return DB::select("SELECT COUNT($field) FROM $table WHERE news_id = ?", [$relations_id])->fetch()['count'];
    }

    public function lastPageForFindUsers(string $table, string $like, $field='id'):int
    {
        return DB::select("SELECT COUNT($field) FROM $table WHERE email LIKE ?", ["%$like%"])->fetch()['count'];
    }

    public function arrayPaginate($limit, $last_page):array
    {
        return [
            'current_page' => $this->page,
            'last_page' => (int)ceil($last_page / $limit),
            'page' => $this->page,
            'query' => '&' . $this->query,
        ];
    }
}