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

        <form action="/admin/nurseries/update?id=<?=$data['result']['id']?>" id="formAllSaveId" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputEmail1">Выберите изображение</label>
                <input type="file" name="image" class="form-control" id="examplePhotos" aria-describedby="photosHelp"
                       accept="image/*,.png,.jpg" value="<?=$data['tmpRoute']?? ''?>" onchange="preview()">
                <?php if (isset($data['error']->error['size'])): ?>
                    <small id="photosHelp" class="form-text coral"><?=$data['error']->error['size'] ?></small>
                <?php endif;?>
                <?php if (isset($data['error']->error['type'])): ?>
                    <small id="photosHelp" class="form-text coral"><?=$data['error']->error['type'] ?></small>
                <?php endif;?>
            </div>
            <table class="table admin-table">
                <thead class="table">
                </thead>
                <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td><?=$data['result']['id']?></td>
                </tr>

                <tr>
                    <th scope="row">Title</th>
                    <td>
                        <input disabled class="login-area change" style="width: 100%" autocomplete="off" name="title" type="text" value="<?=$data['result']['title']?>">
                        <?php if (isset($data['errorPost']->error['title'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errorPost']->error['title'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Text</th>
                    <td>
                        <div>
                            <div class="input-group">
                                <textarea disabled class="form-control change" id="titleInputId" name="text" aria-label="With textarea" placeholder="Title..."><?=$data['result']['text']??''?></textarea>
                            </div>
                        </div>
                        <?php if (isset($data['errorPost']->error['text'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errorPost']->error['text'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Address</th>
                    <td>
                        <input disabled class="login-area change" style="width: 100%" autocomplete="off" name="address" type="text" value="<?=$data['result']['address']?>">
                        <?php if (isset($data['errorPost']->error['address'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errorPost']->error['address'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Phone</th>
                    <td>
                        <input disabled class="login-area change" style="width: 100%" autocomplete="off" name="phone" type="text" value="<?=$data['result']['phone']?>">
                        <?php if (isset($data['errorPost']->error['phone'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errorPost']->error['phone'] ?></small>
                        <?php endif;?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Created_at</th>
                    <td>
                        <input disabled hidden class="login-area change" style="width: 100%" autocomplete="off" name="created_at" type="text" value="<?=$data['result']['created_at']?>">
                        <input disabled class="login-area" style="width: 100%" autocomplete="off" name="created_at" type="text" value="<?=$data['result']['created_at']?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">Updated_at</th>
                    <td>
                        <input disabled hidden class="login-area change" style="width: 100%" autocomplete="off" name="updated_at" type="text" value="<?=$data['result']['updated_at']?>">
                        <input disabled class="login-area" style="width: 100%" autocomplete="off" name="updated_at" type="text" value="<?=$data['result']['updated_at']?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Image</th>
                    <td>
                        <input hidden name="default_image" id="imageEditWhenLoadIsNotId" value="<?=$data['result']['image']??''?>">
                        <img class="admin-photos-edit" src="#" alt="none" id="imgPreviewId">
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
<script src="/resources/js/main.js"></script>
<script src="/resources/js/admin_cancel_save.js"></script>
<script src="/resources/js/admin_change_image.js"></script>
</body>
</html>



