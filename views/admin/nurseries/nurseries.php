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
            <div class="row">
                <div class="col-4">
                    <label for="peculiaritiesId">Сортировать по: </label>
                    <select class="selector-users" id="peculiaritiesId" name="select">
                        <option></option>
                        <?php foreach ($data['selectorField'] as $value):?>
                            <option <?=isset($_GET['select']) && $value === $_GET['select'] ? 'selected' : ''?> value="<?=$value?>"><?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-4" id="selectorArrowId">
                    <input hidden name="sorted" value="">
                    <button class="arrow-btn down" id="arrow-up-id"></button>
                    <button class="arrow-btn up" id="arrow-down-id"></button>
                </div>
            </div>

            <input class="login-area admin-users-input" placeholder="Поиск по email" autocomplete="off" name="find" type="text" id="login-area"
                   value="<?=isset($data['find']) ?  $data['find'] : ''?>">
            <button type="submit" class="btn btn-primary button-for-image" id="btnForLoginChangeId"> Поиск</button>
        </form>
        <table class="table admin-table">
            <thead class="table">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Image</th>
                <th scope="col">Title</th>
                <th scope="col">Text</th>
                <th scope="col">Address</th>
                <th scope="col">Phone</th>
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
                    <td><img class="admin-photos" src="/<?=$result['image']?>" alt="none"></td>
                    <td><?=\App\Http\Services\StrService::stringCut($result['title'], 15)?></td>
                    <td><?=\App\Http\Services\StrService::stringCut($result['text'], 15)?></td>
                    <td><?=\App\Http\Services\StrService::stringCut($result['address'], 15)?></td>
                    <td><?=\App\Http\Services\StrService::stringCut($result['phone'], 15)?></td>
                    <td><?=$result['created_at']?></td>
                    <td><?=$result['updated_at']?></td>
                    <td><a class="admin-btn-update" href="/admin/nurseries/edit?id=<?=$result['id']?>">Update</a></td>
                    <td><a class="admin-btn-delete" href="#" id="btn-delete_<?=$result['id']?>">Delete</a></td>
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
<script src="/resources/js/arrow.js"></script>
<script src="/resources/js/modal_window.js"></script>
</body>
</html>
