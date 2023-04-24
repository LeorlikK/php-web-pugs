<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <div class="shapka">
        <?php require_once 'views/components/header.php'; ?>
        <?php require_once 'views/components/peculiarities_nav.php'; ?>
    </div>
</header>
<div class="wrapper">
    <div class="page">
        <h1><?=($data['result']['title'])?></h1>
        <div class="content">
                <?=htmlspecialchars_decode($data['result']['text'])?>
        </div>
    </div>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script type="module" src="/resources/js/main.js"></script>
</body>
</html>