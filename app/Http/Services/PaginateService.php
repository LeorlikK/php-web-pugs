<?php

namespace App\Http\Services;

use Database\DB;

class PaginateService
{
    private int $page;
    private ?int $id;
    private ?string $query;

    public function __construct(int $page, ?int $id=null, ?string $query=null)
    {
        $this->page = $page;
        $this->id = $id;
        $this->query = $query;
    }

    public function getPage():int
    {
        return $this->page;
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function offset(int $limit):int
    {
        return ($this->page - 1) * $limit;
    }

    public function lastPage(string $table, string $field='id'):int
    {
        return DB::select("SELECT COUNT($field) FROM $table")->fetch()['count'];
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
        isset($this->query) ? $findQuery = '&find=' . $this->query : $findQuery = '';
        if ($this->id !== null){
            $idQuery = '&id=' . (string)$this->id;
        }else{
            $idQuery = '';
        }
        return [
            'current_page' => $this->page,
            'last_page' => (int)ceil($last_page / $limit),
            'id' => (int)$this->id,
            'id_query' => $idQuery,
            'find_query' => $findQuery
        ];
    }
}