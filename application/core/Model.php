<?php

namespace application\core;

use application\lib\Db;
use application\lib\CookieManager;

abstract class Model {

	public $db;
	public $superadmins = [];
	
	public function __construct() {
		$this->db = new Db;
		$this->superadmins = require 'application/config/superadmins.php';
		if ( !CookieManager::read('activity') ) {
			CookieManager::create('activity', 1);
			$day = (int) date('N');
			$this->db->query("UPDATE `kansai_dashboard_statistic` SET `count` = `count` + 1 WHERE `day` = $day");
		}
	}

	public function getTopHeader () {
		return $this->db->row("SELECT * FROM `kansai_siteHeader_Images` ORDER BY `position`, `id` DESC");
	}

	public function curl_get ($url, $referer = "http://www.google.com") {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0");
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public function createPagination ( $sql, $current_page, $per_page = 10 ) {

		$per_page = $per_page > 0 ? $per_page : 1;

		$total_count = $this->db->rowCount($sql);
		$total_pages = ceil($total_count / $per_page);
		if ( $current_page <= 1 || $current_page > $total_pages ) 
			$current_page = 1;
		$offset = ($per_page * $current_page) - $per_page;
		$sql .= " LIMIT $offset, $per_page";

		$items = $this->db->row($sql);

		$result = $items;
		return $result;
	}

	public function sendSmtpMail ($to, $subject, $textBody) {
		$params = [
			"from" => urlencode('support@createpro.site'),
			"to" => urlencode($to),
			"subject" => urlencode($subject),
			"textBody" => urlencode($textBody),
		];

		$result = $this->curl_get("https://phenixsignal.ru/res/phpMailSend/mail.php?".http_build_query($params));

		if ($result) {
			return true;
		}else
			return false;
	}

}