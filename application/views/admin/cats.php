<div class="row">
	<h1 class="h2 border-gray pb-2 mb-0">Категории</h1>
</div><hr>
<div class="row">
	<div class="col-12">
		<form action="/admin/cats" method="post">
			<div class="form-group">
				<label>
					<h2 class="h3">Добавить категорию</h2><br>
					<input type="text" name="addTag" class="form-control" placeholder="Введите жанр/категорию">
				</label>
				<button type="submit">Добавить</button>
			</div>
		</form>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-12">
		<h2 class="h3">Существующие жанры/категории</h2>
	</div>
	<div class="col-12">
		<div class="row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Название</th>
						<th scope="col">Удалить</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($cats)): ?>
						<?php $count = 1; foreach ($cats as $cat): ?>
							<tr>
								<th scope="row"><?= $count ?></th>
								<td><?= $cat['tag'] ?></td>
								<td>
									<a href="/admin/cats/delete/<?=$cat['id']?>">Удалить</a>
								</td>
							</tr>
						<?php $count++; endforeach ?>
					<?php else: ?>
						<tr>
							<th scope="row" class="text-center" colspan="3">Пусто</th>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>