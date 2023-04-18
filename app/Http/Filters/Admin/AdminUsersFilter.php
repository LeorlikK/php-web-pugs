<?php

namespace App\Http\Filters\Admin;

use App\Http\Controllers\Admin\Users\UsersAdminController;
use App\Http\Services\StrService;

class AdminUsersFilter
{
    public function filter($query):string
    {
        $sql = "SELECT * FROM users";
        if (isset($query['find'])){
            $find = StrService::stringFilter($query['find']);
            $sql .= " WHERE email LIKE '%{$find}%'";
        }
        if ((isset($query['select'])) && ($query['select'] !== '')){
            $select = StrService::stringFilter($query['select']);
            $sorted = 'DESC';
            if (isset($query['sorted']) && $query['sorted'] === 'down') $sorted = 'ASC';
            if ($query['select'] === 'created_at' || $query['select'] === 'updated_at'){
                $sql .= " ORDER BY $select $sorted";
            }else{
                $sorted === 'DESC' ? $sorted = 'ASC': $sorted = 'DESC';
                $sql .= " ORDER BY LOWER($select) $sorted, created_at DESC";
            }
        }else{
            $sql .= " ORDER BY created_at DESC";
        }

        $sql .= "  OFFSET ? LIMIT ?";
        return $sql;
    }
}