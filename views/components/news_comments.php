<?php use App\Http\Controllers\Auth\Authorization;
use App\Http\Services\StrService;?>
<div class="comment-block">
    <?php if (Authorization::authCheck()): ?>
    <div class="content">
        <div class="d-flex flex-start">
            <img class="rounded-circle shadow-1-strong me-3" src="/<?php echo $comment['avatar'] ?? "resources/images/avatar/avatar_default.png"?>" alt="avatar" width="65" height="65">
            <div class="flex-grow-1 flex-shrink-1" id="id">
                <div>
                    <div class="input-group">
                        <textarea class="form-control" id="leave-comment-field" aria-label="With textarea" placeholder="Введите коментарий"></textarea>
                        <button disabled class="btn-load-comments leave-comment" value="<?=$data['files']['id']?>">Оставить комментарий</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php foreach ($data['comments'] as $comment): ?>
        <div class="content">
            <div class="d-flex flex-start">
                <img class="rounded-circle shadow-1-strong me-3" src="/<?php echo $comment['avatar']?>" alt="error" width="65" height="65">
                <div class="flex-grow-1 flex-shrink-1" id="<?=$comment['id']?>">
                    <div class="non-class">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-1"><?=$comment['login']?> <span class="time small time">- <?=StrService::format($comment['created_at'])?></span></p>
                            <?php if (Authorization::authCheck()): ?>
                          		<i class="fas fa-reply fa-xs"></i>
                                <?php if (Authorization::checkAdmin()): ?>
                                    <form action="/news/show/delete-comment" method="post"   style="margin-left: 70%">
                                        <span class="small closer-class delete"> удалить</span>
                                      	<input hidden disabled value="main">
                                        <input hidden disabled value="<?=$comment['id']?>">
                                    </form>
                                    /
                                <?php endif; ?>
                                <span class="small closer-class"> ответить</span>
                            <?php endif; ?>
                        </div>
                        <p class="small mb-0">
                            <?=$comment['text']?>
                        </p>
                    </div>
                    <?php if ($comment['comment_count'] > 0) :?>
                        <button class="btn-load-comments" value="<?=$comment['comment_count']?>">Показать ответы: <?=$comment['comment_count']?></button>
                    <?php endif;?>
                    <button class="btn-add-comments" hidden value="3">Другие ответы</button>
                    <div class="news-load-await">
                        <div hidden class="spinner-grow load-anyway" style="margin-left: 43%" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
