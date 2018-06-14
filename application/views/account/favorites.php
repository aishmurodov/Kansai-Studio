<div class="block">
	<?php if (empty($favorites)): ?>
		<div class="text-center">
			<h2 class="h3">Ваш список избранных пуст</h2>
		</div>
	<?php else: ?>
		<div>
			<h1 class="h2 border-gray pb-2 mb-0">Избранные</h1>
			<?php foreach ($favorites as $favorite): ?>
				<div>
					<div class="media text-muted pt-3">
						<img alt="" class="mr-2 rounded" style="width: 32px; height: 32px;" src="
					      <?php 
					      	echo $favorite['poster'];
					      ?>
						" data-holder-rendered="true">
						<p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray"> 
							<a href="/catalog/item/<?=$favorite['url']?>"><strong class="d-block text-gray-dark"><?= $favorite['title'] ?></strong></a>
							<?= $favorite['original_title'] ?><br>
							Остановился на <?= $favorite['last_user_serie'] ?> серии <br>
							<form action="/account/favorites/" method="post">
								<input type="hidden" name="delete-favorite" value="<?=$favorite['id']?>">
								<button class="btn main_bgc_color" type="submit">Удалить из избранных</button>
							</form>
						</p>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php if ($paginator->
        getPrevUrl()): ?>
        <li style="margin: 20px 0;" class="page-item pagination-arrow">
            <a  class="page-link" href="<?php echo $paginator->
                getPrevUrl(); ?>">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Назад</span>
            </a>
        </li>
        <?php endif; ?>
        <?php if ($paginator->
        getNextUrl()): ?>
        <li style="margin: 20px 0;" class="page-item pagination-arrow">
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
        <li style="margin: 20px 0;" class="page-item<?php echo $page['isCurrent'] ? ' active' : ''; ?>
            ">
            <a class="page-link"  href="<?php echo $page['url']; ?>
                ">
                <?php echo $page['num']; ?></a>
        </li>
        <?php else: ?>
        <li style="margin: 20px 0;" class="disabled page-item">
            <span class="page-link">
                <?php echo $page['num']; ?></span>
        </li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</nav>
</div>
