<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper" style="max-width: 1400px; margin-right: 150px; margin-left: 350px;">
    <div class="page">
        <div class="admin">
            <?php require_once 'views/components/admin_nav.php'; ?>
        </div>
        <div style="padding: 20px; background-color: rgba(252, 252, 252, 0.7); border-radius: 3px">
            <div class="row">
                <div class="col-6 admin-main-margin">
                    <span class="text-span">All Users: </span>
                    <span><?=$data['result']['count']?></span>
                </div>
                <div class="col-6 admin-main-margin photos-size">
                    <span class="text-span">Photos Size: </span>
                    <span><?=\App\Http\Services\MediaSizeService::translate($data['result']['image'])?></span>
                    <span>(<?=round($data['result']['image_percent'], 2)?>%)</span>
                </div>
                <div class="col-6 admin-main-margin">
                    <span class="text-span">Users banned: </span>
                    <span><?=$data['result']['banned']['count']?></span>
                </div>
                <div class="col-6 admin-main-margin video-size">
                    <span class="text-span">Video Size: </span>
                    <span><?=\App\Http\Services\MediaSizeService::translate($data['result']['video'])?></span>
                    <span>(<?=round($data['result']['video_percent'], 2)?>%)</span>
                </div>
                <div class="col-6 admin-main-margin">
                    <span class="text-span">Users in the last week: </span>
                    <span><?=$data['result']['date']['count']?></span>
                    <span><?=$data['result']['percentChange']?>%</span>
                    <?php if ($data['result']['percentChangeArrow'] === 'plus'): ?>
                        <?php require_once 'views/components/arrow_percent/plus.php'; ?>
                    <?php elseif($data['result']['percentChangeArrow'] === 'minus'):?>
                        <?php require_once 'views/components/arrow_percent/minus.php'; ?>
                    <?php endif; ?>
                </div>
                <div class="col-6 admin-main-margin audio-size">
                    <span class="text-span">Audio Size: </span>
                    <span><?=\App\Http\Services\MediaSizeService::translate($data['result']['audio'])?></span>
                    <span>(<?=round($data['result']['audio_percent'], 2)?>%)</span>
                </div>
                <div class="col-6 admin-main-margin">
                    <span class="text-span">Users last week</span>
                    <span><?=$data['result']['date']['two_week']?></span>
                </div>
                <div class="col-6 admin-main-margin">
                    <span class="text-span">All Size: </span>
                    <span><?=\App\Http\Services\MediaSizeService::translate($data['result']['sum'])?></span>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-bar__part part1" style="width: <?=$data['result']['image_percent']?>%"></div>
                <div class="progress-bar__part part2" style="width: <?=$data['result']['video_percent']?>%; left: <?=$data['result']['image_percent']?>%"></div>
                <div class="progress-bar__part part3" style="width: <?=$data['result']['audio_percent']?>%; left: <?=($data['result']['image_percent']+$data['result']['video_percent'])?>%"></div>
            </div>
        </div>
    </div>
    <?php require_once 'views/components/menu.php'; ?>
    <div class="top">
    </div>
</div>
<script type="module" src="/resources/js/main.js"></script>
</body>
</html>
