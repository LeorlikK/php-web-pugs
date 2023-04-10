<?php
use App\Http\Controllers\Auth\Authorization;
?>

<div class="bej">
    <a class="logo no-logo " href="/">Главная</a>
    <?php
    if (Authorization::authCheck()): ?>
        <div>
            <a class="login no-login" type="button" id="authorizationLogoutId">
                <?=\App\Http\Services\StrService::stringCut($_SESSION['authorize'], 8)?>
            </a>
<!--            <div class="spinner-grow" role="status">-->
<!--                <span class="visually-hidden">Loading...</span>-->
<!--            </div>-->
            <a class="login no-login" href="/office" type="button" id="authorizationLogoutId" style="margin-top: 50px">LK</a>
        </div>
    <?php else: ?>
        <a class="login no-login" href="/login">Войти</a>
    <?php endif ?>
</div>

