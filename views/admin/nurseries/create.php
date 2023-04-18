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

        <form action="/admin/nurseries/store" id="formSubmitId" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputEmail1">Выберите изображение</label>
                <input type="file" name="image" class="form-control change" id="examplePhotos" aria-describedby="photosHelp"
                       accept="image/*,.png,.jpg" value="<?=$data['tmpRoute']?? ''?>" onchange="preview()">
                <?php if (isset($data['errorsFile']->error['size'])): ?>
                    <small id="photosHelp" class="form-text coral"><?=$data['errorsFile']->error['size'] ?></small>
                <?php endif;?>
                <?php if (isset($data['errorsFile']->error['type'])): ?>
                    <small id="photosHelp" class="form-text coral"><?=$data['errorsFile']->error['type'] ?></small>
                <?php endif;?>
            </div>
            <table class="table admin-table">
                <thead class="table">
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Title</th>
                    <td>
                        <input class="login-area change" style="width: 100%" autocomplete="off" name="title" type="text" placeholder="Title..." value="<?=$data['result']['title']??''?>">
                        <?php if (isset($data['errors']->error['title'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['title'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Text</th>
                    <td>
                        <div>
                            <div class="input-group">
                                <textarea class="form-control change" id="titleInputId" name="text" aria-label="With textarea" placeholder="Text..."><?=$data['result']['text']??''?></textarea>
                            </div>
                        </div>
                        <?php if (isset($data['errors']->error['text'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['text'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Address</th>
                    <td>
                        <input class="login-area change" style="width: 100%" autocomplete="off" name="address" type="text" placeholder="Address..." value="<?=$data['result']['address']??''?>">
                        <?php if (isset($data['errors']->error['address'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['address'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Phone</th>
                    <td>
                        <input class="login-area change" style="width: 100%" autocomplete="off" name="phone" type="text" placeholder="Phone..." value="<?=$data['result']['phone']??''?>">
                        <?php if (isset($data['errors']->error['phone'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['phone'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Image</th>
                    <td>
                        <input hidden name="default_image" id="imageEditWhenLoadIsNotId" value="<?=$data['result']['image']??''?>">
                        <img class="admin-photos-edit" src="" alt="none" id="imgPreviewId">
                    </td>
                </tr>

                </tbody>
            </table>
            <button type="submit" class="btn btn-primary button-for-image" id="btnForSaveId" style="margin-top: 0"> Сохранить</button>
        </form>
    </div>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script src="/resources/js/main.js"></script>
<script src="/resources/js/admin_save.js"></script>
<!--<script src="/resources/js/admin_cancel_save.js"></script>-->
<script src="/resources/js/admin_change_image.js"></script>
</body>
</html>
