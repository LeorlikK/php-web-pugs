<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<?php require_once 'views/components/header.php'; ?>
<div class="wrapper">
    <form action="/registration" method="post" enctype="multipart/form-data">
        <div class="login-pole">
            <input autocomplete="off" name="login" type="text" value="<?=$data->request['login']??'' ?>" placeholder="Login">
        </div>
        <?php if (isset($data->error['login'])): ?>
            <p class="text-danger"><?=$data->error['login']?></p>
        <?php endif; ?>
        <div class="login-pole">
            <input autocomplete="off" name="email" type="email" value="<?=$data->request['email']??'' ?>" placeholder="E-mail">
        </div>
        <?php if (isset($data->error['email'])): ?>
            <p class="text-danger"><?=$data->error['email']?></p>
        <?php endif; ?>
        <div class="login-pole">
            <input autocomplete="off" name="password-first" type="password" value="<?=$data->request['password-first']??'' ?>" placeholder="Введите пароль">
        </div>
        <?php if (isset($data->error['password-first'])): ?>
            <p class="text-danger"><?=$data->error['password-first']?></p>
        <?php endif; ?>
        <div class="login-pole">
            <input autocomplete="off" name="password-second" type="password" value="<?=$data->request['password-second']??'' ?>" placeholder="Повторите пароль">
        </div>
        <?php if (isset($data->error['password-second'])): ?>
            <p class="text-danger"><?=$data->error['password-second']?></p>
        <?php endif; ?>
        <input autocomplete="off" name="avatar" type="file" value="" placeholder="Выберите изображение" accept="image/*,.png,.jpg" style="margin-left: 32%">
        <?php if (isset($data->error['avatar'])): ?>
            <p class="text-danger"><?=$data->error['avatar']?></p>
        <?php endif; ?>
        <?php if (isset($data->error['size'])): ?>
            <p class="text-danger"><?=$data->error['size']?></p>
        <?php endif; ?>
        <?php if (isset($data->error['type'])): ?>
            <p class="text-danger"><?=$data->error['type']?></p>
        <?php endif; ?>
            <?php if (isset($regState)): ?>
                <p class="text-success"><?=$regState?></p>
            <?php endif; ?>
        <input type="submit" class="lk-enter" value="Зерегистрироваться">
    </form>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script type="module" src="/resources/js/main.js"></script>
</body>
</html>

