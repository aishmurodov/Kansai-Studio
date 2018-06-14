<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class AccountController extends Controller {

	public function loginAction() {
		if (!empty($_POST)) {

			if (!$this->model->validate(["email", "password"], $_POST)) 
				$this->view->message("Ошибка",$this->model->error);
			else if (!$this->model->checkData($_POST['email'], $_POST['password']))
				$this->view->message("Ошибка", "E-mail или пароль указан неверно");
			else if (!$this->model->checkStatus('email', $_POST['email']))
				$this->view->message("Ошибка", $this->model->error);
			else {
				$this->model->authorize($_POST['email']);
				$this->view->location('account/profile');
			}
		}
		$this->view->render('Вход');
	}

	public function getLastViewedSerieAction () {
		if (!empty($_POST)) {
			if (isset($_SESSION['account']['id'])) {
				echo $this->model->get_user_last_serie($_POST['item']);
			}
			else 
				echo 1;
		}
		else {
			$this->view->errorCode(404);
		}
	}

	public function favoritesAction () {
		if (!empty($_POST)) {
			if (isset($_POST['delete-favorite'])) {
				if (!$this->model->removeFavorte($_POST['delete-favorite'])) 
					$this->view->message('Ошибка', 'Не удалось удалить из списка этот пост');
				else 
					$this->view->location($_SERVER['HTTP_REFERER'], 'full');
			}
			else if (isset($_POST['add-favorite'])) {
				if ($this->model->checkInFavorite($_POST['add-favorite'])) 
					$this->view->message('Ошибка', $this->model->error);
				else {
					if ( $this->model->addFavorte($_POST['add-favorite']) )
						$this->view->message('Успех', 'этот пост добавлен в ваш список избранных');
				}
			}
			else if (isset($_POST['put-last-serie']) && isset($_POST['item'])) {
				$this->model->put_user_last_serie($_POST['item'], $_POST['put-last-serie']);
			}
		}else {
			$totalItems = $this->model->all_posts_count_for_favorite();
			$itemsPerPage = 10;
			$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
			$urlPattern = "/account/favorites/page/(:num)";
			$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

			$favorites = $this->model->getFavoritePosts($currentPage, $itemsPerPage);
			$paginator->setMaxPagesToShow(10);

			$favorites = $this->model->get_user_last_serie_for_array($favorites);

			$vars = [
				"paginator" => $paginator,
				"favorites" => $favorites
			];
			$this->view->render('Избранные', $vars);
		}
	}

	public function registerAction() {
		if (!empty($_POST)) {

			if (!$this->model->validate(["email", "login", "password", "repeat_pass"], $_POST)) 
				$this->view->message("Ошибка",$this->model->error);
			else if (!$this->model->checkEmailExists($_POST['email'])) 
				$this->view->message("Ошибка",$this->model->error);
			else if (!$this->model->checkLoginExists($_POST['login']))
				$this->view->message("Ошибка",$this->model->error);
			else {
				$this->model->register($_POST);
				$this->view->message("Успех","регистрация завершена. На вашу почту было отправлено письмо с ссылкой на активацию аккаунта. Если его нет, то посмотрить в разделе \"Спам\", если же там нет, то подождите еще немного");
			}
		}
		$this->view->render('Регистрация');
	}

	public function confirmAction() {
		if (!$this->model->checkTokenExists($this->route['token'])) 
			$this->view->redirect('account/login');
		$this->model->activate($this->route['token']);
		$this->view->render('Регистрация завершена');
	}

	public function recoveryAction() {
		if (!empty($_POST)) {

			if (!$this->model->validate(["email"], $_POST)) 
				$this->view->message("Ошибка",$this->model->error);
			else if ($this->model->checkEmailExists($_POST['email'])) 
				$this->view->message("Ошибка","Такого E-mail'а не существует в базе");
			else if (!$this->model->checkStatus('email', $_POST['email']))
				$this->view->message("Ошибка", $this->model->error);
			else {
				$this->model->recovery($_POST);
				$this->view->message("Успех","запрос на восстановление пароля обработан. На вашу почту было отправлено письмо с ссылкой на сброс пароля. Если его нет, то посмотрить в разделе \"Спам\", если же там нет, то подождите еще немного");
			}
		}

		$this->view->render('Восстановление пароля');
	}

	public function resetAction() {
		if (!$this->model->checkTokenExists($this->route['token'])) 
			$this->view->redirect('account/login');
		$password = $this->model->reset($this->route['token']);
		$vars = [
			"password" => $password
		];
		$this->view->render('Пароль сброшен', $vars);
	}

	public function profileAction() {
		if (!empty($_FILES)) {
			if (!$this->model->validateAvatar($_FILES))
				$this->view->message("Ошибка",$this->model->error);
			else {
				if (!$this->model->updateAvatar($_FILES)) {
					$this->view->message("Ошибка",$this->model->error);
				}else {
					$this->view->message("Успех", "Аватар успешно загружен");
				}
			}
		}
		else if (!empty($_POST)) {
			if ($_POST['email'] != $_SESSION['account']['email']) {
				if ( !$this->model->validate(["email"], $_POST) )
					$this->view->message("Ошибка",$this->model->error);
				else if (!$this->model->checkEmailExists($_POST['email'])) 
					$this->view->message("Ошибка",$this->model->error);
			}
			if (!empty($_POST['password'])) {
				if ( !$this->model->validate(["password"], $_POST) )
					$this->view->message("Ошибка",$this->model->error);
			}
			if ($this->model->updateSettings($_POST))
				$this->view->message("Успех","Настройи изменены");
		}
		else 
			$this->view->render('Профиль');
	}

	public function logoutAction() {
		if ( $this->model->logout() )
			$this->view->redirect('account/login');
	}

	public function addCommentAction () {
		if (isset($_POST['item']) & isset($_POST['commentText'])) {
			if (!$this->model->validateComment($_POST)) 
				$this->view->message("Ошибка", $this->model->error);
			else {
				$item = $this->model->addComment($_POST);
				$this->view->location('catalog/item/'.$item);
			}
		}else 
			$this->view->errorCode(404);
			// $this->view->message('', json_encode($_POST));
	}

	public function ratePostAction() {
		if (isset($_POST['vote']) && isset($_POST['item'])) {
			if (!$this->model->validateRate($_POST['vote'])) 
				$this->view->message('Ошибка', $this->model->error);
			else if (!$this->model->checkInRate($_POST['item'], $_POST['vote'])) 
				$this->view->message('Ошибка', $this->model->error);
			else {
				$this->model->doRatePost($_POST['item'], $_POST['vote']);
				$this->view->message('Усаех', 'спасибо за то, что проголосовали');
			}
		}else {
			var_dump($_POST);
			// $this->view->errorCode(404);
		}
	}

}