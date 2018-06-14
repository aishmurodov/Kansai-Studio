<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php if ($paginator->
        getPrevUrl()): ?>
        <li class="page-item pagination-arrow">
            <a  class="page-link" href="<?php echo $paginator->
                getPrevUrl(); ?>">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Назад</span>
            </a>
        </li>
        <?php endif; ?>
        <?php if ($paginator->
        getNextUrl()): ?>
        <li class="page-item pagination-arrow">
            <a class="page-link"  href="<?php echo $paginator->
                getNextUrl(); ?>">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Вперёд</span>
            </a>
        </li>
        <?php endif; ?>

        <?php foreach ($paginator->
        getPages() as $page): ?>
        <?php if ($page['url']): ?>
        <li class="page-item<?php echo $page['isCurrent'] ? ' active' : ''; ?>
            ">
            <a class="page-link"  href="<?php echo $page['url']; ?>
                ">
                <?php echo $page['num']; ?></a>
        </li>
        <?php else: ?>
        <li class="disabled page-item">
            <span class="page-link">
                <?php echo $page['num']; ?></span>
        </li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</nav>
<?php if (!empty($posts)): ?>

    <?php foreach ($posts as $post): ?>
        <div class="anime-card">
            <div class="row">
                <div class="anime-card-left col-md-4">
                    <img src="<?= $post['poster'] ?>" alt="<?= $post['title'] ?>">
                </div>
                <div class="anime-card-right col-md-8">
                    <div class="anime-title">
                        <h1>
                            <a href="/catalog/item/<?= $post['url'] ?>"><?= $post['title'] ?>
                            <?php if ($post['totalseries'] > 1): ?> 
                                [<?=$post['series_count']?> из <?= $post['totalseries'] ?>]
                            <?php endif ?>
                            </a>
                        </h1>
                        <h3><?= $post['original_title'] ?></h3>
                    </div>
                    <div class="anime-votes">
                        <?
                            $break = false;
                            for ($i = 1; $i <= 10; $i++) {
                                if ($post['rating'] > $i || $post['rating'] == $i) {
                                    $star = "fas fa-star";
                                }else {
                                    if ( $i - $post['rating'] > 0 && $i - $post['rating'] < 1) {
                                       $star = "fas fa-star-half";
                                       $break = true;
                                    }else{
                                        $star = "far fa-star";
                                    }
                                }
                                echo '<span data-value="'.$i.'" title="Рейтинг: '.$post['rating'].' из 10"><i class="'.$star.'"></i></span>';
                                if ($break === true) {
                                    break;
                                }
                            }
                        ?>
                    </div>
                    <div class="anime-categories">
                        <?php foreach ($post['cats'] as $cat): ?>
                            <a href="/catalog/search/cats/<?=$cat['id']?>" class="categorie"><?=$cat['title']?></a>
                        <?php endforeach ?>
                    </div>
                    <div class="anime-more">
                        <?php if ($post['season']): ?>
                            <b>Сезон: </b> <a href="/catalog/search/season/<?=$post['season']?>"><?= returnSeason($post['season'])?></a><br>
                        <?php endif ?>
                        <?
                            $infos = json_decode($post['additional'],true);
                            foreach ($infos as $info) {
                                if (isset($info['ishidden']) && $info['ishidden']) {
                                    echo '<b>'.$info['key'].':</b> '.$info['value'].'<br>';
                                }
                            }
                        ?>
                    </div>
                    <div class="anime-about-text">
                        <?= $post['description'] ?>
                    </div>
                </div>
            </div>
        </div><br class="mainCardBr">
    <?php endforeach ?>
<?php else: ?>
    <div class="block text-center">
        <h3 class="h3">К сожалению, здесь пусто</h3>
    </div>
<?php endif ?>
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php if ($paginator->
        getPrevUrl()): ?>
        <li class="page-item pagination-arrow">
            <a  class="page-link" href="<?php echo $paginator->
                getPrevUrl(); ?>">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Назад</span>
            </a>
        </li>
        <?php endif; ?>
        <?php if ($paginator->
        getNextUrl()): ?>
        <li class="page-item pagination-arrow">
            <a class="page-link"  href="<?php echo $paginator->
                getNextUrl(); ?>">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Вперёд</span>
            </a>
        </li>
        <?php endif; ?>

        <?php foreach ($paginator->
        getPages() as $page): ?>
        <?php if ($page['url']): ?>
        <li class="page-item<?php echo $page['isCurrent'] ? ' active' : ''; ?>
            ">
            <a class="page-link"  href="<?php echo $page['url']; ?>
                ">
                <?php echo $page['num']; ?></a>
        </li>
        <?php else: ?>
        <li class="disabled page-item">
            <span class="page-link">
                <?php echo $page['num']; ?></span>
        </li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</nav>