<form action="/admin/torrents/id/<?=$post['id']?>" method="post">
	<div class="row">
		<div class="col-12">&nbsp;</div>

		<div class="col-12">
			<h1 class="h2 border-gray pb-2 mb-0">
				Торренты поста "<?=$post['title']?>"
			</h1>
		</div>

		<div class="col-12">&nbsp;</div>
		
		<div class="col-12">
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Разрешение</th>
						<th scope="col">Ссылка на файл</th>
					</tr>
				</thead>
				<tbody id="torrentContainer">
					<?php $count = 1; if (!empty($post['torrent'])): ?>
						<?php foreach ($post['torrent'] as $resolution => $url): ?>
							<tr>
				      			<th scope="row">
				      				<?= $count ?>
				      			</th>
				      			<th scope="row">
						          	<div class="form-group">
						          		<label>
					          				<input type="text" name="resolution[<?=$count?>]" value="<?= $resolution ?>" placeholder="Ссылка на фрейм или видео" class="form-control"><br>
						          		</label>
						          	</div>
				      			</th>
				      			<th scope="row">
						          	<div class="form-group">
				          				<input type="text" name="url[<?=$count?>]" value="<?= $url ?>" placeholder="Ссылка на фрейм или видео" class="form-control"><br>
						          	</div>
				      			</th>
							</tr>
						<?php $count++; endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-12 text-right">
			<span class="btn btn-primary" id="buttonTorrentContainer" data-all-count="<?=$count?>">
				Добавить поле
			</span>
			&nbsp;
			<button type="submit" class="btn btn-primary">
				Сохранить
			</button>
		</div>
	</div>
</form>