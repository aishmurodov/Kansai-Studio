<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class AdminController extends Controller {

	public function dashboardAction() {
		$this->view->layout = "admin";
		$vars = [
			"tags" => $this->model->dashboard_get_last_10_cats(),
			"posts" => $this->model->dashboard_get_last_10_posts(),
			"activityes" => $this->model->get_activity_static(),
			"comments" => $this->model->dashboard_get_last_50_cats(),
		];
		$this->view->render('Админ панель', $vars);
	}

	public function seriesAction() {
		if (isset($this->route['deleteserieid'])) {
			$this->model->deleteSerie($this->route['deleteserieid']);
			$this->view->redirect($_SERVER['HTTP_REFERER'], 'full');
		}
		else if (!empty($_POST)) {
			if ($this->route['postid'] == "add_serie") {
				if (!empty($this->model->getPost($_POST['postid']))) {
					if ( !$this->model->addSerie($_POST) )
						$this->view->message('Ошибка', $this->model->error);
					else {
						$this->view->location('admin/series/id/'.$_POST['postid']);
					}
				}else {
					$this->view->message('Ошибка', 'Пост не найден');
				}
			}else if ($this->route['postid'] == "update_serie") {
				if (!empty($this->model->checkIssetSerie($_POST['serieid']))) {
					if ( !$this->model->updateSerie($_POST) )
						$this->view->message('Ошибка', $this->model->error);
					else {
						$this->view->location('admin/series/id/'.$_POST['postid']);
					}
				}else {
					$this->view->message('Ошибка', 'Серия не найдена');
				}
			}
		}else {
			$post = $this->model->getPost($this->route['postid']);
			if (!empty($post)) {
				$this->view->layout = "admin";
				$vars = [
					"post" => $post,
					"series" => $this->model->getSeriesOfPost($this->route['postid']),
				];
				$this->view->render('Управление сериями', $vars);
			}else {
				$this->view->errorCode(404);
			}
		}
	}

	public function torrentsAction() {
		if (!empty($_POST)) {
			$post = $this->model->getPost($this->route['postid']);
			if (!empty($post)) {
				if (isset($_POST['url']) && isset($_POST['resolution'])) {
					// echo $this->route['postid'];
					$res_count = count($_POST['resolution']);
					$torrent = [];
					for ($i = 1; $i <= $res_count; $i++) {
						if (!empty($_POST['resolution'][$i]) && !empty($_POST['url'][$i])) {
							$torrent[$_POST['resolution'][$i]] = $_POST['url'][$i];
						}
					}
					$this->model->updateTorrents($this->route['postid'], json_encode($torrent));
					$this->view->location($_SERVER['HTTP_REFERER'], 'full');
				}
			}else {
				$this->view->errorCode(404);
			}
		}
		else {
			$post = $this->model->getPost($this->route['postid']);
			if (!empty($post)) {
				$this->view->layout = "admin";
				$vars = [
					"post" => $post
				];
				$this->view->render('Управление торрентами', $vars);
			}else {
				$this->view->errorCode(404);
			}
		}
	}

	public function spoilerCommentAction() {
		$this->model->spoilerComment($_POST['comment_id']);
		$this->view->message('Успех', "комментарий был отмечен как спам");
	}

	public function unspoilerCommentAction() {
		$this->model->unspoilerComment($_POST['comment_id']);
		$this->view->message('Успех', "комментарий был убран из спам-листа");
	}

	public function deleteCommentAction() {
		$this->model->deleteComment($_POST['comment_id']);
		$this->view->message('Успех', "комментарий был удалён");
	}

	public function siteheaderAction () {
		if (isset($this->route['deletepostid'])) {
			$this->model->deleteSiteHeader($this->route['deletepostid']);
			$this->view->redirect('admin/siteheader');
		}else if (!empty($_POST)) {
			if (!empty($_POST['changeposition'])) {

				if (!$this->model->changeposition($_POST['changeposition'])) 
					$this->view->message('Ошибка',$this->model->error);
				else
					$this->view->location('admin/siteheader');
			}else if ( isset($_FILES['imageurl']['size']) && $_FILES['imageurl']['size'] > 0 ) {
				if (!$this->model->createNewHeader($_POST, $_FILES)) 
					$this->view->message('Ошибка',$this->model->error);
				else
					$this->view->location('admin/siteheader');
			}
		}else {
			$this->view->layout = "admin";
			$vars = [
				"images" => $this->model->getSiteHeader()
			];
			$this->view->render('Шапка сайта', $vars);
		}
	}

	public function editCatalogAction() {
		$this->view->layout = "admin";

		$totalItems = $this->model->all_posts_count();
		$itemsPerPage = 20;
		$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
		$urlPattern = "/admin/catalog/edit/page/(:num)";
		$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

		$posts = $this->model->getPosts($currentPage, $itemsPerPage);
		$paginator->setMaxPagesToShow(10);

		$vars = [
			"paginator" => $paginator,
			"posts" => $posts
		];

		$this->view->render('Редактировать', $vars);
	}

	public function updateStatusOfPostAction () {
		if (!empty($_POST)) {
			if (isset($_POST['set-hidden'])) 
				if (!$this->model->hidePost($_POST['set-hidden'])) 
					$this->view->message('Ошибка', "Не удалось скрыть пост");
			else if (isset($_POST['unset-hidden'])) 
				if (!$this->model->showPost($_POST['unset-hidden'])) 
					$this->view->message('Ошибка', "Не удалось скрыть пост");
			$this->view->location('admin/catalog/edit');
		}else {
			$this->view->errorCode(404);
		}
	}

	public function editCatalogWithIdAction() {
		$this->view->layout = "admin";
		$vars = [
			"post" => $this->model->getPost($this->route['postid']),
			"cats" => $this->model->getAllCats()
		];
		if (!$vars['post']) 
			$this->view->errorCode(404);
		else{
			if (!empty($_POST)) {
				if ( isset($_FILES['poster']['size']) && $_FILES['poster']['size'] > 0 ) {
					if ( !$this->model->uploadPoster($_FILES,$this->route['postid']) )
						$this->view->message("Ошибка", $this->model->error);
				}
				if (!$this->model->updatePost($_POST, $this->route['postid']))
					$this->view->message("Ошибка", $this->model->error);
				else {
					$this->view->location('catalog/item/'.$vars['post']['url']);
				}
			}else {
				$this->view->render('Редактировать', $vars);
			}
		}
	}

	public function addCatalogAction() {
		if (!empty($_POST) && !empty($_FILES)) {
			if (!$this->model->addPost($_POST, $_FILES)) 
				$this->view->message("Ошибка",$this->model->error);
			else
				$this->view->location($this->model->error);
		}else {
			$vars = [
				"cats" => $this->model->getAllCats()
			];
			$this->view->layout = "admin";
			$this->view->render('Добавить тайтл',$vars);
		}
	}

	public function catsAction() {
		if (isset($this->route['deleteid'])) {
			$this->model->removeTag($this->route['deleteid']);
			$this->view->redirect('admin/cats');
		}else if (!empty($_POST)) {
			if ($_POST['addTag']) {
				if (!$this->model->validateTag($_POST['addTag']))
					$this->view->message("Ошибка",$this->model->error);
				else {
					$this->model->addTag($_POST['addTag']);
					// $this->view->message("Успех", "Категория добавлена");
					$this->view->location("admin/cats");
				}
			}
		}else {
			$this->view->layout = "admin";
			$vars = [
				"cats" => $this->model->getAllCats()
			];

			$this->view->render('Категории',$vars);
		}
	}

	public function usersAction() {
		$this->view->layout = "admin";
		if ($_POST) {
			if ( isset($_POST['set-admin']) && isset($_SESSION['superAdmin']) ){
				if (!$this->model->checkLoginExists($_POST['set-admin'])) {
					$this->model->setAdmin($_POST['set-admin']);
					$this->view->message("Успех","Пользователь назначен администратором");
				}
			}
			if ( isset($_POST['delete-admin']) && isset($_SESSION['superAdmin']) ){
				if (!$this->model->checkLoginExists($_POST['delete-admin'])) {
					$this->model->deleteAdmin($_POST['delete-admin']);
					$this->view->message("Успех","Пользователь удалён из администрации");
				}
			}
		}
		$totalItems = $this->model->all_users_count();
		$itemsPerPage = 10;
		$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
		$urlPattern = "/admin/users/page/(:num)";
		$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

		$users = $this->model->getUsersByPer($currentPage, $itemsPerPage);
		$paginator->setMaxPagesToShow(10);

		$vars = [
			"paginator" => $paginator,
			"users" => $users
		];
		$this->view->render('Посмотреть пользователей', $vars);
	}

	public function useraddAction() {
		if (!empty($_POST)) {
			if (!$this->model->validate(["email", "login", "password"], $_POST)) 
				$this->view->message("Ошибка",$this->model->error);
			else if (!$this->model->checkEmailExists($_POST['email'])) 
				$this->view->message("Ошибка",$this->model->error);
			else if (!$this->model->checkLoginExists($_POST['login']))
				$this->view->message("Ошибка",$this->model->error);
			else {
				$this->model->createNewUser($_POST);
				$this->view->message("Успех","Новый пользователь добавлен");
			}
		}else {
			$this->view->layout = "admin";
			$this->view->render('Добавить пользователя');
		}

	}
}