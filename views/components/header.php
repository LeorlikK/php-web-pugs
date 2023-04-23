<?php
use App\Http\Controllers\Auth\Authorization;
?>

<div class="bej">
    <a class="logo no-logo " href="/">Главная</a>
    <?php
    if (Authorization::authCheck()): ?>
            <div class="menu">
                <a class="menu-btn" href="/login">
                    <?=\App\Http\Services\StrService::stringCut($_SESSION['authorize'], 8)?>
                </a>
                <div class="menu-items">
                    <a class="menu-hover" href="/office">Personal Area</a>
                    <a class="menu-hover" id="authorizationLogoutId" style="cursor: pointer">Logout</a>
                </div>
            </div>
    <?php else: ?>
        <div class="menu">
            <a class="menu-btn" href="/login">Войти</a>
        </div>
    <?php endif ?>
</div>

