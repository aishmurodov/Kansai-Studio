<div class="row">
	<h1 class="h2 border-gray pb-2 mb-0">Посты</h1>
	<?php foreach ($posts as $post): ?>
		<div class="col-12">
			<div class="media text-muted pt-3">
				<img alt="" class="mr-2 rounded" style="width: 32px; height: 32px;" src="
			      <?php 
			      	echo $post['poster'];
			      ?>
				" data-holder-rendered="true">
				<p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray"> 
					<a href="/admin/catalog/edit/id/<?=$post['id']?>"><strong class="d-block text-gray-dark"><?= $post['title'] ?></strong></a>
					<?= $post['original_title'] ?>
					<br>
					<?php if ($post['isHidden'] == 1): ?>
						<form action="/admin/catalog/updateStatusOfPost" method="post">
							<input type="hidden" name="unset-hidden" value="<?=$post['id']?>">
							<button type="submit" class="btn btn-primary">Показать пост</button>
						</form>
					<?php elseif ($post['isHidden'] != 1): ?>
						<form action="/admin/catalog/updateStatusOfPost" method="post">
							<input type="hidden" name="set-hidden" value="<?=$post['id']?>">
							<button type="submit" class="btn btn-primary">Скрыть пост</button>
						</form>
					<?php endif ?>
				</p>
				&nbsp;
				<a href="/admin/series/id/<?=$post['id']?>" class="btn btn-primary">Управление сериями</a>
			</div>
		</div>
	<?php endforeach ?>
</div>
<div class="row">
	<div class="col-12">
		<nav>
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
				<?php endforeach; ?></ul>
		</nav>
	</div>
</div>