<div class="row">
	<div class="col-12 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Добавление нового тайтла</h1>
	</div>
	<div class="col-12">
		<form action="/admin/catalog/add" method="post" enctype='multipart/form-data'>
			<div class="form-group">
				<label>
					<h3 class="h4">Выберите постер</h3>
					<input type="file" class="form-control" name="poster" id="poster" required>
				</label>
			</div>
			<div class="form-group">
				<label>
					<h3 class="h4">Название на русском</h3>
					<input type="text" class="form-control" name="title" placeholder="Название на русском" required>
				</label>
			</div>
			<div class="form-group">
				<label>
					<h3 class="h4">Оригинальное название</h3>
					<input type="text" class="form-control" name="original_title" placeholder="Оригинальное название" required>
				</label>
			</div>
			<div class="form-group">
				<label>
					Сезон
					<select class="form-control" name="season">
						<option value="0" disabled="" selected>Выберите сезон</option>
						<option value="1">Зимний</option>
						<option value="2">Весенний</option>
						<option value="3">Летний</option>
						<option value="4">Осенний</option>
					</select>
				</label>
			</div>
			<div class="form-group">
				<label>
					<h3 class="h4">Максимальное кол-во серий</h3>
					<input type="number" class="form-control" name="totalseries" placeholder="Максимальное кол-во серий">
					<small class="form-text text-muted">
						Если неизвестно - пропустите. Если фильм - поставьте 1
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
										<input name="tag[<?=$cat['id']?>]" class="form-check-input" type="checkbox"><?=$cat['tag']?>
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
				<textarea name="description" class="form-control" placeholder="Описание" required=""></textarea>
			</div>

			<div class="form-group">
				<button class="btn btn-primary">Добавить тайтл</button>
			</div>
		</form>
	</div>
</div>