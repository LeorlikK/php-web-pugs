<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<?php require_once 'views/components/modal_windows/modal_window_nurseries.php'; ?>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper"  style="max-width: 1400px; margin-right: 150px; margin-left: 350px;">
    <div class="page">
        <div class="admin">
            <?php require_once 'views/components/admin_nav.php'; ?>
        </div>
        <form action="/admin/nurseries/create" method="get">
            <button type="submit" class="btn btn-primary button-for-image">Добавить запись</button>
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
                    <td><a class="admin-btn-delete" id="btn-delete_<?=$result['id']?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php require_once 'views/components/pagination/pagination.php'; ?>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script type="module" src="/resources/js/main.js"></script>
<script type="module" src="/resources/js/modal_window.js"></script>
</body>
</html>
