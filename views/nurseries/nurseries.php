<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper">

    <div class="page">
        <h1>Питомники мопсов</h1>
        <div class="content">
            <?php foreach ($data['nurseries'] as $nursery): ?>
                <div class="pitomniki">
                <div class="pitomniki-ava-block">
                    <div class="ava-med">
                        <div class="noava" style="background-image: url(<?=$nursery['image']?>)"></div>
                    </div>
                </div>
                <div class="forum-text-block">
                    <div class="forum-href">
                        <p class="fh"><?=$nursery['title'] ?></p>
                    </div>
                    <div class="forum-text">
                        <p>
                            <?=$nursery['text'] ?>
                        </p>
                    </div>
                </div>
                <div class="forum-text-contact bej">
                    <a class="zvonok site1" href="tel:<?=$nursery['phone'] ?>">Позвонить</a>
                    <a
                            class="zvonok site1"
                            href="<?=$nursery['address'] ?>"
                            target="_blank"
                    >Сайт</a
                    >
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php require_once 'views/components/pagination/pagination.php'; ?>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>

<script type="module" src="/resources/js/main.js"></script>
</body>
</html>
