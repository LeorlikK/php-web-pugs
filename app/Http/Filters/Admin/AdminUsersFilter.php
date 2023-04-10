<?php

namespace App\Http\Filters\Admin;

class AdminUsersFilter
{
    public function usersEmail($query)
    {
        if ($query['find']){
            $query = "SELECT * FROM users WHERE email LIKE '%{$query['find']}%' OFFSET ? LIMIT ?";
        }

        return $query;
    }
}