<!doctype html>
<html lang="en">
<?php require_once 'views/components/head.php'; ?>
<body>
<header>
    <?php require_once 'views/components/header.php'; ?>
</header>
<div class="wrapper"  style="max-width: 1400px; margin-right: 150px; margin-left: 350px;">
    <div class="page">
        <div class="admin">
            <?php require_once 'views/components/admin_nav.php'; ?>
        </div>

        <div class="content">
            <form action="/admin/news/update?id=<?=$data['result']['id']?>" id="formSubmitId" method="post" enctype="multipart/form-data">
                <div class="form-group admin-news-input">
                    <label for="exampleInputEmail1">Выберите изображение</label>
                    <input type="file" name="image" class="form-control" id="examplePhotos" aria-describedby="photosHelp"
                           accept="image/*,.png,.jpg" value="<?=$data['tmpRoute']?? ''?>" onchange="preview()">
                    <?php if (isset($data['errorsFile']->error['size'])): ?>
                        <small id="photosHelp" class="form-text coral"><?=$data['errorsFile']->error['size'] ?></small>
                    <?php endif;?>
                    <?php if (isset($data['errorsFile']->error['type'])): ?>
                        <small id="photosHelp" class="form-text coral"><?=$data['errorsFile']->error['type'] ?></small>
                    <?php endif;?>
                </div>

                <div class="admin-news-input">
                    <div class="input-group">
                        <textarea class="admin-input-form" id="titleInputId" name="title" aria-label="With textarea" placeholder="Title..."><?=$data['result']['title']??''?></textarea>
                    </div>
                </div>
                <div class="admin-news-input">
                    <div class="input-group">
                        <textarea class="admin-input-form" id="shortInputId" name="short" aria-label="With textarea" placeholder="Short..."><?=$data['result']['short']??''?></textarea>
                    </div>
                </div>
                <div class="admin-news-input">
                    <div class="admin-input-form">
                        <label for="checkboxPublishId">Публикация</label>
                        <input type="checkbox" name="publish" <?=(isset($data['result']['publish']) && $data['result']['publish'])?'checked':''?> id="checkboxPublishId">
                    </div>
                </div>
                <div class="news-block">
                    <div class="news">
                        <div class="news-title">
                            <a href="#"><h1 class="news-title-text" id="inputTitleFakeId"><?=$data['result']['title']??''?></h1></a>
                        </div>
                        <?php if (isset($data['errors']->error['title'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['title'] ?></small>
                        <?php endif;?>
                        <hr class="hr">
                        <div class="news-image-show">
                            <input hidden name="default_image" id="imageEditWhenLoadIsNotId" value="<?=$data['result']['image']??''?>">
                            <img class="news-image-image" src="#" alt="none" id="imgPreviewId">
                        </div>
                        <hr class="hr">
                        <div class="news-text">
                            <p class="news-text-text" id="inputShortFakeId"><?=$data['result']['short']??''?></p>
                        </div>
                        <?php if (isset($data['errors']->error['short'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['short'] ?></small>
                        <?php endif;?>
                        <hr class="hr">
                        <div class="news-text">
                            <p class="news-text-text"></p>
                        </div>
                        <!--Summer                        -->
                        <input id="innerTextId" hidden value="<?=$data['result']['text']??''?>">
                        <div class="form-group" id="summernoteFormId">
                            <label for="summernote">Text</label>
                            <textarea class="form-control h-200" name="text" id="summernote" placeholder="Text"></textarea>
                        </div>
                        <?php if (isset($data['errors']->error['text'])): ?>
                            <small id="photosHelp" class="form-text coral"><?=$data['errors']->error['text'] ?></small>
                        <?php endif;?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary button-for-image" id="btnForSaveId" style="margin-top: 0"> Сохранить</button>
            </form>
        </div>
        <?php require_once 'views/components/menu.php'; ?>
        <div class="top">
        </div>
    </div>
    <script src="/resources/js/jquery/jquery.min.js"></script>
    <script src="/resources/js/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/resources/js/select2/js/select2.full.min.js"></script>
    <script src="/resources/js/summernote/summernote-bs4.min.js"></script>
    <script src="/resources/js/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ]
            });
        });
    </script>
    <script>
        $(function () {
            bsCustomFileInput.init();
        });
        $('.select2').select2()
    </script>
    <script type="module" src="/resources/js/main.js"></script>
    <script type="module" src="/resources/js/admin/save.js"></script>
    <script type="module" src="/resources/js/admin/news_create.js"></script>
    <script src="/resources/js/admin/change_image.js"></script>
</body>
</html>

