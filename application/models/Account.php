<?php

namespace application\models;

use application\core\Model;

class Account extends Model {
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
		if (isset($post['repeat_pass']) && $post['repeat_pass'] != $post['password']) {
			$this->error = "Пароли не совподают";
			return false;
		}
		return true;
	}

	public function getPostById ($id) {
		$params = [
			"id" => $id
		];
		return $this->db->row("SELECT `url`,`poster`,`title`,`original_title`,`rating`,`rators` FROM `kansai_posts` WHERE `id` = :id", $params);
	}

	public function validateComment ($post) {
		$item = $post['item'];
		$spoiler = isset($_POST['isSpoiler']) ? 1 : 0;
		$text = htmlspecialchars(trim($post['commentText']));

		if (strlen($text) < 5) 
			$this->error = "Текст комментария должен содержать не менее 5-х символов";
		else if ( empty($this->getPostById($item)) )
			$this->error = "Пост не найден";
		else
			return true;

		return false;
	}

	public function addComment ($post) {
		$item = $post['item'];
		$spoiler = isset($_POST['isSpoiler']) ? 1 : 0;
		$text = htmlspecialchars(trim($post['commentText']));
		$params = [
			"userid" => $_SESSION['account']['id'],
			"postid" => $item,
			"spoiler" => $spoiler,
			"text" => $text,
		];

		$this->db->query("INSERT INTO `kansai_posts_comments` (`userid`,`postid`,`spoiler`,`text`) VALUES(:userid,:postid,:spoiler,:text)",$params);
		
		return $this->getPostById($item)[0]['url'];;
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

	public function checkReferalExists ($login) {
		$params = ["login" => $login];
		return $this->db->column("SELECT `id` FROM `kansai_accounts` WHERE `login` = :login", $params);
	}


	public function checkTokenExists ($token) {
		$params = ["token" => $token];
		if (!$this->db->column("SELECT `id` FROM `kansai_accounts` WHERE `token` = :token", $params)) {
			return false;
		}
		return true;
	}

	public function activate ($token) {
		$params = ["token" => $token];
		$this->db->query("UPDATE `kansai_accounts` SET `status` = 1, `token` = '' WHERE `token` = :token", $params);
	}

	public function createToken () {
		return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 30)), 0, 30);
	}

	public function register ($post) {
		$token = $this->createToken();
		$server = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/account/confirm/'.$token;

		$params = [
			'id' => '',
			'email' => $post['email'],
			'login' => $post['login'],
			'password' => password_hash($post['password'], PASSWORD_BCRYPT),
			'token' => $token,
			'status' => 0
		];

		$this->db->query("INSERT INTO `kansai_accounts` (`id`,`email`,`login`,`password`,`token`,`status`) VALUES(:id,:email,:login,:password,:token,:status)",$params);
		if ( $this->sendSmtpMail($post['email'], 'Register', 'Confirm code: '.$server) === true )
			return true;
		else 
			return false;
	}

	public function checkData ($email, $password) {
		$params = ["email" => $email];
		$hash = $this->db->column("SELECT `password` FROM `kansai_accounts` WHERE `email` = :email", $params);

		if (!$hash || !password_verify($password, $hash)) {
			return false;
		}
		return true;
	}

	public function checkStatus ( $type, $data ) {
		$params = [
			$type => $data
		];
		$status = $this->db->column("SELECT `status` FROM `kansai_accounts` WHERE `$type` = :$type", $params);
		if ($status != 1) {
			$this->error = "Аккаунт ожидает подтверждения по E-mail";
			return false;
		}
		return true;
	}

	public function authorize ($email) {
		$params = ["email" => $email];
		$row = $this->db->row("SELECT * FROM `kansai_accounts` WHERE `email` = :email",$params)[0];

		$_SESSION['account'] = $row;
		
		$_SESSION['account']['avatar'] = ($_SESSION['account']['avatar']) ? $_SESSION['account']['avatar'] : "/public/images/non-avatar.png";
		if ( $_SESSION['account']['ugroup'] > 1 )
			$_SESSION['admin'] = 1;

		if ( in_array($_SESSION['account']['login'], $this->superadmins) )
			$_SESSION['superAdmin'] = 1;
	}

	public function logout () {
		session_unset($_SESSION['account']);
		return true;
	}

	public function recovery ($post) {
		$token = $this->createToken();
		$server = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/account/reset/'.$token;

		$params = [
			'email' => $post['email'],
			'token' => $token,
		];

		$this->db->query("UPDATE `kansai_accounts` SET `token` = :token WHERE `email` = :email",$params);

		if ( $this->sendSmtpMail($post['email'], 'Recovery', 'Confirm code: '.$server) === true )
			return true;
		else 
			return false;
	}

	public function reset ($token) {
		$new_pass = $this->createToken();
		$params = [
			"token" => $token
		];
		
		$email = $this->db->column("SELECT `email` FROM `kansai_accounts` WHERE `token` = :token", $params);

		$params['password'] = password_hash($new_pass, PASSWORD_BCRYPT);

		$this->db->query("UPDATE `kansai_accounts` SET `password` = :password, `token` = '' WHERE `token` = :token", $params);

		$this->sendSmtpMail($email, 'Recovery', "Password was successfully reset.\r\nAnd now it is - $new_pass");
		return $new_pass;
	}

	public function validateAvatar($file) {
		$image = $file['avatar_input'];
		$formats = ["png","jpg","jpeg"];
		$current_format = @end(explode(".", $image['name']));
		if (in_array($current_format, $formats)) {
			if ($image['size'] <= 5*1048576) {
				if (is_uploaded_file($image['tmp_name'])) {
					return true;
				}
			}else {
				$this->error = 'Максимальный размер аватара должен быть меньше 5 мегабайт';
				return false;
			}
		}else {
			$this->error = 'Изображение не соответствует форматам "png", "jpg", "jpeg"';
			return false;
		}
	}

	public function updateAvatar ($file) {
		$image = $file['avatar_input'];
		$current_format = @end(explode(".", $image['name']));
		$param = [
			"id" => $_SESSION['account']['id'],
		];
		$previous_avatar = $this->db->column("SELECT `avatar` FROM `kansai_accounts` WHERE `id` = :id", $param);
		if (isset($previous_avatar)) {
			$previous_avatar = mb_substr($previous_avatar, 1);
			unlink($previous_avatar);
		}
		$dir = "public/images/avatars/".$_SESSION['account']['login']."-".time().".$current_format";
		if (move_uploaded_file($image['tmp_name'], $dir)) {
			$dir = "/$dir";
			$_SESSION['account']['avatar'] = $dir;
			$params = [
				"id" => $_SESSION['account']['id'],
				"avatar" => $dir
			];
			$this->db->query("UPDATE `kansai_accounts` SET `avatar` = :avatar WHERE `id` = :id",$params);
			return true;
		}else {
			$this->error = 'Не удалось загрузить изображение';
			return false;
		}
	}

	public function updateSettings ($post) {
		$params = [
			"id" => $_SESSION['account']['id'],
			"email" => $post['email']
		];
		$sql = "UPDATE `kansai_accounts` SET `email` = :email";
		if (!empty($post['password'])) {
			$params['password'] = password_hash($post['password'], PASSWORD_BCRYPT);
			$sql.= ", `password` = :password";
		}

		$sql.= " WHERE `id` = :id";

		$this->db->query($sql, $params);
		$this->authorize($post['email']);
		return true;
	}

	public function validateRate ($vote) {
		$vote = (int) $vote;
		if ($vote >= 1 && $vote <= 10)
			return true;
		else 
		{
			$this->error = "Оценка дожна быть больше 0 и меньше 11";
			return false;
		}
	}

	public function checkInRate ($item, $vote) {
		$vote = (int) $vote;
		$post = $this->getPostById($item);
		if (!empty($post)) {
			$post = $post[0];
			$rators = explode(',', $post['rators']);

			if (empty($rators) || !in_array($_SESSION['account']['id'], $rators)) {
				return true;
			}else {
				$this->error = "Вы уже голосовали";
			}
		}else
			$this->error = "Пост не найден";

		return false;
	}

	public function doRatePost ($item, $vote) {
		$vote = (int) $vote;
		$post = $this->getPostById($item);
		if (!empty($post)) {
			$post = $post[0];
			$rators = explode(',', $post['rators']);
			
			$rators[] .= $_SESSION['account']['id'];
			$rators_str = '';
			foreach ($rators as $key => $value) {
			  if ($rators_str) {
			    $rators_str .= ",".$value;
			  }else {
			    $rators_str = $value;
			  }
			}

			$rators_exp = explode(',', $rators_str);

			$rating = $post['rating'] + $vote;
			$rating = $rating / count($rators_exp);
			$rating = round($rating, 2);

			$params = [
				"item" => $item,
				"rating" => $rating,
				"rators" => $rators_str,
			];
			$this->db->query("UPDATE `kansai_posts` SET `rating` = :rating, `rators` = :rators WHERE `id` = :item",$params);
		}
	}

	public function all_posts_count_for_favorite () {
		$favorites = json_decode($_SESSION['account']['favorites'], true);
		if (!empty($favorites)) {
			$ids_array = [];
			foreach ($favorites as $favorite) {
				$ids_array[] = $favorite['id'];
			}
			$ids_sql = implode(' OR `id` = ', $ids_array);
			return $this->db->rowCount("SELECT `id` FROM `kansai_posts` WHERE `isHidden` = 0 AND (`id` = $ids_sql)");
		}
		else {
			return 0;
		}
	}

	public function getFavoritePosts ($currentPage, $perPage) {
		$favorites = json_decode($_SESSION['account']['favorites'], true);
		if (!empty($favorites)) {
			$ids_array = [];
			foreach ($favorites as $favorite) {
				$ids_array[] = $favorite['id'];
			}
			$ids_sql = implode(' OR `id` = ', $ids_array);
			
			return $this->createPagination("SELECT `id`,`url`,`poster`,`title`,`original_title` FROM `kansai_posts` WHERE `isHidden` = 0 AND (`id` = $ids_sql)", $currentPage, $perPage);
		}
		else {
			return 0;
		}
	}

	public function get_user_last_serie_for_array ($array) {
		$favorites = json_decode($_SESSION['account']['favorites'], true);

		if (!empty($favorites)) {
			$result = [];

			foreach ($favorites as $favorite) {
				foreach ($array as $arr) {
					if ( $favorite['id'] == $arr['id'] ){

						$arr['last_user_serie'] = $favorite['serie'];
						$result[] = $arr;
					}
				}
			}
			return $result;
		}
		else {
			return $array;
		}
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

	public function removeFavorte ($item) {
		$favorites_result = json_decode($_SESSION['account']['favorites'], true);
		$favorites = json_decode($_SESSION['account']['favorites'], true);

		if (!empty($favorites)) {
			foreach ($favorites as $key => $favorite) {
				if ( $favorite['id'] == $item ){
					unset($favorites_result[$key]);
					$favorites_result = json_encode($favorites_result);
					$params = [
						"id" => $_SESSION['account']['id'],
						"favorites" => $favorites_result
					];
					$_SESSION['account']['favorites'] = $favorites_result;
					return $this->db->query("UPDATE `kansai_accounts` SET `favorites` = :favorites WHERE `id` = :id",$params);
					break;
				}
			}
		}
		else {
			return false;
		}
	}

	public function put_user_last_serie ($item, $serie) {
		$favorites = json_decode($_SESSION['account']['favorites'], true);
		$favorites_result = [];

		if (!empty($favorites)) {
			foreach ($favorites as $favorite) {
				if ( $favorite['id'] == $item ){
					$favorite['serie'] = $serie;
				}
				$favorites_result[] = $favorite;
			}
			$params = [
				"id" => $_SESSION['account']['id'],
				"favorites" => json_encode($favorites_result)
			];
			$_SESSION['account']['favorites'] = json_encode($favorites_result);
			$this->db->query("UPDATE `kansai_accounts` SET `favorites` = :favorites WHERE `id` = :id",$params);
		}

		return json_encode($favorites);
	}

	public function checkInFavorite ($item) {
		$favorites = json_decode($_SESSION['account']['favorites'], true);

		if (!empty($favorites)) {
			foreach ($favorites as $key => $favorite) {
				if ( $favorite['id'] == $item ){
					$this->error = "Этот пост уже находится в вашем списке";
					return true;
					break;
				}
			}
		}
		return false;
	}

	public function addFavorte ($item) {
		if (!empty($this->getPostById($item))) {
			$favorites = json_decode($_SESSION['account']['favorites'], true);
			$favorites[] = [
				"id" => $item,
				"serie" => 1
			];

			$params = [
				"id" => $_SESSION['account']['id'],
				"favorites" => json_encode($favorites)
			];
			$_SESSION['account']['favorites'] = json_encode($favorites);
			$this->db->query("UPDATE `kansai_accounts` SET `favorites` = :favorites WHERE `id` = :id",$params);
			return true;
		}else 
			$this->error = "Пост не найден";

		return false;
	}

}