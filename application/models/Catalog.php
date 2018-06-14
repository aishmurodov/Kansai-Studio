<?php

namespace application\models;

use application\core\Model;
use application\model\Account;

class Catalog extends Model {

	public function getAllCommentsForPost ($url) {
		$params = ["url" => $url];
		$postid = $this->db->column("SELECT `id` FROM `kansai_posts` WHERE `url` = :url", $params);
		if ($postid) {
			return $this->db->rowCount("SELECT `id` FROM `kansai_posts_comments` WHERE `postid` = $postid");
		}else {
			return false;
		}
		
	}

	public function plusToViewPost ($id) {
		return $this->db->query("UPDATE `kansai_posts` SET `views` = `views` + 1 WHERE `id` = $id");
	}

	public function plusToViewPostSeries ($id) {
		return $this->db->query("UPDATE `kansai_posts_series` SET `views` = `views` + 1 WHERE `id` = $id");
	}

	public function getPost ($url, $currentPage, $perPage) {

		$params = [
			'slug' => $url
		];
		$sql = "SELECT * FROM `kansai_posts` WHERE `url` = :slug";
		$post = $this->db->row($sql, $params);

		if (!empty($post)) {
			$post = $post[0];

			$sql = "SELECT * FROM `kansai_posts_comments` WHERE `postid` = $post[id] ORDER BY `date` DESC";
			$comments = $this->createPagination($sql, $currentPage, $perPage);
			$res_comm = [];

			if (!empty($comments)) {
				foreach ($comments as $comment) {
					$comm_user = $this->db->row("SELECT `login`,`avatar`,`ugroup` FROM `kansai_accounts` WHERE `id` = $comment[userid]")[0];
					$comment['user'] = $comm_user;
					$res_comm[] = $comment;
				}
			}

			$params = [
				'postid' => $post['id']
			];

			$cats = [];

			$categories_ids = $this->db->row("SELECT `tagid` FROM `kansai_posts_tags` WHERE `postid` = :postid",$params);

			foreach ($categories_ids as $categories_id) {
				$params = [
					'id' => $categories_id['tagid']
				];
				$cats[] = [
					'id' => $categories_id['tagid'],
					'title' => $this->db->column("SELECT `tag` FROM `kansai_tags` WHERE `id` = :id",$params),
				];
			}

			$params = [
				'postid' => $post['id']
			];

			$series = $this->db->row("SELECT * FROM `kansai_posts_series` WHERE `postid` = :postid ORDER BY `number`", $params);

			$series_count = (int) $this->db->column("SELECT `number` FROM `kansai_posts_series` WHERE `postid` = :postid ORDER BY `number` DESC", $params);
			
			if (!$series_count) $series_count = 0;
			$post['totalseries'] = (int) $post['totalseries'];

			if ($post['totalseries'] >= 1 && $series_count > $post['totalseries']) 
				$series_count = $post['totalseries'];

			if (!$post['totalseries']) 
				$post['totalseries'] = 'XXX';
			
			$post['cats'] = $cats;
			$post['series'] = $series;
			$post['series_count'] = $series_count;
			$post['comments'] = $res_comm;

			if (isset($_SESSION['account']['id'])) {
				$post['inFavorite'] = $this->checkInFavorite($post['id']);
				$post['last_serie'] = $this->get_user_last_serie($post['id']);
			}else {
				$post['last_serie'] = 1;
			}

			return $post;
		}else {
			return false;
		}
	}

	public function checkInFavorite ($item) {
		$favorites = json_decode($_SESSION['account']['favorites'], true);

		if (!empty($favorites)) {
			foreach ($favorites as $key => $favorite) {
				if ( $favorite['id'] == $item ){
					return true;
					break;
				}
			}
		}
		return false;
	}

	public function get_user_last_serie ($item) {
		$favorites = json_decode($_SESSION['account']['favorites'], true);

		if (!empty($favorites)) {
			foreach ($favorites as $favorite) {
				if ( $favorite['id'] == $item ){
					return $favorite['serie'];
					break;
				}
			}
		}
		else {
			return 1;
		}
	}

}