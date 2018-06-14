<?php

namespace application\models;

use application\core\Model;

class Admin extends Model {

	public function getSiteHeader () {
		return $this->db->row("SELECT * FROM `kansai_siteHeader_Images` ORDER BY `position`");
	}

	public function spoilerComment ($id) {
		$params = [
			'id' => $id
		];
		
		return $this->db->query("UPDATE `kansai_posts_comments` SET `spoiler` = 1 WHERE `id` = :id", $params);
	}

	public function unspoilerComment ($id) {
		$params = [
			'id' => $id
		];
		
		return $this->db->query("UPDATE `kansai_posts_comments` SET `spoiler` = 0 WHERE `id` = :id", $params);
	}

	public function deleteComment ($id) {
		$params = [
			'id' => $id
		];

		return $this->db->query("DELETE FROM `kansai_posts_comments` WHERE `id` = :id", $params);
	}

	public function deleteSiteHeader ($id) {
		$params = [
			'id' => $id
		];
		$image = $this->db->column("SELECT `url` FROM `kansai_siteHeader_Images` WHERE `id` = :id", $params);
		$image = substr($image, 1);
		unlink($image);
		return $this->db->query("DELETE FROM `kansai_siteHeader_Images` WHERE `id` = :id", $params);
	}

	public function createNewHeader ($post,$file) {

		$image = $file['imageurl'];
		$current_format = @end(explode(".", $image['name']));
		$formats = [
			"jpg","jpeg","png"
		];

		$position = (int) $post['positionOnSite'];
		if ( in_array($current_format, $formats)){
			$dir = "public/images/top_header/$position-".time().".$current_format";
			if (move_uploaded_file($image['tmp_name'], $dir)) {
				$dir = "/$dir";
				$params = [
					"position" => $position,
					"url" => $dir
				];
				$this->db->query("INSERT INTO `kansai_siteHeader_Images` (`url`,`position`) VALUES(:url,:position)",$params);
				return true;
			}else {
				$this->error = 'Не удалось загрузить изображение';
				return false;
			}
		}else {
			$this->error = 'Поддерживаются изображения форматов (png,jpg,jpeg)';
			return false;
		}
	}

	public function changeposition ($post) {
		foreach ($post as $key => $value) {
			$posterID = (int) $key;
			$position = (int) $value;
		}

		$params = [
			"poster_id" => $posterID
		];

		if ($this->db->rowCount("SELECT `id` FROM `kansai_siteHeader_Images` WHERE `id` = :poster_id", $params)) {

			$last_position = $this->db->rowCount("SELECT `id` FROM `kansai_siteHeader_Images`");
			if ($position <= $last_position) {
				$params["position"] = $position;

				$this->db->query("UPDATE `kansai_siteHeader_Images` SET `position` = :position WHERE `id` = :poster_id", $params);
				return true;
			}else
				$this->error = "Порядковый номер не может быть бельше, чем кол-во постеров. Всего постеров - $last_position";
		}else
			$this->error = "Хммм, что-то не так, попробуйте позже";

		return false;
	}

	public function all_users_count () {
		return $this->db->rowCount("SELECT `id` FROM `kansai_accounts`");
	}

	public function dashboard_get_last_10_cats () {
		return $this->db->row("SELECT `tag`, `created_at` FROM `kansai_tags` ORDER BY `created_at` DESC, `id` DESC LIMIT 10");
	}

	public function dashboard_get_last_50_cats () {
		$comments = $this->db->row("SELECT `userid`, `postid`, `text`, `date` FROM `kansai_posts_comments` ORDER BY `date` DESC, `id` DESC LIMIT 50");

		$result = [];

		foreach ($comments as $comment) {
			$params = [
				"userid" => $comment['userid']
			];
			$user = $this->db->row("SELECT `login`, `ugroup` FROM `kansai_accounts` WHERE `id` = :userid", $params)[0];
			$comment['user'] = $user;

			$params = [
				"postid" => $comment['postid']
			];
			$post = $this->db->row("SELECT `url`, `title` FROM `kansai_posts` WHERE `id` = :postid", $params)[0];
			$comment['post'] = $post;
			$result[] = $comment;
		}
		return $result;
	}

	public function get_activity_static () {
		$day_of_week = date('N');
		return $this->db->row("SELECT `count` FROM `kansai_dashboard_statistic` LIMIT $day_of_week");
	}

	public function dashboard_get_last_10_posts () {
		return $this->db->row("SELECT `title`, `url`,`updated_at` FROM `kansai_posts` ORDER BY `updated_at` DESC, `id` DESC LIMIT 10");
	}

	public function all_posts_count () {
		return $this->db->rowCount("SELECT `id` FROM `kansai_posts`");
	}

	public function getPosts ($currentPage, $perPage) {
		$sql = "SELECT * FROM `kansai_posts` ORDER BY `title` ASC";
		$posts = $this->createPagination($sql, $currentPage, $perPage);
		return $posts;
	}

	public function getSeriesOfPost ($postid) {
		$params = [
			'postid' => $postid
		];
		$sql = "SELECT * FROM `kansai_posts_series` WHERE `postid` = :postid ORDER BY `number`";
		$series = $this->db->row($sql, $params);
		return $series;
	}

	public function addSerie ($post) {
		$totalseries = $this->getPost($_POST['postid'])['totalseries'];
		$last_serie = @end($this->getSeriesOfPost($post['postid']))['number'] + 1;
		$post['number'] = (int) $post['number'];
		if ($post['number'] > $last_serie) {
			$this->error = "Последняя серия имела порядковый номер \"". ($last_serie - 1). "\" - номер новой не может больше 1, чем номер последней";
		}else if ( $post['number'] < 0 ) {
			$this->error = "Минимальный порядковый номер = 0";
		}else if ( $post['number'] > $totalseries ) {
			$this->error = "Порядковый номер серии превышает указанную в посте максимальную кол-во серий";
		}else {
			$all_series = $this->getSeriesOfPost($_POST['postid']);
			$array_of_numbers = [];
			foreach ($all_series as $number_of_aleady_serie) {
				$array_of_numbers[] = $number_of_aleady_serie['number'];
			}
			if (in_array($post['number'], $array_of_numbers)) {
				$this->error = "Серия с таким номером уже существует";
			}else {
				$isFrame = isset($post['isFrame']) ? 0 : 1;
				$url = isset($post['urlToVideo']) ? $post['urlToVideo'] : false;
				if ($url) {
					$params = [
						"postid" => $_POST['postid'],
						"isframe" => $isFrame,
						"url" => $url,
						"number" => $post['number']
					];
					$this->db->query("INSERT INTO `kansai_posts_series` (`postid`,`isframe`,`url`,`number`) VALUES(:postid,:isframe,:url,:number)", $params);
					$param = [
						"id" => $_POST['postid']
					];
					$this->db->query("UPDATE `kansai_posts` SET `updated_at` = NOW() WHERE `id` = :id", $param);
					return true;
				}else {
					$this->error = "Укажите путь до фрейма или видео";
				}
			}
		}

		return false;
	}

	public function updateSerie ($post) {
		$totalseries = $this->getPost($_POST['postid'])['totalseries'];
		$last_serie = @end($this->getSeriesOfPost($post['postid']))['number'] + 1;
		$post['number'] = (int) $post['number'];
		if ($post['number'] > $last_serie) {
			$this->error = "Порядковый номер серии не может превышать диапозон существующих номеров больше, чем на 1";
		}else if ( $post['number'] < 0 ) {
			$this->error = "Минимальный порядковый номер = 0";
		}else if ( $post['number'] > $totalseries ) {
			$this->error = "Порядковый номер серии превышает указанную в посте максимальную кол-во серий";
		}else {
			$isFrame = isset($post['isFrame']) ? 0 : 1;
			$url = isset($post['urlToVideo']) ? $post['urlToVideo'] : false;
			if ($url) {
				$params = [
					"id" => $post['serieid'],
					"isframe" => $isFrame,
					"url" => $url,
					"number" => $post['number']
				];
				$this->db->query("UPDATE `kansai_posts_series` SET `isframe` = :isframe, `url` = :url, `number` = :number WHERE `id` = :id", $params);
				$param = [
					"id" => $_POST['postid']
				];
				$this->db->query("UPDATE `kansai_posts` SET `updated_at` = NOW() WHERE `id` = :id", $param);
				return true;
			}else {
				$this->error = "Укажите путь до фрейма или видео";
			}
		}

		return false;
	}

	public function checkIssetSerie ($serieid) {
		$param = [
			"id" => $serieid
		];
		return $this->db->row("SELECT `id` FROM `kansai_posts_series` WHERE `id` = :id", $param);
	}

	public function deleteSerie ($serieid) {
		$param = [
			"id" => $serieid
		];
		$this->db->query("DELETE FROM `kansai_posts_series` WHERE `id` = :id", $param);
	}

	public function getPost ($id) {

		$params = [
			'postid' => $id
		];
		$sql = "SELECT * FROM `kansai_posts` WHERE `id` = :postid";
		$post = $this->db->row($sql, $params);

		if (!empty($post)) {
			$post = $post[0];

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

			$post['cats'] = $cats;
			$post['torrent'] = json_decode($post['torrent'], true);

			return $post;
		}else {
			return false;
		}
	}

	public function getAllCats () {
		$result = $this->db->row("SELECT * FROM `kansai_tags` ORDER BY `tag`");
		return $result;
	}

	public function removeTag ($id) {
		$params = [
			"id" => $id
		];
		$this->db->query("DELETE FROM `kansai_posts_tags` WHERE `tagid` = :id",$params);
		$this->db->query("DELETE FROM `kansai_tags` WHERE `id` = :id",$params);
		return true;
	}

	public function hidePost ( $post_id ) {
		$params = [
			"post_id" => $post_id
		];
		$this->db->query("UPDATE `kansai_posts` SET `isHidden` = 1 WHERE `id` = :post_id",$params);
		return true;
	}

	public function showPost ( $post_id ) {
		$params = [
			"post_id" => $post_id
		];
		$this->db->query("UPDATE `kansai_posts` SET `isHidden` = 0 WHERE `id` = :post_id",$params);
		return true;
	}


	public function uploadPoster ($file, $postId) {
		$image = $file['poster'];
		$current_format = @end(explode(".", $image['name']));
		$formats = [
			"jpg","jpeg","png"
		];
		if ( in_array($current_format, $formats)){
			$dir = "public/images/posters/".$postId."-".time().".$current_format";
			if (move_uploaded_file($image['tmp_name'], $dir)) {
				$dir = "/$dir";
				$params = [
					"id" => $postId
				];

				$old_poster = $this->db->column("SELECT `poster` FROM `kansai_posts` WHERE `id` = :id", $params);
				if (isset($old_poster)) {
					unlink(substr($old_poster, 1));
				}
				$params["poster"] = $dir;
				$this->db->query("UPDATE `kansai_posts` SET `poster` = :poster, `updated_at` = NOW() WHERE `id` = :id",$params);
				return true;
			}else {
				$this->error = 'Не удалось загрузить изображение';
				return false;
			}
		}else {
			$this->error = 'Поддерживаются изображения форматов (png,jpg,jpeg)';
			return false;
		}
	}

	public function updatePost ( $post, $post_id ) {
		$post['season'] = isset($post['season']) ? $post['season'] : 0;
		$params = [
			'id' => $post_id,
			'title' => $post['title'],
			'original_title' => $post['original_title'],
			'totalseries' => $post['totalseries'],
			'description' => $post['description'],
			'season' => (int) $post['season'],
		];

		$json_inputs = [];
		if (!empty($post['oldNewInputs'])) {
			foreach ($post['oldNewInputs'] as $input) {
				if ( isset($input['key']) && isset($input['value']) ) {
					if ($input['key'] && $input['value']) {
						$json_inputs[] = $input;
					}
				}
			}
		}

		if (!empty($post['newinput'])) {
			foreach ($post['newinput'] as $input) {
				if ( isset($input['key']) && isset($input['value']) ) {
					if ($input['key'] && $input['value']) {
						$json_inputs[] = $input;
					}
				}
			}
		}


		$json_inputs = json_encode($json_inputs);

		$params['additional'] = $json_inputs;

		// UPDATING

		$this->db->query("UPDATE `kansai_posts` SET `title` = :title, `original_title` = :original_title, `totalseries` = :totalseries, `description` = :description, `season` = :season, `additional` = :additional, `updated_at` = NOW() WHERE `id` = :id", $params);

		$only_post = [
			"postid" => $post_id
		];

		$this->db->query("DELETE FROM `kansai_posts_tags` WHERE `postid` = :postid", $only_post);

		$param = [
			"postid" => $post_id,
			"tagid" => 0,
		];
		if (!empty($post['tag'])) {
			foreach ($post['tag'] as $key => $value) {
				$param['tagid'] = $key;
				$this->db->query("INSERT INTO `kansai_posts_tags` (`postid`,`tagid`) VALUES(:postid,:tagid)", $param);
			}
		}

		return true;
	}

	public function addPost ( $post, $files ) {
		$post['season'] = isset($post['season']) ? $post['season'] : 0;
		$params = [
			'title' => $post['title'],
			'original_title' => $post['original_title'],
			'totalseries' => $post['totalseries'],
			'description' => $post['description'],
			'season' => (int) $post['season'],
		];

		$json_inputs = [];
		if (!empty($post['newinput'])) {
			foreach ($post['newinput'] as $input) {
				if ( isset($input['key']) && isset($input['value']) ) {
					if ($input['key'] && $input['value']) {
						$json_inputs[] = $input;
					}
				}
			}
		}


		$json_inputs = json_encode($json_inputs);

		$params['additional'] = $json_inputs;

		$params['url'] = url_slug($post['title'], array('transliterate' => true));

		if (!empty($this->db->column("SELECT `id` FROM `kansai_posts` WHERE `url` = '$params[url]'")))
			$params['url'].='-'.time();
		

		$this->db->query("INSERT INTO `kansai_posts` (`id`, `title`, `original_title`,`season`,`totalseries`, `rating`, `torrent`, `poster`, `description`, `updated_at`, `views`, `isHidden`, `url`, `additional`) VALUES (NULL, :title, :original_title, :season, :totalseries, '0', '', '', :description, '', '0', '0', :url, :additional)", $params);

		$post_id = $this->db->lastInsertId();
		if ( $this->uploadPoster($files,$post_id) ){
			$param = [
				"postid" => $post_id,
				"tagid" => 0,
			];
			if (!empty($post['tag'])) {
				foreach ($post['tag'] as $key => $value) {
					$param['tagid'] = $key;
					$this->db->query("INSERT INTO `kansai_posts_tags` (`postid`,`tagid`) VALUES(:postid,:tagid)", $param);
				}
			}
			$this->error = 'catalog/item/'.$params['url'];
			return true;
		}else {
			return false;
		}
	}

	public function validateTag ($tag) {
		$tag = trim($tag);
		if ( strlen($tag) < 2 && strlen($tag) > 30 ) {
			$this->error = "Длина категории должа быть больше 1-го символа и менее 31";
			return false;
		}else
			return true;
	}

	public function addTag ($tag) {
		$params = [
			"tag" => $tag
		];

		$this->db->query("INSERT INTO `kansai_tags` (`tag`) VALUES(:tag)",$params);
		return true;
	}

	public function getUsersByPer ($currentPage, $perPage) {
		$id = $_SESSION['account']['id'];
		$sql = "SELECT `avatar`,`email`,`login`,`ugroup` FROM `kansai_accounts` WHERE `id` <> $id ORDER BY `login`";
		$users = $this->createPagination($sql, $currentPage, $perPage);
		return $users;
	}

	public function validate ($input, $post) {
		$rules = [
			'email' => [
				'pattern' => '#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
				'message' => 'E-mail адрес указан неверно',
			],
			'login' => [
				'pattern' => '#^[a-z0-9]{3,15}$#',
				'message' => 'Логин указан неверно (разрешены только латинские буквы и цифры от 3 до 15 символов',
			],
			'password' => [
				'pattern' => '#^[A-z0-9]{7,30}$#',
				'message' => 'Пароль указан неверно (разрешены только латинские буквы и цифры от 7 до 30 символов',
			],
			'repeat_pass' => [
				'pattern' => '#^[A-z0-9]{7,30}$#',
				'message' => 'Пароль указан неверно (разрешены только латинские буквы и цифры от 7 до 30 символов',
			],
		];

		foreach ($input as $val) {
			if (!isset($post[$val]) or !preg_match($rules[$val]['pattern'], $post[$val])) {
				$this->error = $rules[$val]['message'];
				return false;
			}
		}
		return true;
	}

	public function checkEmailExists ($email) {
		$params = ["email" => $email];
		if ($this->db->column("SELECT `id` FROM `kansai_accounts` WHERE `email` = :email", $params)) {
			$this->error = "Этот E-mail уже используется";
			return false;
		}
		return true;
	}

	public function checkLoginExists ($login) {
		$params = ["login" => $login];
		if ($this->db->column("SELECT `id` FROM `kansai_accounts` WHERE `login` = :login", $params)) {
			$this->error = "Этот логин уже используется";
			return false;
		}
		return true;
	}

	public function setAdmin ($login) {
		$params = ["login" => $login];
		$this->db->query("UPDATE `kansai_accounts` SET `ugroup` = 2 WHERE `login` = :login",$params);
		return true;
	}

	public function deleteAdmin ($login) {
		$params = ["login" => $login];
		$this->db->query("UPDATE `kansai_accounts` SET `ugroup` = 1 WHERE `login` = :login",$params);
		return true;
	}

	public function createNewUser ($post) {

		$params = [
			'id' => '',
			'email' => $post['email'],
			'login' => $post['login'],
			'password' => password_hash($post['password'], PASSWORD_BCRYPT),
			'token' => '',
			'status' => 1
		];

		$this->db->query("INSERT INTO `kansai_accounts` (`id`,`email`,`login`,`password`,`token`,`status`) VALUES(:id,:email,:login,:password,:token,:status)",$params);
		return true;
	}

	public function updateTorrents($postid, $torrent) {
		$params = [
			'postid' => $postid,
			'torrent' => $torrent,
		];
		
		return $this->db->query("UPDATE `kansai_posts` SET `torrent` = :torrent WHERE `id` = :postid", $params);
	}
}