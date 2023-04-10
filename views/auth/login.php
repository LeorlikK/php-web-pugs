<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<?php require_once 'views/components/header.php'; ?>
<div class="wrapper">
    <form action="/login" method="post">
        <div class="login-pole">
            <input autocomplete="off" name="email" type="email" value="<?=$data['request']['email']?? ''?>" placeholder="E-mail">
            <?php if (isset($data['error']['email'])): ?>
                <p class="text-danger"><?=$data['error']['email']?></p>
            <?php endif; ?>
        </div>
        <div class="login-pole">
            <input autocomplete="off" name="password" type="password" value placeholder="Введите пароль">
            <?php if (isset($data['error']['password'])): ?>
                <p class="text-danger"><?=$data['error']['password']?></p>
            <?php endif; ?>
        </div>

        <input type="submit" class="lk-enter" value="Войти">
        <a href="/registration" class="lk-enter">Зерегистрироваться</a>
    </form>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
</body>
</html>

