<div class="row">
	<div class="col-12">
		<h1 class="h2 border-gray pb-2 mb-0">
			Постеры, отображающиеся на шапке сайта
		</h1>
	</div>

	<div class="col-12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Картинка</th>
            <th scope="col">Порядковый номер</th>
            <th scope="col">Удаление</th>
          </tr>
        </thead>
        <tbody>
		<?php if (!empty($images)): ?>
			<?php $count = 1; foreach ($images as $image): ?>
                <tr>
                  <th scope="row"><?= $count ?></th>
                  <td><img src="<?= $image['url'] ?>" alt="" class="img-fluid"></td>
                  <td>
                  	<div class="form-group">
                  		<label>
                  			<form method="post" action="/admin/siteheader">
                  				<input type="number" min="1" value="<?= $image['position'] ?>" name="changeposition[<?=$image['id']?>]" class="form-control"><br>
                  				<button class="btn btn-primary">Изменить</button>
                  			</form>
                  		</label>
                  	</div>
                  </td>
                  <td>
                  	<a href="/admin/siteheader/delete/<?= $image['id']?>">Удалить</a>
                  </td>
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
					Добавить новый постер
				</h1>
			</div>
		</div>

      <table class="table table-striped">
      	<thead>
      		<tr>
      			<th scope="col">Картинка</th>
      			<th scope="col">Порядковый номер</th>
      			<th scope="col">Добавление</th>
      		</tr>
      	</thead>
      	<tbody>
      		<form method="post" action="/admin/siteheader">
				<th scope="row">
		          	<div class="form-group">
		          		<label>
	          				<input type="file" name="imageurl" class="form-control"><br>
		          		</label>
		          	</div>
				</th>
				<td>
		          	<div class="form-group">
		          		<label>
	          				<input type="number" min="1" value="<?= $image['position'] + 1 ?>" name="positionOnSite" class="form-control"><br>
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