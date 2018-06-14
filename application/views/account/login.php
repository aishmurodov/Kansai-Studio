<div class="block">
    <h3>Вход</h3>
    <hr>
    <form method="post" action="/account/login">
        <div class="form-group">
            <label for="exampleInputEmail1">Email адрес</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Введите свой E-mail" name="email"></div>
        <div class="form-group">
            <label for="exampleInputPassword1">Пароль</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Введите свой пароль" name="password"></div>
        <div class="form-group">
            <a href="/account/recovery">Забыл пароль?</a>
        </div>
        <button type="submit" class="btn main_bgc_color">Войти</button>
        <a href="/account/register" class="btn btn-secondary">Загеристрироваться</a>
    </form>
</div>