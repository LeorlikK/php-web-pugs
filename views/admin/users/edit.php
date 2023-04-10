<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper"  style="max-width: 1400px; margin-right: 150px; margin-left: 350px;">
    <div class="page">
        <div class="admin">
            <?php require_once 'views/components/admin_nav.php'; ?>
        </div>
        <div class="row" style="margin: 50px 50px 50px 50px">
            <div class="col-3">
                <label for="peculiaritiesId">peculiarities</label>
                <select id="peculiaritiesId">
                    <option selected disabled>1</option>
                    <option>all</option>
                    <option>create</option>
                </select>
            </div>
            <div class="col-3">
                <label for="peculiaritiesId">peculiarities</label>
                <select id="peculiaritiesId">
                    <option selected disabled>1</option>
                    <option>all</option>
                    <option>create</option>
                </select>
            </div>

        </div>
        <form action="/admin/users/update?id=<?=$data['result']['id']?>" method="post">
            <table class="table admin-table">
                <thead class="table">
                <tr>
                    <th scope="col">Field</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td><?=\App\Http\Services\StrService::stringCut($data['result']['id'], 15)?></td>
                </tr>

                <tr>
                    <th scope="row">Email</th>
                    <td>
                        <input disabled class="login-area change" style="width: 100%" autocomplete="off" name="email" type="text" value="<?=$data['result']['email']?>">
                        <?php if (isset($data['errors']->error['email'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['email'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>


                <tr>
                    <th scope="row">Login</th>
                    <td>
                        <input disabled class="login-area change" style="width: 100%" autocomplete="off" name="login" type="text" value="<?=$data['result']['login']?>">
                        <?php if (isset($data['errors']->error['login'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['login'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Role</th>
                    <td>
                        <select disabled class="change" name="role">
                            <?php foreach (array_flip(\App\Http\Controllers\Auth\Authorization::ROLE) as $key => $value):?>
                                <option <?= $value === $data['result']['role'] ? 'selected': '' ?> value="<?=$value?>"><?=$value?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($data['errors']->error['role'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['role'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Avatar</th>
                    <td>
                        <input disabled class="login-area change" style="width: 100%" autocomplete="off" name="avatar" type="text" value="<?=$data['result']['avatar']?>">
                        <?php if (isset($data['errors']->error['avatar'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['avatar'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Created_at</th>
                    <td>
                        <input disabled class="login-area" style="width: 100%" autocomplete="off" name="created_at" type="text" value="<?=$data['result']['created_at']?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">Updated_at</th>
                    <td>
                        <input disabled class="login-area" style="width: 100%" autocomplete="off" name="updated_at" type="text" value="<?=$data['result']['updated_at']?>">
                    </td>
                </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary button-for-image" id="btnForLoginChangeId"> Изменить</button>
            <button disabled type="submit" class="btn btn-primary button-for-image" id="btnForLoginSaveId"> Сохранить</button>
        </form>
    </div>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script src="/resources/js/main.js"></script>
<script src="/resources/js/admin.js"></script>
</body>
</html>

