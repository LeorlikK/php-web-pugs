<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Filters\Admin\AdminUsersFilter;
use App\Http\Services\PaginateService;
use Database\DB;
use Views\View;

class AudioAdminController
{
    const LIMIT_ITEM_PAGE = 2;

    private PaginateService $paginate;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        if (!Authorization::authCheck()) header('Location: /');

//        $find = null;
//        if ((isset($_GET['find']) && $_GET['find'] !== '') || (isset($_GET['select']) && $_GET['select'] !== '')){
//            $find = $_GET['find'];
//            $filter = new AdminUsersFilter();
//            $query = $filter->usersEmail($_GET);
//            $last_page = $this->paginate->lastPageForFindUsers('users', $_GET['find']);
//        }else{
//            $query = "SELECT * FROM users ORDER BY created_at DESC OFFSET ? LIMIT ?";
//            $last_page = $this->paginate->lastPage('users');
//        }

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('photos');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $query = "SELECT * FROM audio ORDER BY created_at DESC OFFSET ? LIMIT ?";
        $result = DB::select($query, [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();


        return new View('admin.audio.audio', ['result' => $result, 'paginate' => $paginate]);
    }
}