<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper admin-wrapper">
    <div class="page">
        <div class="admin">
            <?php require_once 'views/components/admin_nav.php'; ?>
        </div>

        <table class="table admin-table">
            <thead class="table">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Text</th>
                <th scope="col">Created_at</th>
                <th scope="col">Updated_at</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['result'] as $result) :?>
            <tr>
                <th scope="row"><?=$result['id']?></th>
                <td><?=\App\Http\Services\StrService::stringCut($result['title'], 15)?></td>
                <td><?=\App\Http\Services\StrService::stringCut($result['text'], 20)?></td>
                <td><?=$result['created_at']?></td>
                <td><?=$result['updated_at']?></td>
                <td><a class="admin-btn-update" href="/admin/peculiarities/edit?id=<?=$result['id']?>">Update</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script src="/resources/js/main.js"></script>
</body>
</html>
