<!doctype html>
<html lang="en">
<?php
require_once 'views/components/head.php';
?>
<body>
<div class="error-div" style="">
    <h1 class="error-text">
        <?=$data['code'];?>
    </h1>
        <p style="font-size: 60px"><?=$data['message'];?></p>
</div>
</body>
</html>