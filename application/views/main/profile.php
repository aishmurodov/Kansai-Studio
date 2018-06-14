<div class="block">
    <div class="row">
        <div class="col-12">
            <h1 class="mt-4 mb-3">Профиль <?= $account['login']?></h1>
        </div>
        <div class="col-md-4 text-center">
            <img src="
            <?
            if (!$account['avatar'])
                echo "/public/images/non-avatar.png";
            else
                echo $account['avatar'];
            ?>
        " class="img-fluid" id="avatarIMG" alt="">
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <div>
                    <input type="text" class="form-control container-fluid" disabled value="<?= returnRankValue($account['ugroup']) ?>">
                </div>
            </div>
            <div class="form-group">
                <div>
                    Логин
                    <input type="text" class="form-control container-fluid" disabled value="<?= $account['login'] ?>">
                </div>
            </div>
            <div class="form-group">
                <div>
                    Email адрес
                    <input type="email" class="form-control container-fluid" disabled value="<?= $account['email'] ?>">
                </div>
            </div>
        </div>
    </div>
</div>