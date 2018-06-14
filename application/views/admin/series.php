<div class="row">
	<div class="col-12">&nbsp;</div>

	<div class="col-12">
		<h1 class="h2 border-gray pb-2 mb-0">
			Серии поста "<?=$post['title']?>" [<?= count($series)?> из <?= $post['totalseries'] ?>]
		</h1>
	</div>

	<div class="col-12">&nbsp;</div>

	<div class="col-12">
      <table class="table table-striped">
        <thead>
          <tr>
  			<th scope="col">#</th>
  			<th scope="col">Ссылка</th>
  			<th scope="col">Порядковый номер</th>
  			<th scope="col">Прямая ссылка</th>
  			<th scope="col">Функции</th>
          </tr>
        </thead>
        <tbody>
		<?php if (!empty($series)): ?>
			<?php $count = 1; foreach ($series as $serie): ?>
				<tr>
		      		<form method="post" action="/admin/series/id/update_serie">
		      			<input type="hidden" name="postid" value="<?= $post['id'] ?>">
		      			<input type="hidden" name="serieid" value="<?= $serie['id'] ?>">
		      			<th scope="row">
		      				<?= $count ?>
		      			</th>
						<th scope="row">
				          	<div class="form-group">
				          		<label>
			          				<input type="text" name="urlToVideo" value="<?= $serie['url'] ?>" placeholder="Ссылка на фрейм или видео" class="form-control"><br>
				          		</label>
				          	</div>
						</th>
						<td>
				          	<div class="form-group">
				          		<label>
			          				<input type="number" min="0" name="number" value="<?= $serie['number'] ?>" class="form-control"><br>
				          		</label>
				          	</div>
						</td>
						<td>
				          	<div class="form-group">
				          		<label>
				          			<?
				          				if ( $serie['isframe'] == 0 ) 
				          					$checked = "checked";
				          				else 
				          					$checked = "";
				          			?>
			          				<input type="checkbox" <?= $checked ?> name="isFrame">
			          				Прямая ссылка на видео
				          		</label>
				          	</div>
						</td>
						<td>
				          	<div class="form-group">
								<button type="submit" class="btn btn-primary">Обновить</button>&nbsp;

								<a onclick="if (!confirm('Вы уверены, что хотите удалить эту серию?')) return false;" href="/admin/series/delete/<?=$serie['id']?>" class="btn btn-primary">Удалить</a>
				          	</div>
						</td>
					</form>
				</tr>
			<?php $count++; endforeach ?>
		<?php endif ?>
        </tbody>
      </table>
	</div>

	<div class="col-12">&nbsp;</div>

	<div class="col-12">
		<div class="row">
			<div class="col-12">
				<h1 class="h2 border-gray pb-2 mb-0">
					Добавить новую серию
				</h1>
			</div>
		</div>

      <table class="table table-striped">
      	<thead>
      		<tr>
      			<th scope="col">Ссылка</th>
      			<th scope="col">Порядковый номер</th>
      			<th scope="col">Прямая ссылка</th>
      			<th scope="col">Добавить</th>
      		</tr>
      	</thead>
      	<tbody>
      		<form method="post" action="/admin/series/id/add_serie">
				<th scope="row">
					<input type="hidden" name="postid" value="<?= $post['id'] ?>">
		          	<div class="form-group">
		          		<label>
	          				<input type="text" name="urlToVideo" placeholder="Ссылка на фрейм или видео" class="form-control"><br>
		          		</label>
		          	</div>
				</th>
				<td>
		          	<div class="form-group">
		          		<label>
	          				<input type="number" min="0" name="number" readonly="" value="<?= @end($series)['number'] + 1 ?>" class="form-control"><br>
		          		</label>
		          	</div>
				</td>
				<td>
		          	<div class="form-group">
		          		<label>
	          				<input type="checkbox" name="isFrame">
	          				Прямая ссылка на видео
		          		</label>
		          	</div>
				</td>
				<td>
		          	<div class="form-group">
						<button type="submit" class="btn btn-primary">Добавить</button>
		          	</div>
				</td>
			</form>
      	</tbody>
      </table>

	</div>

</div>