<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper">
    <!--  Заменить  -->
    <a class="back-news-button" href="/news?page=<?=\App\Http\Services\NewsService::getCookiePage()?>">Back</a>
    <div class="page">
        <div class="content">
            <div class="news-block">
                <div class="news">
                    <div class="news-title">
                        <a href="#"><h1 class="news-title-text"><?=$data['files']['title']?></h1></a>
                    </div>
                    <hr class="hr">
                    <div class="news-image">
                        <img class="news-image-image" src="/<?=$data['files']['image']?>">
                    </div>
                    <hr class="hr">
                    <div class="news-text">
                        <p class="news-text-text"><?=htmlspecialchars_decode($data['files']['text'])?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php require_once 'views/components/news_comments.php'; ?>
    <?php require_once 'views/components/pagination.php'; ?>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>

<div class="spinner-grow load-anyway" role="status">
    <span class="visually-hidden">Loading...</span>
</div>

<script src="/resources/js/main.js"></script>
<script src="/resources/js/news.js"></script>
</body>
</html>
