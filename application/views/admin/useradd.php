<div class="row">
	<div class="col-12 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Добавление пользователя</h1>
	</div>
	<div class="col-12">
	    <form method="post" action="/admin/users/add">
	      <div class="form-group">
	        <label>
	            E-mail
	            <input type="email" class="form-control" placeholder="E-mail" name="email">
	        </label>
	      </div>
	      <div class="form-group">
	        <label>
	            Логин
	            <input type="еуче" class="form-control" placeholder="Логин" name="login">
	            <small id="passwordHelpInline" class="text-muted">
	                Длина должна быть не менее 5-ти символов 
	            </small>
	        </label>
	      </div>
	      <div class="form-group">
	        <label>
	            Пароль
	            <input type="password" class="form-control" placeholder="Пароль" name="password">
	            <small id="passwordHelpInline" class="text-muted">
	                Длина должна быть не менее 7-ми символов 
	            </small>
	        </label>
	      </div>
	      <button type="submit" class="btn main_bgc_color">Зарегистрироваться</button>
	    </form>
	</div>
</div>