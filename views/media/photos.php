<!doctype html>
<html lang="en">
<?php use App\Http\Controllers\Auth\Authorization;

require_once 'views/components/head.php'; ?>
<body>
<header>
    <div class="shapka">
        <?php require_once 'views/components/header.php'; ?>
        <?php require_once 'views/components/media_nav.php'; ?>
    </div>
</header>
<div class="wrapper">
    <h1>Photo</h1>
    <?php if (Authorization::checkAdmin()): ?>
        <form action="/media/photos/save?page=<?=($data['paginate']['current_page']) ?>" id="formSubmitId" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputEmail1">Выберите изображение</label>
                <input type="file" name="photos" class="form-control" id="examplePhotos" aria-describedby="photosHelp" accept="image/*,.png,.jpg">
                <?php if (isset($data['error']->error['size'])): ?>
                    <small id="photosHelp" class="form-text coral"><?=$data['error']->error['size'] ?></small>
                <?php endif;?>
                <?php if (isset($data['error']->error['type'])): ?>
                    <small id="photosHelp" class="form-text coral"><?=$data['error']->error['type'] ?></small>
                <?php endif;?>
            </div>
            <button type="submit" class="btn btn-primary button-for-image" id="btnFormId"> Загрузить</button>
        </form>
    <?php endif; ?>
    <div class="big-image">
    </div>

    <div class="container mt-1">
        <div class="row mb-5" >
            <?php
            $buttonId = 1;
            foreach ($data['files'] as $file): ?>
                <div class="col-3 mt-4" id="photoContainerId">
                    <img src="/<?= $file['url'] ?>" alt="..." class="img-thumbnail photo-small">
                    <div>
                        <p> <?=\App\Http\Services\StrService::stringCut($file['name'], 6)?>
                        </p>
                        <?php if (Authorization::checkAdmin()): ?>
                        <form action="/media/photos/delete?page=<?=($data['paginate']['current_page']) ?>" id="FormId<?=$buttonId?>" method="post">
                            <input hidden name="delete" value="<?= $file['url'] ?>">
                            <button type="submit" class="btn btn-primary button-for-image"> Удалить</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php $buttonId++;?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php require_once 'views/components/pagination.php'; ?>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script src="/resources/js/main.js"></script>
<script src="/resources/js/media.js"></script>
</body>
</html>
