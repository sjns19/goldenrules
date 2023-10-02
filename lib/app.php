<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class App {
	public const MAIL_HOST_ADDRESS = 'xxx';
	public const MAIL_HOST_PASSWORD = 'xxx';
	public const IMAGE_COMPRESS_LEVEL = 50;

	public $props;

	public function __construct() {
		$this->props = [];
	}

	public function __set($key, $value) {
		$this->props[$key] = $value;
	}

	public function __get($key){
		return (array_key_exists($key, $this->props)) ? $this->props[$key] : 0;
	}

	public function render(array $files, string $dir) {
		if (!empty($files)) {
			foreach ($files as $key => $value) {
				$files_in = $dir . $files[$key] . '.template.php';

				if (file_exists($files_in)) {
					define(basename($files_in) . '-included', true);
					include_once $files_in;
				} else {
					throw new Exception('Could not include file ' . $files_in);
				}
			}
		}
	}

	public function pageNotFound() {
		http_response_code(404);
		$title_page_not_found = '404 - Page not found';

		$this->page_data = [
			'title' => $title_page_not_found,
			'description' => 'The following URL might be broken or the page does not exist.',
			'og' => [
				'title' => $title_page_not_found,
				'url' => SITE_URL,
				'image' => SITE_URL . '/src/icons/og_logo.jpg',
				'type' => 'website'
			]
		];
		
		define('HIDE_HEADER_AND_FOOTER', true);
		define('ERR_PAGE_NOT_FOUND', true);
	}

	public static function resizeImage(string $src, int $height, int $width): string {
		return SITE_URL . '/lib/resizer.php?u=' . urlencode($src) . '&h=' . $height . '&w=' . $width;
	}

	public function countUsers(object $connection): int {
		$count = 0;
		$query = '
			SELECT 
				COUNT(username) AS total_users 
			FROM 
				grt_users 
			WHERE 
				email_verified=1';

		$stmt = $connection->prepare($query);

		if ($stmt->execute()) {
			$rows = $stmt->fetch();

			if ($rows > 0) {
				$count = $rows['total_users'];
			}
		}

		return $count;
	}

	public static function getCustomQueryParam(string $param_name): ?string {
		$param_value = null;
		$uri = $_SERVER['REQUEST_URI'];
		$url = parse_url($uri);

		if (array_key_exists('query', $url)) {
			list($path, $qs) = explode('?', $_SERVER['REQUEST_URI'], 2);
			parse_str($qs, $params);

			if (array_key_exists($param_name, $params)) {
				$param_value = $params[$param_name];
			}
		}

		return $param_value;
	}

	public static function getAgoFromUnix(int $unix): string {
		$units = [
			'second', 
			'minute', 
			'hour', 
			'day', 
			'week', 
			'month', 
			'year', 
			'decade'
		];
		$len = [
			'60', 
			'60', 
			'24', 
			'7', 
			'4.35', 
			'12', 
			'10'
		];
		$time = time() - $unix;
		
		for ($i = 0; $time >= $len[$i] && $i < count($len) - 1; ++$i) {
			$time /= $len[$i];
		}

		$time = round($time);
		
		if ($time != 1) {
			$units[$i] .= 's';
		}

		return $time . ' ' . $units[$i] . ' ago';
	}

	public static function strToSEO(string $string): string {
		$separator = '-';
		$quote = preg_quote($separator, '#');

		$trans = [
			'&.+?;' => '',
			'[^\w\d _-]' => '',
			'\s+' => $separator,
			'(' . $quote . ')+' => $separator
		];
		
		$string = strip_tags($string);
		
		foreach ($trans as $key => $val){
			$string = preg_replace('#' . $key . '#i' . (''), $val, $string);
		}
		
		$string = strtolower($string);
		return trim(trim($string, $separator));
	}

	public static function getRandomString(int $len): string {
		$charset = '0123456789abcdfghjkmnpqrstvwxyzABCDFGHJKLMNPQRSTVWXYZ';

		$char_len = strlen($charset);
		$string = '';

		for ($i = 0; $i < $len; $i++) {
			$string .= $charset[rand(0, $char_len - 1)];
		}

		return $string;
	}

	public static function getFirstLetterFromName(string $name): string {
		return substr($name, 0, 1);
	}

	public static function sanitizeArticleTitle(string $title): string {
		$title = filter_var($title, FILTER_SANITIZE_STRING);
		$title = preg_replace('/\s+/', ' ', $title);
		$title = trim($title);
		return ucfirst($title);
	}

	public static function getRandomColor(): string {
		$colors = [
			'#b07937', '#b0a237', '#9e5729',
			'#299e92', '#105e56', '#2c5f99',
			'#64788f', '#5a56a8', '#681e7d',
			'#84668c', '#61053e', '#8f6a81',
			'#998e6a', '#8c6451', '#ad400c',
			'#76874d', '#405904', '#239aa1'
		];

		$key = array_rand($colors);
		return $colors[$key];
	}

	public static function ellipsis(string $string, int $len): string {
		return strlen($string) > $len ? substr($string, 0, $len) . ' ...' : $string;
	}

	public static function sanitizeBody(string $string): string {
		$result = html_entity_decode($string);
		$result = strip_tags($result);
		$result = urldecode($result);
		$result = str_replace('&nbsp;', ' ', $result);
		$result = preg_replace('/ +/', ' ', $result);
		$result = trim($result);

		return $result;
	}

	public static function getClientIP(): string {
		if (isset($_SERVER['REMOTE_ADDR']))
				return $_SERVER['REMOTE_ADDR'];
		if (isset($_SERVER['HTTP_CLIENT_IP']))
				return $_SERVER['HTTP_CLIENT_IP'];
		else if (isset($_SERVER['HTTP_X_FORWARDED']))
				return $_SERVER['HTTP_X_FORWARDED'];
		else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
				return $_SERVER['HTTP_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_FORWARDED']))
				return $_SERVER['HTTP_FORWARDED'];
		else
				return 'Unknown';
	}

	public static function getUserOrigin(string $ip): array {
		$data = [];
		$parse = json_decode(@file_get_contents('http://ip-api.com/json/' . $ip));

		if (isset($parse->country) && isset($parse->city)) {
			$data = [
				'country' => $parse->country,
				'city' => $parse->city,
				'alpha' => strtolower($parse->countryCode)
			];
		}
		return $data;
	}

	public static function sendMail(array $send_data, string $subject, string $contents): bool {
		require_once __DIR__ . '/mailer/PHPMailer.php';
		require_once __DIR__ . '/mailer/Exception.php';
		require_once __DIR__ . '/mailer/SMTP.php';

		$mailer = new PHPMailer();
		$mailer->isSMTP();
		$mailer->Host = 'smtp.gmail.com';
		$mailer->SMTPAuth = true;
		//$mailer->SMTPDebug = 4;
		$mailer->Username = self::MAIL_HOST_ADDRESS;
		$mailer->Password = self::MAIL_HOST_PASSWORD;
		$mailer->SMTPSecure = 'tls';
		$mailer->Port = 587;
		$mailer->SetFrom($send_data['from_mail'], $send_data['from_name']);
		$mailer->addAddress($send_data['to_mail'], $send_data['to_name']); 
		$mailer->isHTML(true);
		$mailer->Subject = $subject;
		$mailer->Body = $contents;
		return $mailer->send() ? true : false;
	}
}