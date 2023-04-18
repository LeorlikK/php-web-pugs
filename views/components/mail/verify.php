<!doctype html>
<html lang="en">
<head>
    <title>Подтверждение почты</title>
</head>
<body>
<div style="max-width: 40%; margin: 75px auto 0; position: relative">
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
        <h1 style="text-align: center; color: lightcoral">Pugs Verification</h1>
        <p>Please verify that this is your email address: <?=$email?></p>
        <a style="
  text-decoration: none;
  width: 180px;
  height: 45px;
  line-height: 45px;
  font-size: 16px;
  float: left;
  cursor: pointer;
  background-color: lightcoral;
  color: #444;
  text-align: center;
" href="<?=$verify?>">Verify email address</a>
    </div>
</div>
</body>
</html>

