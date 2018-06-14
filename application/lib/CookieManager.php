<?

	/**
	* CookieManager by Abduraxmon Ishmurodov
	* vk - https://vk.com/aishmurodov
	*/

	namespace application\lib;

	class CookieManager
	{

		static public function create($key, $value, $time = false, $path = '/', $domain = 'current', $secure = 0) {
			if ($domain == 'current') 
				$domain = $_SERVER['HTTP_HOST'];
			if (!$time)
				$time = time() + (3600 * 24);
			else 
				$time = time() + $time;
			setcookie($key, $value, $time, $path, $domain, $secure);
			return true;
		}

		static public function read($key) {
			if (isset($_COOKIE[$key])) 
				return $_COOKIE[$key];
			else
				return false;
		}

		static public function delete($key, $value = '', $time = 1, $path = '/', $domain = 'current', $secure = 0) {
			if ($domain == 'current') 
				$domain = $_SERVER['HTTP_HOST'];
			setcookie($key, $value, $time, $path, $domain, $secure);
			return true;
		}

	}
