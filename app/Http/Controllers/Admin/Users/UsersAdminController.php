<?php

namespace App\Http\Controllers\Admin\Users;

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
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function main():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $find = null;
        if ((isset($_GET['find']) && $_GET['find'] !== '') || (isset($_GET['select']) && $_GET['select'] !== '')){
            $find = $_GET['find'];
            $filter = new AdminUsersFilter();
            $query = $filter->usersEmail($_GET);
            $last_page = $this->paginate->lastPageForFindUsers('users', $_GET['find']);
        }else{
            $query = "SELECT * FROM users ORDER BY created_at DESC OFFSET ? LIMIT ?";
            $last_page = $this->paginate->lastPage('users');
        }

        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $result = DB::select($query, [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();


        return new View('admin.users.users', ['result' => $result, 'paginate' => $paginate, 'find' => $find, 'selectorField' => self::SELECT_FILED]);
    }

    public function edit()
    {
        if (!Authorization::authCheck()) header('Location: /');

        $result = DB::select("SELECT * FROM users WHERE id = ?", [$_GET['id']])->fetch();

        return new View('admin.users.edit', ['result' => $result]);
    }

    public function update()
    {
        if (!Authorization::authCheck()) header('Location: /');

        $result = [
            'email' => StrService::stringFilter($_POST['email']),
            'login' => StrService::stringFilter($_POST['login']),
            'role' => StrService::stringFilter($_POST['role']),
            'avatar' => StrService::stringFilter($_POST['avatar']),
            'id' => $_GET['id']
        ];

        $errors = UsersAdminRequest::validated($result);
        if (!$errors){

            $dateTime = new DateTime();
            $dateNow = $dateTime->format('Y-m-d H:i:s');
            DB::update("UPDATE users SET email = ?, login = ?, role = ?, avatar = ?, updated_at = ?
                WHERE id = ?", [$result['email'], $result['login'], $result['role'], $result['avatar'], $dateNow, $result['id']]);

            header('Location: /admin/users/edit?id=' . $_GET['id']);
            exit();
        }

        $result = DB::select("SELECT * FROM users WHERE id = ?", [$_GET['id']])->fetch();

        return new View('admin.users.edit', ['result' => $result, 'errors' => $errors]);
    }

    public function delete()
    {
        if (!Authorization::authCheck()) header('Location: /');

        $id = StrService::stringFilter($_POST['id']);

        $news = DB::select("SELECT id FROM news WHERE user_id = ?", [$id])->fetchAll();
        $news_comments = DB::select("SELECT id FROM news_comments WHERE user_id = ?", [$id])->fetchAll();
        $comments_relations =DB::select("SELECT id FROM comment_relations WHERE comment_id IN(SELECT id FROM news_comments WHERE user_id = ?)", [$id])->fetchAll();
//        DB::delete("DELETE FROM users WHERE id = ?", [$id]);

        return json_encode([$comments_relations]);
//        header('Location: /admin/users');
        // resources/images/photos/3fbeb5ea0f0d2d31973340bedf9134c3.jpeg
    }
}