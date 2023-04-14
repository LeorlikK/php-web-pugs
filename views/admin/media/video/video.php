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
        <form action="/admin/video/create?page=<?=($data['paginate']['current_page']) ?>" id="formSubmitId" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputEmail1">Выберите видео файл</label>
                <input type="file" name="video" class="form-control" id="examplePhotos" aria-describedby="photosHelp" accept="video/*,.mp4,.web">
                <?php if (isset($data['error']->error['size'])): ?>
                    <small id="photosHelp" class="form-text coral"><?=$data['error']->error['size'] ?></small>
                <?php endif;?>
                <?php if (isset($data['error']->error['type'])): ?>
                    <small id="photosHelp" class="form-text coral"><?=$data['error']->error['type'] ?></small>
                <?php endif;?>
            </div>
            <button type="submit" class="btn btn-primary button-for-image" id="btnFormId"> Загрузить</button>
        </form>
        <table class="table admin-table">
            <thead class="table">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Url</th>
                <th scope="col">Name</th>
                <th scope="col">Video</th>
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
                    <td><?=\App\Http\Services\StrService::stringCut($result['url'], 15)?></td>
                    <td><?=\App\Http\Services\StrService::stringCut($result['name'], 20)?></td>
                    <td>
                        <video controls class="video-player img-thumbnail admin-video" >
                            <source src="/<?= $result['url'] ?>">
                        </video>
                    </td>
                    <td><?=$result['created_at']?></td>
                    <td><?=$result['updated_at']?></td>
                    <td><a class="admin-btn-update" href="/admin/video/edit?id=<?=$result['id']?>">Update</a></td>
                    <td><a class="admin-btn-delete" href="#" id="btn-delete_<?=$result['id']?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php require_once 'views/components/pagination.php'; ?>
        <?php require_once 'views/components/menu.php'; ?>
        <div class="top">
        </div>
    </div>
    <script src="/resources/js/main.js"></script>
    <script src="/resources/js/modal_window.js"></script>
</body>
</html>


