<?php

namespace application\models;

use application\core\Model;

class Main extends Model {

	public function getUser ($login) {
		$params = [
			"login" => $login
		];

		$user = $this->db->row("SELECT * FROM `kansai_accounts` WHERE `login` = :login", $params);
		if ( empty($user) ) {
			return false;
		}else
			return $user[0];
	}

	public function all_posts_count ($sql = '') {
		return $this->db->rowCount("SELECT `id` FROM `kansai_posts` WHERE `isHidden` = 0 $sql");
	}

	public function all_posts_count_by_query ($query) {
		$params = [
			"query" => htmlspecialchars($query)
		];
		$query = htmlspecialchars(urldecode($query));
		return $this->db->rowCount("SELECT `id` FROM `kansai_posts` WHERE `isHidden` = 0 AND (`title` LIKE '%%$query%%' OR `original_title` LIKE '%%$query%%' OR `description` LIKE '%%$query%%')");
	}

	public function getPostsByQuery ($query, $currentPage, $perPage) {
		$query = htmlspecialchars($query);
		$sql = "SELECT * FROM `kansai_posts` WHERE `isHidden` = 0 AND (`title` LIKE '%%$query%%' OR `original_title` LIKE '%%$query%%' OR `description` LIKE '%%$query%%') ORDER BY `updated_at` DESC";
		$posts = $this->createPagination($sql, $currentPage, $perPage);

		$result = [];

		foreach ($posts as $post) {
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

			$series_count = (int) $this->db->column("SELECT `number` FROM `kansai_posts_series` WHERE `postid` = :postid ORDER BY `number` DESC", $params);
			
			if (!$series_count) $series_count = 0;
			$post['totalseries'] = (int) $post['totalseries'];

			if ($post['totalseries'] >= 1 && $series_count > $post['totalseries']) 
				$series_count = $post['totalseries'];

			if (!$post['totalseries']) 
				$post['totalseries'] = 'XXX';
			

			$post['cats'] = $cats;
			$post['series_count'] = $series_count;
			$result[] = $post;
		}

		return $result;
	}

	public function all_posts_count_by_tag ($cat_id) {
		$params = [
			"cat_id" => $cat_id
		];

		$all_posts_id = $this->db->row("SELECT `postid` FROM `kansai_posts_tags` WHERE `tagid` = :cat_id",$params);
		$str_post_ids = '';
		foreach ($all_posts_id as $post_ids) {
			$str_post_ids .= "`id` = $post_ids[postid] OR ";
		}

		$str_post_ids = substr($str_post_ids, 0, -4);
		return $this->db->rowCount("SELECT `id` FROM `kansai_posts` WHERE `isHidden` = 0 AND ($str_post_ids)");
	}

	public function getPostsByTags ($cat_id, $currentPage, $perPage) {
		$params = [
			"cat_id" => $cat_id
		];

		$all_posts_id = $this->db->row("SELECT `postid` FROM `kansai_posts_tags` WHERE `tagid` = :cat_id",$params);
		$str_post_ids = '';
		foreach ($all_posts_id as $post_ids) {
			$str_post_ids .= "`id` = $post_ids[postid] OR ";
		}

		$str_post_ids = substr($str_post_ids, 0, -4);

		$sql = "SELECT * FROM `kansai_posts` WHERE `isHidden` = 0 AND ($str_post_ids) ORDER BY `updated_at` DESC";

		$posts = $this->createPagination($sql, $currentPage, $perPage);
		$result = [];

		foreach ($posts as $post) {
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

			$series_count = (int) $this->db->column("SELECT `number` FROM `kansai_posts_series` WHERE `postid` = :postid ORDER BY `number` DESC", $params);
			
			if (!$series_count) $series_count = 0;
			$post['totalseries'] = (int) $post['totalseries'];

			if ($post['totalseries'] >= 1 && $series_count > $post['totalseries']) 
				$series_count = $post['totalseries'];

			if (!$post['totalseries']) 
				$post['totalseries'] = 'XXX';
			

			$post['cats'] = $cats;
			$post['series_count'] = $series_count;
			$result[] = $post;
		}

		return $result;
	}

	public function getPosts ($currentPage, $perPage, $sort = 'ORDER BY `updated_at` DESC') {
		$sql = "SELECT * FROM `kansai_posts` WHERE `isHidden` = 0 $sort";
		$posts = $this->createPagination($sql, $currentPage, $perPage);

		$result = [];

		foreach ($posts as $post) {
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

			$series_count = (int) $this->db->column("SELECT `number` FROM `kansai_posts_series` WHERE `postid` = :postid ORDER BY `number` DESC", $params);
			
			if (!$series_count) $series_count = 0;
			$post['totalseries'] = (int) $post['totalseries'];

			if ($post['totalseries'] >= 1 && $series_count > $post['totalseries']) 
				$series_count = $post['totalseries'];

			if (!$post['totalseries']) 
				$post['totalseries'] = 'XXX';
			

			$post['cats'] = $cats;
			$post['series_count'] = $series_count;
			$result[] = $post;
		}

		return $result;
	}

}