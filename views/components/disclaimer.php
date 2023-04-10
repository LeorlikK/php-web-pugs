<?php
use App\Http\Controllers\Auth\Authorization;
?>
<div class="page">
    <div class="content_main" id="citata">
        <h1 class="coral">Pugs, not drugs</h1>
        <p class="coral"><em>Жизнь без мопса возможна, но не имеет смысла (c) Loriot</em> </p>
    </div>
    <?php
    if (Authorization::checkRegister()): ?>
        <div id="myDanger" class="alert alert-danger" style="margin-top: -40px;" role="alert">
            <?= $_SESSION['success'] ?>
        </div>
    <?php endif; ?>
    <?php Authorization::deleteRegister() ?>
</div>
