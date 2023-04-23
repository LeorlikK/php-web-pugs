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

        <form action="/admin/peculiarities/update?id=<?=$data['result']['id']?>" id="formAllSaveId" method="post" enctype="multipart/form-data">

            <table class="table admin-table">
                <thead class="table">
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Title</th>
                    <td>
                        <input disabled class="login-area change" style="width: 100%" autocomplete="off" name="title" type="text" value="<?=$data['result']['title']??''?>">
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
                                <textarea disabled class="form-control change" id="titleInputId" name="text" aria-label="With textarea" placeholder="Title..."><?=$data['result']['text']??''?></textarea>
                            </div>
                        </div>
                        <?php if (isset($data['errors']->error['text'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['text'] ?></small>
                        <?php endif;?>
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
<script src="/resources/js/admin/cancel-save.js"></script>
</body>
</html>
