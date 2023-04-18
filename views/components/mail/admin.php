<!doctype html>
<html lang="en">
<head>
    <title>Запрос администратору</title>
</head>
<body>
<div style="max-width: 90%; margin: 75px auto 0; position: relative">
    <div style="
        color: rgb(41, 39, 39);
        background-color: rgba(252, 252, 252, 0.7);
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
        padding: 10px;
        max-width: 1024px;
        min-height: 170px;
        margin: 0;
        top: 10px;
    ">
        <h1 style="color: lightcoral">Email: <?=$email?></h1>
        <h1 style="color: lightcoral">Login: <?=$login?></h1>
        <p><?=$message?>
        </p>
    </div>
</div>
</body>
</html>

