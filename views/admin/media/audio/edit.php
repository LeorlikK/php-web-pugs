<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <div class="shapka">
        <?php require_once 'views/components/header.php'; ?>
        <?php require_once 'views/components/media_admin_nav.php'; ?>
    </div>
</header>
<div class="wrapper"  style="max-width: 1400px; margin-right: 150px; margin-left: 350px;">
    <div class="page">
        <div class="admin">
            <?php require_once 'views/components/admin_nav.php'; ?>
        </div>

        <form action="/admin/audio/update?id=<?=$data['result']['id']?>" id="formAllSaveId" method="post">
            <table class="table admin-table">
                <thead class="table">
                </thead>
                <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td><?=\App\Http\Services\StrService::stringCut($data['result']['id'], 15)?></td>
                </tr>

                <tr>
                    <th scope="row">Url</th>
                    <td>
                        <input disabled class="login-area" style="width: 100%" autocomplete="off" name="url" type="text" value="<?=$data['result']['url']?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">Name</th>
                    <td>
                        <input disabled class="login-area change" style="width: 100%" autocomplete="off" name="name" type="text" value="<?=$data['result']['name']?>">
                        <?php if (isset($data['errors']->error['name'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['name'] ?></small>
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
                <tr>
                    <th scope="row">Аудио</th>
                    <td>
                        <audio controls src="/<?= $data['result']['url'] ?>"></audio>
                    </td>
                </tr>

                </tbody>
            </table>
            <button type="button" class="btn btn-primary button-for-image" id="btnForLoginChangeId"> Изменить</button>
            <button disabled type="submit" class="btn btn-primary button-for-image" id="btnForAllSaveId"> Сохранить</button>
        </form>
    </div>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script type="module" src="/resources/js/main.js"></script>
<script type="module" src="/resources/js/admin/cancel-save.js"></script>
</body>
</html>


