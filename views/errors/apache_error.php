<!doctype html>
<html lang="en">
<?php
  require_once '../components/head.php';
?>
<body>
<div class="error-div">
    <?php
  	$code = $_GET['error'];
    match ($code) {
        '404' => $message = 'Not Found',
        '403' => $message = 'Forbidden',
        '401' => $message = 'Unauthorized',
        '400' => $message = 'Bad Request',
        '500' => $message = 'Internal Server Error',
        '503' => $message = 'Service Unavailable',
        default => $message = 'Unknown Error ',
    };
    ?>
    <h1 class="error-text">
        <?=$code?>
        <?=$message?>
    </h1>
</div>
</body>
</html>