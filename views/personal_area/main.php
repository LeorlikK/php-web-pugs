<!doctype html>
<html lang="en">
    <?php require_once 'views/components/head.php'; ?>
    <body>
    <header>
        <?php require_once 'views/components/header.php'; ?>
    </header>
    <div class="wrapper">
        <h1>Personal Area</h1>
        <div class="page">
            <div class="content content-area persona-area">
                <div>
                    <label for="login-area">Login: </label>
                        <form action="/office/login/update" id="FormChangeLoginId" method="post">
                            <input disabled class="login-area" autocomplete="off" name="login" type="text" id="login-area" value="<?=$data['user']['login']?>" style="min-width: 78%; vertical-align: top">
                            <button type="button" class="btn btn-primary button-for-image" id="btnForLoginChangeId" style="margin-top: 0"> Изменить</button>
                            <button disabled type="submit" class="btn btn-primary button-for-image" id="btnForLoginSaveId" style="margin-top: 0"> Сохранить</button>
                        </form>
                        <?php if (isset($data['errors']->error['login'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['login']?></small>
                        <?php endif; ?>
                </div>
                <div>
                    <label for="email-area">Email: </label>
                    <input disabled class="login-area" autocomplete="off" name="login" type="text" id="email-area" value="<?=$data['user']['email']?>">
                </div>
                <div>
                </div>
                <form action="/office/avatar/update" id="formSubmitId" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Фотография:</label>
                        <img class="img-area" src="/<?php echo $data['user']['avatar']?>" alt="error" width="65" height="65">
                        <input type="file" name="avatar" class="form-control" id="examplePhotos" aria-describedby="photosHelp" accept="image/*,.png,.jpg">
                        <?php if (isset($data['errors']->error['size'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['size'] ?></small>
                        <?php endif;?>
                        <?php if (isset($data['errors']->error['type'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['type'] ?></small>
                        <?php endif;?>
                    </div>
                    <div style="width: 100%">
                        <button disabled type="submit" class="btn btn-primary button-for-image" id="btnPhotosId"> Сохранить</button>
                    </div>
                </form>
                <div>
                    <label for="leave-comment-field">Обратная связь: </label>
                    <form action="/office/email/send" id="formSendId" method="post">
                        <div class="flex-grow-1 flex-shrink-1" id="id">
                            <div>
                                <div class="input-group">
                                    <textarea class="form-control persona-area" name="emailSend" id="textAreaId" aria-label="With textarea" placeholder="Оставьте отзыв" style="width: 100%"></textarea>
                                </div>
                                <button disabled type="submit" class="btn btn-primary button-for-image" id="btnRelationsId" style="margin-left: 89.6%"> Отправить</button>
                            </div>
                            <?php if (isset($data['errors']['main'])): ?>
                                <small id="photosHelp" class="form-text coral"><?=$data['errors']['main'] ?></small>
                            <?php endif;?>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <?php require_once 'views/components/menu.php'; ?>
        <div class="top">
        </div>
    </div>
    <script type="module" src="/resources/js/main.js"></script>
    <script src="/resources/js/personal_area.js"></script>

    </body>
</html>
