<div class="row">
	<h1 class="h2 border-gray pb-2 mb-0">Пользователи</h1>
	<?php foreach ($users as $user): ?>
		<div class="col-12">
			<div class="media text-muted pt-3">
				<img alt="" class="mr-2 rounded" style="width: 32px; height: 32px;" src="
			      <?php 
			      	if (!$user['avatar'])
			  			echo "/public/images/non-avatar.png";
			  		else
			  			echo $user['avatar'];
			      ?>
				" data-holder-rendered="true">
				<p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray"> 
					<a href="/profile/<?=$user['login']?>"><strong class="d-block text-gray-dark"><?= $user['login'] ?></strong></a>
					<?= returnRankValue($user['ugroup']) ?>
					<br>
					<?= $user['email'] ?>
					<?php if ($user['ugroup'] == 1 && isset($_SESSION['superAdmin'])): ?>
						<form action="/admin/users" method="post">
							<input type="hidden" name="set-admin" value="<?=$user['login']?>">
							<button type="submit">Сделать администратором</button>
						</form>
					<?php elseif ($user['ugroup'] != 1 && isset($_SESSION['superAdmin'])): ?>
						<form action="/admin/users" method="post">
							<input type="hidden" name="delete-admin" value="<?=$user['login']?>">
							<button type="submit">Удалить администратора</button>
						</form>
					<?php endif ?>
				</p>
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
					<span>
						<?php echo $page['num']; ?></span>
				</li>
				<?php endif; ?>
				<?php endforeach; ?></ul>
		</nav>
	</div>
</div>