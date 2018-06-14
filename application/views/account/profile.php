<div class="block">
    <div class="row">
    	<div class="col-12">
    		<h1 class="mt-4 mb-3">Профиль</h1>
    	</div>
        <div class="col-md-4 text-center">
            <img src="<?= $_SESSION['account']['avatar'] ?>" class="img-fluid" id="avatarIMG" alt="">
            <br>
            <label id="avatar_inputForm">
                <form action="/account/profile" method="post" enctype="multipart/form-data" id="avatarFORM">
                    <input type="file" name="avatar_input" onclick="preLoadImage();" onchange="" id="avatar_input">
                </form><br>
                <span class="btn main_bgc_color">Обновить аватар</span>
            </label>
        </div>
        <form action="/account/profile" class="col-md-8" method="post">
            <div class="form-group">
                <label>
                    <input type="text" class="form-control" disabled value="<?= returnRankValue($_SESSION['account']['ugroup']) ?>">
                </label>
            </div>
            <div class="form-group">
                <label>
                    Логин
                    <input type="text" class="form-control" disabled placeholder="Логин" value="<?= $_SESSION['account']['login'] ?>">
                </label>
            </div>
            <div class="form-group">
                <label>
                    Email адрес
                    <input type="email" class="form-control" placeholder="Введите новый E-mail" value="<?= $_SESSION['account']['email'] ?>" name="email">
                </label>
            </div>
            <div class="form-group">
                <label>
                    Пароль
                    <input type="text" class="form-control" placeholder="Введите новый пароль" name="password">
                </label>
            </div>
            <button type="submit" class="btn main_bgc_color">Обновить настройки</button>
        </form>
    </div>
</div>