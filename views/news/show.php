<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper">
    <a class="zvonok site1" href="/news?page=<?=\App\Http\Services\NewsService::getCookiePage()?>" style="color: whitesmoke">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"/>
        </svg>
    </a>
    <!--  Заменить  -->
    <div class="page">
        <div class="content">
            <div class="news-block">
                <div class="news">
                    <div class="news-title">
                        <a href="#"><h1 class="news-title-text"><?=$data['files']['title']?></h1></a>
                    </div>
                    <hr class="hr">
                    <div class="news-image-show">
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
    <?php require_once 'views/components/pagination/pagination-news-show.php'; ?>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>

<div class="spinner-grow load-anyway" role="status">
    <span class="visually-hidden">Loading...</span>
</div>

<script type="module" src="/resources/js/main.js"></script>
<script type="module" src="/resources/js/news.js"></script>
</body>
</html>
