<?php

namespace App\Http\Controllers\Admin\Users;

use App\Exceptions\ErrorView;
use App\Http\Controllers\Auth\Authorization;
use App\Http\Filters\Admin\AdminUsersFilter;
use App\Http\Requests\Admin\UsersAdminRequest;
use App\Http\Services\PaginateService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class UsersAdminController
{
    const LIMIT_ITEM_PAGE = 8;
    const SELECT_FILED= [
            'email',
            'login',
            'created_at',
            'updated_at',
        ];
    private PaginateService $paginate;

    public function __construct()
    {
        if (!Authorization::authCheck()) header('Location: /');
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        $get = StrService::stringFilter($_GET ?? null);
        if ((isset($get['find']) && $get['find'] !== '') || (isset($get['select']) && $get['select'] !== '')){
            $AdminUsersFilter = new AdminUsersFilter();
            if (isset($get['find'])){
                $last_page = $this->paginate->lastPageForFindUsers('users', $get['find']);
            }else{
                $last_page = $this->paginate->lastPage('users');
            }
            $query = $AdminUsersFilter->filter($get);
        }else{
            $last_page = $this->paginate->lastPage('users');
            $query = "SELECT * FROM users ORDER BY created_at DESC OFFSET ? LIMIT ?";
        }

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $result = DB::select($query, [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();

        return new View('admin.users.users', ['result' => $result, 'paginate' => $paginate,
            'find' => $get['find'] ?? null, 'selector' => self::SELECT_FILED, 'sorted' => $get['sorted']??null]);
    }

    public function edit():View
    {
        setcookie('old_value', '', -60*60*24);
        $result = DB::select("SELECT * FROM users WHERE id = ?", [StrService::stringFilter($_GET['id'] ?? null)])->fetch();

        return new View('admin.users.edit', ['result' => $result]);
    }

    public function update():View
    {
        $request = [
            'email' => StrService::stringFilter($_POST['email']),
            'login' => StrService::stringFilter($_POST['login']),
            'role' => StrService::stringFilter($_POST['role']),
            'avatar' => StrService::stringFilter($_POST['avatar']),
            'id' => StrService::stringFilter($_GET['id']??null)
        ];
        $errors = UsersAdminRequest::validated($request);

        if (!$errors){
            $dateTime = new DateTime();
            $dateNow = $dateTime->format('Y-m-d H:i:s');
            setcookie('old_value', '', -60*60*24);
            DB::update("UPDATE users SET email = ?, login = ?, role = ?, avatar = ?, updated_at = ? WHERE id = ?",
                [$request['email'], $request['login'], $request['role'], $request['avatar'], $dateNow, $request['id']]);

            header('Location: /admin/users/edit?id=' . $request['id']);
            exit();
        }

        $result = DB::select("SELECT * FROM users WHERE id = ?", [$request['id']])->fetch();
        foreach ($request as $key => $value){
            $result[$key] = $value;
        }

        return new View('admin.users.edit', ['result' => $result, 'errors' => $errors]);
    }

    public function delete():bool
    {
        $id = StrService::stringFilter($_POST['id']);

        $user = DB::select("SELECT * FROM users WHERE id = ?", [$id])->fetch();
        if ($user['banned'] === true){
            DB::update("UPDATE users SET banned = ? WHERE id = ?", [0, $id]);
        }else{
            DB::update("UPDATE users SET banned = ? WHERE id = ?", [true, $id]);
        }
        return true;
    }
}