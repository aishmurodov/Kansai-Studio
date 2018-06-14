<div class="row">
	<div class="col-12 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Редактирование "<?=$post['title']?>"</h1>
	</div>
	<div class="col-12">
		<form action="/admin/catalog/edit/id/<?=$post['id']?>" method="post" enctype='multipart/form-data'>
			<div class="form-group">
				<label>
					<h3 class="h4">Изменить постер</h3>
					<img src="<?=$post['poster']?>" width="200" height="200" alt=""><br><br>
					<input type="file" class="form-control" name="poster" id="poster">
				</label>
			</div>
			<div class="form-group">
				<label>
					<h3 class="h4">Название на русском</h3>
					<input type="text" class="form-control" name="title" value="<?=$post['title']?>" placeholder="Название на русском" required>
				</label>
			</div>
			<div class="form-group">
				<label>
					<h3 class="h4">Оригинальное название</h3>
					<input type="text" class="form-control" name="original_title" value="<?=$post['original_title']?>" placeholder="Оригинальное название" required>
				</label>
			</div>
			<div class="form-group">
				<label>
					Сезон
					<select class="form-control" name="season">
						<option value="0" disabled="" <? if ($post['season'] == 0) echo 'selected'; ?>>Выберите сезон</option>
						<option value="1" <? if ($post['season'] == 1) echo 'selected'; ?>>Зимний</option>
						<option value="2" <? if ($post['season'] == 2) echo 'selected'; ?>>Весенний</option>
						<option value="3" <? if ($post['season'] == 3) echo 'selected'; ?>>Летний</option>
						<option value="4" <? if ($post['season'] == 4) echo 'selected'; ?>>Осенний</option>
					</select>
				</label>
			</div>
			<div class="form-group">
				<label>
					<h3 class="h4">Максимальное кол-во серий</h3>
					<input type="number" class="form-control" name="totalseries" placeholder="Максимальное кол-во серий" value="<?=$post['totalseries']?>">
					<small class="form-text text-muted">
						Если неизвестно - пропустите
					</small>
				</label>
			</div>
			<hr>

			<div class="row">
				<div class="col-12">
					<h3 class="h4">Дополнительные поля</h3>
					<small class="text-muted">Чтобы добавить доп. поле, нажмите кнопку "добавить поле", затем введите название поля, его значание и поставьте галочку, если хотите, чтобы это поле выводилось на главной странице</small>
					<div class="text-right"><span id="add_input" data-total-inputs="0" class="btn btn-primary">Добавить поле</span></div>
					<div id="newInputs" class="row">
						<?php
							$additionals = json_decode($post['additional'], true);
						
							if (!empty($additionals)) {
								foreach ($additionals as $key => $additional) {
									echo '<div class="col-4"><input type="text" name="oldNewInputs['.$key.'][key]" value="'.$additional['key'].'" class="form-control" placeholder="Название поля"></div>';
									echo '<div class="col-4"><input type="text" name="oldNewInputs['. $key .'][value]" value="'.$additional['value'].'" class="form-control" placeholder="Значение поля"></div>';
									if (isset($additional['ishidden']) && $additional['ishidden']) {
										$checked = "checked";
									}else {
										$checked = "";
									}
									echo '<div class="col-4"><div class="form-check"><label><input name="oldNewInputs['. $key .'][ishidden]" '.$checked.' class="form-check-input" type="checkbox">Выводить на главной</label></div></div>';
									echo '<div class="col-12" style="height:20px"></div>';
								}
							}
						?>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<h3 class="h4">Категории</h3>
				<div class="row">

						<?php $count = 1; foreach ($cats as $cat): ?>
							<div class="col-md-4">
								<div class="form-check">
									<label>
										<input name="tag[<?=$cat['id']?>]"
										<?
											foreach ($post['cats'] as $post_cat) {
												if ($post_cat['id'] == $cat['id']) {
													echo 'checked';
												}	
											}
										?>
										class="form-check-input" type="checkbox"><?=$cat['tag']?>
									</label>
								</div>
							</div>	
							<?
								if ($count == 3) {
									$count = 0;
									echo '<div class="col-12" style="height:20px"></div>';
								}
							?>
						<?php $count++; endforeach ?>
				</div>
			</div>

			
			<div class="form-group">
				<h3 class="h4">Описание</h3>
				<textarea name="description" style="min-height: 200px;" class="form-control" placeholder="Описание" required=""><?=$post['description']?></textarea>
			</div>

			<div class="form-group">
				<button class="btn btn-primary">Добавить тайтл</button>
			</div>
		</form>
	</div>
</div>