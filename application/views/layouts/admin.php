<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?= $title ?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="/public/styles/admin.css?<?=time()?>">
	<link rel="stylesheet" href="/public/styles/ARating.css">
  <link rel="stylesheet" href="/public/styles/plyr.css">
</head>
<body>

    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#"><?= $this->company['name'] ?></a>
      
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="/">Перейти к сайту</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="/admin">
                  <span data-feather="home"></span>
                  <i class="fas fa-chart-line"></i>
                  Статистика
                </a>
              </li>
            </ul>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Контент</span>
              <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="/admin/siteheader">
                  <span data-feather="file-text"></span>
                  <i class="fas fa-images"></i>
                  Шапка сайта
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/catalog/add">
                  <span data-feather="file-text"></span>
                  <i class="fas fa-book"></i>
                  Новый тайтл
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/catalog/edit">
                  <span data-feather="file-text"></span>
                  <i class="fas fa-edit"></i>
                  Редактировать тайтл
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/cats">
                  <span data-feather="file-text"></span>
                  <i class="fas fa-tags"></i>
                  Категории
                </a>
              </li>
            </ul>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Пользователи</span>
              <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="/admin/users/add">
                  <span data-feather="file-text"></span>
                  <i class="fas fa-user-plus"></i>
                  Добавить
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/users">
                  <span data-feather="file-text"></span>
                  <i class="fas fa-users"></i>
                  Посмотреть
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/account/profile">
                  <span data-feather="file-text"></span>
                  <i class="fas fa-user"></i>
                  Свой профиль
                </a>
              </li>
          	</ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <?= $content ?>
        </main>
      </div>
    </div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="https://phenixsignal.ru/res/libs/font-awesome.js"></script>
  <script src="/public/scripts/plyr.js"></script>
	<script src="/public/scripts/main.js?<?=time()?>"></script>
	<script src="/public/scripts/ARating.js"></script>
	<script src="/public/scripts/form.js?<?=time()?>"></script>
</body>
</html>