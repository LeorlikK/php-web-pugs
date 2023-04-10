<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<?php require_once 'views/components/modal_window.php'; ?>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper"  style="max-width: 1400px; margin-right: 150px; margin-left: 350px;">
    <div class="page">
        <div class="admin">
            <?php require_once 'views/components/admin_nav.php'; ?>
        </div>
        <form action="/admin/users" method="get" style="margin-top: 20px">
            <div class="col-3">
                <label for="peculiaritiesId">peculiarities</label>
                <select id="peculiaritiesId" name="select">
                    <option selected disabled>email</option>
                    <option>all</option>
                    <option>create</option>
                </select>
            </div>
            <input class="login-area admin-users-input" placeholder="Поиск по email" autocomplete="off" name="find" type="text" id="login-area"
                   value="<?=isset($data['find']) ?  $data['find'] : ''?>">
            <button type="submit" class="btn btn-primary button-for-image" id="btnForLoginChangeId"> Поиск</button>
        </form>
        <table class="table admin-table">
            <thead class="table">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Email</th>
                <th scope="col">Login</th>
                <th scope="col">Role</th>
                <th scope="col">Avatar</th>
                <th scope="col">Created_at</th>
                <th scope="col">Updated_at</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['result'] as $result) :?>
                <tr id="<?=$result['id']?>">
                    <th scope="row"><?=$result['id']?></th>
                    <td><?=\App\Http\Services\StrService::stringCut($result['email'], 15)?></td>
                    <td><?=\App\Http\Services\StrService::stringCut($result['login'], 20)?></td>
                    <td><?=$result['role']?></td>
                    <td><?=\App\Http\Services\StrService::stringCut($result['avatar'], 20)?></td>
                    <td><?=$result['created_at']?></td>
                    <td><?=$result['updated_at']?></td>
                    <td><a class="admin-btn-update" href="/admin/users/edit?id=<?=$result['id']?>">Update</a></td>
                    <td><a class="admin-btn-delete" href="#" id="btn-delete">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php require_once 'views/components/pagination.php'; ?>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script src="/resources/js/main.js"></script>
<script src="/resources/js/modal_window.js"></script>
</body>
</html>
