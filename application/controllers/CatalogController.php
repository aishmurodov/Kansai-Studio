<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class CatalogController extends Controller {

	public function itemAction() {
		$ser_array = explode('-==', $this->route['item']);
		$this->route['item'] = $ser_array[0];
		if (isset($ser_array[1]))
			$this->route["page"] = explode('=', $ser_array[1])[1];
		else
			$this->route["page"] = 1;

		$totalItems = $this->model->getAllCommentsForPost($this->route['item']);
		$itemsPerPage = 10;
		$currentPage = (isset($this->route["page"])) ? $this->route["page"] : 1;
		$urlPattern = "/catalog/item/".$this->route['item']."-==page=(:num)#comments-container";
		$paginator = new Pagination($totalItems, $itemsPerPage, $currentPage, $urlPattern);

		$item = $this->model->getPost($this->route['item'], $currentPage, $itemsPerPage);
		$paginator->setMaxPagesToShow(10);
		if ( !$item )
			$this->view->errorCode(404);
		else {
			$vars = [
				"paginator" => $paginator,
				'post' => $item
			];
			$this->model->plusToViewPost($item['id']);
			$this->view->render($item['title'], $vars);
		}
	}

	public function plusviewtoserieAction () {
		if (!empty($_POST)) {
			$serie =  (int) $_POST['serie_id'];
			if ($this->model->plusToViewPostSeries($serie)) {
				echo 'Success';
			}
		}else {
			$this->view->errorCode(404);
		}
	}

}