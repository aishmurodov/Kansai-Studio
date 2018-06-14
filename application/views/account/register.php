<div class="block">
    <h3>Регистрация</h3>
    <hr>
    <form method="post" action="/account/register">
      <div class="form-group">
        <label>
            E-mail
            <input type="email" class="form-control" placeholder="E-mail" name="email">
            <small>На указанный в форме e-mail придет запрос на подтверждение регистрации.</small>
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
      <div class="form-group">
        <label>
            Подтверждение пароля
            <input type="password" class="form-control" placeholder="Подтверждение пароля" name="repeat_pass">
        </label>
      </div>
      <button type="submit" class="btn main_bgc_color">Зарегистрироваться</button>
    </form>
</div>