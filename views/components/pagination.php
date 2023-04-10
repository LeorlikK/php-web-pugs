<?php if ($data['paginate']['last_page'] > 0): ?>
    <nav aria-label="..." class="paginator-position">
        <ul class="pagination">
            <li class="page-item <?=($data['paginate']['current_page'] != 1) ?  "" : "disabled" ?>" >
                <a class="page-link" href="<?=parse_url($_SERVER['REQUEST_URI'])['path']?>?page=<?=($data['paginate']['current_page']-1).$data['paginate']['id_query'].$data['paginate']['find_query']?>" tabindex="-1">Previous</a>
            </li>
            <?php if ($data['paginate']['current_page'] != 1): ?>
                <?php for($i=$data['paginate']['current_page']-2;$i!=$data['paginate']['current_page'];$i++) :?>

                    <?php if ($i > 0): ?>
                        <li class="page-item"><a class="page-link" href="<?=parse_url($_SERVER['REQUEST_URI'])['path']?>?page=<?=$i.$data['paginate']['id_query'].$data['paginate']['find_query']?>">
                                <?=$i ?></a></li>
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif;?>
            <li class="page-item active">
                <a class="page-link" href="<?=parse_url($_SERVER['REQUEST_URI'])['path']?>?page=<?=($data['paginate']['current_page']).$id?>"><?=$data['paginate']['current_page']?> <span class="sr-only"></span></a>
            </li>
            <?php if (($data['paginate']['current_page']) < $data['paginate']['last_page']): ?>
                <?php for($i=1;$i<3;$i++) :?>
                    <li class="page-item"><a class="page-link" href="<?=parse_url($_SERVER['REQUEST_URI'])['path']?>?page=<?=($data['paginate']['current_page']+$i).$data['paginate']['id_query'].$data['paginate']['find_query']?>">
                            <?=($data['paginate']['current_page']+$i)?></a></li>
                    <?php if ($data['paginate']['current_page'] + $i == $data['paginate']['last_page']) break ?>
                <?php endfor; ?>
            <?php endif;?>
            <li class="page-item <?=($data['paginate']['current_page'] == $data['paginate']['last_page']) ?  "disabled" : "" ?>">
                <a class="page-link" href="<?=parse_url($_SERVER['REQUEST_URI'])['path']?>?page=<?=($data['paginate']['current_page']+1).$data['paginate']['id_query'].$data['paginate']['find_query']?>">Next</a>
            </li>
        </ul>
    </nav>
<?php endif ?>
