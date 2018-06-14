<?php

namespace application\core;

class View {

	public $path;
	public $route;
	public $top_sliders = [];

	public $layout = 'default';
	public $company = [];

	public function __construct($route) {
		$this->route = $route;
		$this->path = $route['controller'].'/'.$route['action'];
		$this->company = require 'application/config/company.php';
	}

	public function render($title, $vars = [], $path = '') {
		extract($vars);
		extract($this->top_sliders);
		if ($path != '') {
			$path = 'application/views/'.$this->route['controller'].'/'.$path.'.php';
		}else
			$path = 'application/views/'.$this->path.'.php';
		if (file_exists($path)) {
			ob_start();
			require $path;
			$content = ob_get_clean();
			require 'application/views/layouts/'.$this->layout.'.php';
		}
	}

	public function redirect($url, $to = 'not-full') {
		if ($to != 'full')
			header('location: /'.$url);
		else 
			header('location: '.$url);
		exit;
	}

	public static function errorCode($code) {
		http_response_code($code);
		$path = 'application/views/errors/'.$code.'.php';
		if (file_exists($path)) {
			require $path;
		}
		exit;
	}

	public function message($status, $message) {
		exit(json_encode(['status' => $status, 'message' => $message]));
	}

	public function location($url, $to = 'not-full') {
		if ($to != 'full') {
			exit(json_encode(['url' => '/'.$url]));
		}else {
			exit(json_encode(['url' => $url]));
		}
		
	}

}	