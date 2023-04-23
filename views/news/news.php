<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper">
    <div class="page">
        <div class="news-content">
            <div class="news-block">
                <?php foreach ($data['files'] as $news): ?>
                    <div class="news">
                        <div class="news-title">
                            <a href="/news/show?&id=<?=$news['id']?>"><h1 class="news-title-text"><?=$news['title']?></h1></a>
                        </div>
                        <hr class="hr">
                        <div class="news-image">
                            <img class="news-image-image" src="/<?=$news['image']?>">
                        </div>
                        <hr class="hr">
                        <div class="news-text">
                            <p class="news-text-text"><?=$news['short']?></p>
                        </div>
                        <hr class="hr">
                        <div class="news-footer">
                            <span><?=$news['created_at']?></span>
                            <span class="news-footer-comments">Комментарии: <?=$news['count']?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php require_once 'views/components/pagination/pagination-news.php'; ?>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script type="module" src="/resources/js/main.js"></script>
<script src="/resources/js/news.js"></script>
</body>
</html>
