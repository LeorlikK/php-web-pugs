<?php

namespace App\Http\Controllers\Admin\Peculiarities;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PeculiaritiesAdminRequest;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use Views\View;

class PeculiaritiesAdminController extends Controller
{
    const LIMIT_ITEM_PAGE = 8;

    private PaginateService $paginate;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('nurseries');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $query = "SELECT * FROM osobennosti ORDER BY id ASC LIMIT ? OFFSET ?";
        $result = DB::select($query, [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();

        return new View('admin.peculiarities.peculiarities', ['result' => $result, 'paginate' => $paginate]);
    }

    public function edit():View
    {
        setcookie('old_value', '', -60*60*24);
        $result = DB::select("SELECT * FROM osobennosti WHERE id = ?", [StrService::stringFilter($_GET['id'])])->fetch();

        return new View('admin.peculiarities.edit', ['result' => $result]);
    }

    public function update():View
    {
        $request = [
            'id' => $_GET['id'],
            'title' => StrService::stringFilter($_POST['title']),
            'text' => StrService::stringFilter($_POST['text']),
        ];

        $errors = PeculiaritiesAdminRequest::validated($request);

        if ($errors){
            return new View('admin.peculiarities.edit', ['result' => $request, 'errors' => $errors]);
        }

        setcookie('old_value', '', -60*60*24);
        DB::update("UPDATE osobennosti SET title = ?, text = ? WHERE id = ?", [$request['title'], $request['text'], $request['id']]);
        header('Location: /admin/peculiarities');
        exit();
    }
}