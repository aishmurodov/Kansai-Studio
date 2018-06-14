<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class MainController extends Controller {

	public function indexAction() {
		$totalItems = $this->model->all_posts_count();
		$itemsPerPage = 10;
		$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
		$urlPattern = "/page/(:num)";
		$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

		$posts = $this->model->getPosts($currentPage, $itemsPerPage);
		$paginator->setMaxPagesToShow(10);

		$vars = [
			"paginator" => $paginator,
			"posts" => $posts
		];
		$this->view->render('Главная страница', $vars);
	}

	public function searchbywordsAction () {
		$ser_array = explode('-==', $this->route['item']);
		$this->route['item'] = trim(urldecode($ser_array[0]));
		if (isset($ser_array[1]))
			$this->route["page"] = explode('=', $ser_array[1])[1];
		else
			$this->route["page"] = 1;

		if (strlen($this->route["item"]) > 3) {
			$totalItems = $this->model->all_posts_count_by_query($this->route['item']);
			$itemsPerPage = 10;
			$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
			$urlPattern = "/catalog/search/query/".$this->route['item']."-==page=(:num)";
			$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

			$this->route['item'] = htmlspecialchars($this->route['item']);

			$posts = $this->model->getPostsByQuery($this->route['item'],$currentPage, $itemsPerPage);
			$paginator->setMaxPagesToShow(10);

			$vars = [
				"paginator" => $paginator,
				"posts" => $posts
			];
			$this->view->render('Поиск', $vars, 'index');
		}else {
			$this->view->errorCode(404);
		}
	}

	public function searchbycatsAction() {
		$ser_array = explode('-==', $this->route['item']);
		$this->route['item'] = $ser_array[0];
		if (isset($ser_array[1]))
			$this->route["page"] = explode('=', $ser_array[1])[1];
		else
			$this->route["page"] = 1;


		$totalItems = $this->model->all_posts_count_by_tag($this->route['item']);
		$itemsPerPage = 10;
		$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
		$urlPattern = "/catalog/search/cats/".$this->route['item']."-==page=(:num)";
		$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

		$this->route['item'] = htmlspecialchars($this->route['item']);

		$posts = $this->model->getPostsByTags($this->route['item'],$currentPage, $itemsPerPage);
		$paginator->setMaxPagesToShow(10);

		$vars = [
			"paginator" => $paginator,
			"posts" => $posts
		];
		$this->view->render('Поиск по категориям', $vars, 'index');
	}

	public function sortbyseasonAction() {
		$ser_array = explode('-==', $this->route['item']);
		$this->route['item'] = $ser_array[0];
		if (isset($ser_array[1]))
			$this->route["page"] = explode('=', $ser_array[1])[1];
		else
			$this->route["page"] = 1;


		$totalItems = $this->model->all_posts_count('AND `season` = '.$this->route['item']);
		$itemsPerPage = 10;
		$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
		$urlPattern = "/catalog/sort/season/".$this->route['item']."-==page=(:num)";
		$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

		$this->route['item'] = htmlspecialchars($this->route['item']);

		$posts = $this->model->getPosts($currentPage, $itemsPerPage, 'AND `season` = '.$this->route['item'].' ORDER BY `updated_at` DESC');
		$paginator->setMaxPagesToShow(10);

		$vars = [
			"paginator" => $paginator,
			"posts" => $posts
		];
		$this->view->render('Сортировать по сизону', $vars, 'index');
	}

	public function sortbyalphabitAction() {
		$totalItems = $this->model->all_posts_count();
		$itemsPerPage = 10;
		$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
		$urlPattern = "/catalog/sort/alphabit/page/(:num)";
		$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

		$posts = $this->model->getPosts($currentPage, $itemsPerPage, 'ORDER BY `title`');
		$paginator->setMaxPagesToShow(10);

		$vars = [
			"paginator" => $paginator,
			"posts" => $posts
		];
		$this->view->render('Сортировать по алфавиту', $vars, 'index');
	}

	public function profileAction() {
		$account = $this->model->getUser($this->route['login']);
		if (!$account)
			$this->view->errorCode(404);
		else{
			$vars = [
				"account" => $account
			];
			$this->view->render('Профиль '. $this->route['login'], $vars);
		}
	}

}