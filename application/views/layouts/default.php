<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?= $title ?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="/public/styles/plyr.css">
	<link rel="stylesheet" href="/public/styles/styles.css?<?=time()?>">
	<link rel="stylesheet" href="/public/styles/ARating.css">
</head>
<body>
	
	<div id="wrapper">
		<div id="top_slider">
			<div id="top_slider_carusel_indicator" class="carousel slide" data-ride="carousel">
			  <ol class="carousel-indicators">
			  	<?php if (count($top_sliders) > 1): ?>
				  	<?php $count = 0;foreach ($top_sliders as $top_slider): ?>
				    	<li data-target="#top_slider_carusel_indicator" data-slide-to="<?=$count?>" class="<?if ($count == 0) echo 'active'?>"></li>
				    <?php $count++; endforeach ?>
				<?php endif ?>
			  </ol>
			  <div class="carousel-inner">
				<?php $count = 1;foreach ($top_sliders as $top_slider): ?>
					<div class="carousel-item <?if ($count == 1) echo 'active'?>">
						<a href="#">
							<img class="d-block w-100" src="<?=$top_slider['url']?>" alt="<?=$top_slider['position']?>">
						</a>
				    </div>
				<?php $count++; endforeach ?>
			  </div>
			  <?php if (count($top_sliders) > 1): ?>
				  <a class="carousel-control-prev" href="#top_slider_carusel_indicator" role="button" data-slide="prev">
				    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
				    <span class="sr-only">Previous</span>
				  </a>
				  <a class="carousel-control-next" href="#top_slider_carusel_indicator" role="button" data-slide="next">
				    <span class="carousel-control-next-icon" aria-hidden="true"></span>
				    <span class="sr-only">Next</span>
				  </a>
			  <?php endif ?>
			</div>
		</div>
		<div id="main_content" class="container-fluid">
			<div class="row">
				<div id="left_content" class="col-md-8">
					<nav id="top_nav">
						<ul>
							<li>
								<a href="/">главная</a>
							</li>
							<li>
								<a href="#">Отсортировать аниме</a>
								<ul>
									<li><a href="/catalog/sort/alphabit">по алфавиту</a></li>
									<li><a href="/catalog/sort/season/1">зимнний сезон</a></li>
									<li><a href="/catalog/sort/season/2">весенний сезон</a></li>
									<li><a href="/catalog/sort/season/3">летний сезон</a></li>
									<li><a href="/catalog/sort/season/4">осенний сезон</a></li>
								</ul>
							</li>
							<li>
								<a href="#">ссылки</a>
								<ul>
									<li><a href="https://vk.com/kansai_official">группа вк</a></li>
									<li><a href="https://www.youtube.com/user/studiokansai">youtube-канал</a></li>
									<li><a href="https://t.me/studiokansai">telegram-канал</a></li>
								</ul>
							</li>
						</ul>
					</nav>
					<main id="content">
						<?= $content ?>
					</main>
				</div>
				<aside id="right_content" class="col-md-4">
					<div class="context">
						<div class="block">
							<div class="block-title">
								Поиск
							</div>
							<div class="block-body">
								<form action="#" id="aside_search" method="post">
									<div class="form-row align-items-center">
										<div class="col-auto">
											<div class="input-group mb-2">
												<input type="text" class="form-control" placeholder="Что ищем">
												<div class="input-group-append">
													<div class="input-group-text">
														<i class="fas fa-search"></i>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<?php if (!isset($_SESSION['account']['id'])): ?>
							<div class="block">
								<div class="block-title hovarable-block" data-toggle="modal" data-target="#AuthModal">
									Авторизцания/Регситрация
								</div>
								<div class="block-body">
								</div>
							</div>
						<?php else: ?>
							<div class="block">
								<div class="block-title"><?= $_SESSION['account']['login'] ?></div>
								<div class="block-body">
									<ul class="upperTextWIthoutList">
										<a href="/account/profile"><li><i class="fas fa-user-circle"></i> Профиль</li></a>
										<a href="/account/favorites"><li><i class="fas fa-heart"></i> Избранные</li></a>
										<?php if (isset($_SESSION['admin'])): ?>
											<a href="/admin"><li><i class="fab fa-autoprefixer"></i> Админ-панель</li></a>
										<?php endif ?>
										<a href="/account/logout"><li><i class="fas fa-sign-out-alt"></i> Выйти</li></a>
									</ul>
								</div>
							</div>
						<?php endif ?>
						<div class="block">
							<div class="block-title">
								Мы в ВК
							</div>
							<div class="block-body">
								<iframe height="500" src="https://vk.com/widget_community.php?app=0&width=297px&_ver=1&gid=39376197&mode=2&color1=FFFCFC&color2=1B1616&color3=333&class_name=&height=500&url=https://kansai.studio&referrer=&title=Kansai Studio&163e4eb6e43" frameborder="0"></iframe>
							</div>
						</div>
					</div>
				</aside>
			</div>
		</div>
		<div id="footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<h5 class="white-text"><?= $this->company['name'] ?></h5>
						<p>
							<?= nl2br($this->company['about']) ?>
                		</p>
					</div>
					<div class="col-md-3">
						<h5>Ссылки</h5>
						<ul class="upperTextWIthoutList">
							<li><i class="fab fa-vk"></i><a href="https://vk.com/kansai_official">группа вк</a></li>
							<li><i class="fab fa-youtube"></i><a href="https://www.youtube.com/user/studiokansai">youtube-канал</a></li>
							<li><i class="fab fa-telegram-plane"></i><a href="https://t.me/studiokansai">telegram-канал</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if (!isset($_SESSION['account']['id'])): ?>
	<div class="modal fade" id="AuthModal" tabindex="-1" role="dialog" aria-labelledby="AuthModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form action="/account/login" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="AuthModalTitle">Авторизцания/Регситрация</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
						<div class="form-group">
							<label for="exampleInputEmail1">Email адрес</label>
							<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите свой E-mail" name="email"></div>
						<div class="form-group">
							<label for="exampleInputPassword1">Пароль</label>
							<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Введите свой пароль" name="password"></div>
						<div class="form-group">
							<a href="/account/recovery">Забыл пароль?</a>
						</div>
				</div>
				<div class="modal-footer">
					<a href="/account/register" class="btn btn-secondary">Регистрация</a>
					<button type="submit" class="btn btn-primary">Войти</button>
				</div>
			</form>
		</div>
	</div>
	</div>
	<?php endif?>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="https://phenixsignal.ru/res/libs/font-awesome.js"></script>
	<script src="/public/scripts/plyr.js"></script>
	<script src="/public/scripts/main.js?<?=time()?>"></script>
	<script src="/public/scripts/ARating.js"></script>
	<script src="/public/scripts/form.js?<?=time()?>"></script>
	<?php if (isset($_SESSION['account']['id'])): ?>
		<script src="/public/scripts/account.js?<?=time()?>"></script>
	<?php endif ?>
</body>
</html>