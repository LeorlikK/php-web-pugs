<div class="widget">
    <h3 class="widget-title">Меню</h3>
    <ul class="widget-list">
        <li id="t1"><a href="/peculiarities">Особенности породы</a></li>
        <li><a href="/nurseries">Питомники</a></li>
        <li><a href="/news">Новости</a></li>
        <li><a href="/media/photos">Медиа</a></li>
        <li><a href="forum.html">Форум</a></li>
        <?php
        use App\Http\Controllers\Auth\Authorization;?>
        <?php if (Authorization::checkAdmin()): ?>
        <li><a href="/admin">Админка</a></li>
        <?php endif ?>
    </ul>
</div>
