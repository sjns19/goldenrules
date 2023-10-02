<?php

class User {
	public $id;
	public $uid_token;
	public $first_name;
	public $last_name;
	public $email;
	public $temporary_email;
	public $username;
	public $password;
	public $avatar_color;
	public $session;
	public $ip;
	public $mentor_id;
	public $last_logged_ip;
	public $email_verified;
	public $paid_membership;
	public $email_verification_type;
	public $avatar_url;
	public $is_admin;

	public const EMAIL_VERIFICATION_TYPE_REGISTRATION = 0;
	public const EMAIL_VERIFICATION_TYPE_RESEND = 1;
	
	private $db_connection;
	private $db_table = 'grt_users';

	public function __construct($db_connection) {
		$this->db_connection = $db_connection;
	}

	public function Create(): bool {
		$query = '
			INSERT INTO 
				' . $this->db_table . ' 
			SET 
				uid_token=:token,
				first_name=:firstname,
				last_name=:lastname,
				email=:email,
				password=:password,
				username=:username,
				joined_date_unix=:unix,
				avatar_color=:color,
				ip=:ip
			';

		$params = [
			'token' => $this->uid_token,
			'firstname' => $this->first_name,
			'lastname' => $this->last_name,
			'email' => $this->email,
			'password' => $this->password,
			'username' => $this->username,
			'unix' => $this->joined_date_unix,
			'color' => $this->avatar_color,
			'ip' => $this->ip
		];

		return ($this->db_connection->prepare($query)->execute($params)) ? true : false;
	}

	public function isTaken(string $data, string $check_for): bool {
    $query = '
			SELECT 
				email
			FROM 
				' . $this->db_table . ' 
			WHERE 
				' . $check_for . '=? 
			LIMIT 1';
    
		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute([$data]) && $stmt->rowCount()) ? true : false;
	}

	public function getList(): array {
		$data = [];
		$query = '
			SELECT 
				CONCAT(first_name, " ", last_name) AS name,
				uid_token
			FROM 
				' . $this->db_table . ' 
			ORDER BY 
				first_name 
			ASC
		';

		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute() && $stmt->rowCount()) {
			$data = $stmt->fetchAll();
		}
		
		return $data;
	}

	public function getMentors(): array {
		$data = [];
		$query = '
			SELECT 
				id,
				CONCAT(first_name, " ", last_name) AS name,
				LEFT(first_name,1) AS first_letter_of_name,
				username,
				avatar_color,
				avatar_url 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				is_admin=1 
			AND 
				is_mentor=1 
			ORDER BY 
				first_name 
			DESC
		';

		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute() && $stmt->rowCount()) {
			$data = $stmt->fetchAll();
		}
		
		return $data;
	}
	
  public function getTemporaryData(string $email_username_token): array {
		$data_arr = [];
		$data_arr['data'] = [];
    $query = '
			SELECT 
				id,
				email,
				username,
				is_admin,
				password 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				email=? 
			OR 
				username=? 
			OR 
				uid_token=? 
			LIMIT 1';

		$stmt = $this->db_connection->prepare($query);
		
		if ($stmt->execute([$email_username_token, $email_username_token, $email_username_token])) {
			if ($stmt->rowCount() > 0) {
				$data_arr['data'] = $stmt->fetch();
			}
		}

		return $data_arr;
	}

	public function getDataById($id): array {
		$data = [];
		$query = '
			SELECT 
				id,
				CONCAT(first_name, " ", last_name) AS name,
				LEFT(first_name,1) AS first_letter,
				first_name,
				last_name,
				avatar_color,
				email,
				username,
				uid_token,
				joined_date_unix,
				email_verified,
				paid_membership,
				is_admin,
				is_mentor,
				avatar_url,
				ip,
				mentor_id,
				last_logged_ip,
				last_active_unix 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				id=:id 
			OR 
				uid_token=:id 
			LIMIT 1';

		$params = [
			'id' => $id,
			'id' => $id
		];

		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute($params) && $stmt->rowCount()) {
			$data = $stmt->fetch();
		}
		
		return $data;
	}

	public function getDataByEmail(string $email): array {
		$data_arr = [];
		$data_arr['data'] = [];
		$query = '
			SELECT 
				uid_token,
				first_name,
				last_name 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				email=:email 
			LIMIT 1';

		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute(['email' => $email])) {
			if ($stmt->rowCount() > 0) {
				$data_arr['data'] = $stmt->fetch();
			}
		}

		return $data_arr;
	}

	public function assignMentor(): bool {
		$query = '
			UPDATE 
				' . $this->db_table . ' 
			SET 
				mentor_id=:mentor_id 
			WHERE 
				id=:id
		';

		$params = [
			'mentor_id' => $this->mentor_id,
			'id' => $this->id
		];

		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute($params)) ? true : false;
	}

	public function removeMentor(): bool {
		$query = '
			UPDATE 
				' . $this->db_table . ' 
			SET 
				mentor_id=0 
			WHERE 
				id=:id
		';

		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute(['id' => $this->id])) ? true : false;
	}

	public function getStudents(int $id): array {
		$data = [];
		$query = '
			SELECT 
				id,
				CONCAT(first_name, " ", last_name) AS name,
				LEFT(first_name,1) AS first_letter,
				avatar_color,
				email 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				mentor_id=:id 
			LIMIT 
				1
		';

		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute(['id' => $id]) && $stmt->rowCount()) {
			$data = $stmt->fetchAll();
		}
		
		return $data;
	}

	public function updateLatestLoginState(): bool {
		$query = '
			UPDATE 
				' . $this->db_table . ' 
			SET 
				session=:session,
				last_logged_ip=:ip,
				last_active_unix=:unix 
			WHERE 
				id=:id
		';

		$params = [
			'session' => $this->session,
			'ip' => $this->last_logged_ip,
			'unix' => $this->last_active_unix,
			'id' => $this->id
		];

		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute($params)) ? true : false;
	}

	public function removeActiveSession(): bool {
		$query = '
			UPDATE 
				' . $this->db_table . ' 
			SET 
				session=null
			WHERE 
				id=:id
		';

		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute(['id' => $this->id])) ? true : false;
	}

	public function updatePassword(): bool {		
		$query = '
			UPDATE 
				' . $this->db_table . ' 
			SET 
				password=:password 
			WHERE 
				uid_token=:token 
			OR 
				id=:id
		';

		$params = [
			'password' => $this->password,
			'token' => $this->uid_token,
			'id' => $this->id
		];

		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute($params)) ? true : false;
	}

	public function updateEmail(bool $direct = false) {
		if (!$direct) {
			$email_arr = $this->getUserEmails();
			$query = '
				UPDATE 
					' . $this->db_table . ' 
				SET 
					email=temporary_email,
					temporary_email=null 
				WHERE 
					uid_token=:token
			';

			$params = [
				'token' => $this->uid_token,
				'id' => $this->id
			];
	
			$stmt = $this->db_connection->prepare($query);
	
			if ($stmt->execute(['token' => $this->uid_token])) {
				if ($this->isLoggedIn()) {
					$_SESSION['grt_user']['email'] = $email_arr['data']['temporary_email'];
				}
			}
			return $email_arr;
		} else {
			$query = '
				UPDATE 
					' . $this->db_table . ' 
				SET 
					email=:email 
				WHERE 
					id=:id
			';

			$params = [
				'email' => $this->email,
				'id' => $this->id
			];

			$stmt = $this->db_connection->prepare($query);
			return ($stmt->execute($params)) ? true : false;
		}
	}

	public function requestEmailChange() {
		$query = '
			UPDATE 
				' . $this->db_table . ' 
			SET 
				temporary_email=:email 
			WHERE 
				uid_token=:token
		';

		$params = [
			'email' => $this->temporary_email,
			'token' => $this->uid_token
		];

		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute($params)) ? true : false;
	}

	private function getUserEmails(): array {
		$data_arr = [];
		$data_arr['data'] = [];
		$query = '
			SELECT 
				email,
				temporary_email 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				uid_token=:token 
			LIMIT 
				1
		';

		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute(['token' => $this->uid_token])) {
			if ($stmt->rowCount() > 0) {
				$data_arr['data'] = $stmt->fetch();
			}
		}
		return $data_arr;
	}

	public function getTotal(): int {
		$count = 0;
		$query = '
			SELECT 
				COUNT(username) AS total_users 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				email_verified=1
		';
		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute()) {
			$rows = $stmt->fetch();

			if ($rows > 0) {
				$count = $rows['total_users'];
			}
		}
		return $count;
	}

	public function getTotalStudents(int $id): int {
		$count = 0;
		$query = '
			SELECT 
				COUNT(username) AS total_students 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				mentor_id=:id
		';
		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute(['id' => $id])) {
			$rows = $stmt->fetch();

			if ($rows > 0) {
				$count = $rows['total_students'];
			}
		}
		return $count;
	}

	public function setPaidMembership(): bool {
		$query = '
			UPDATE 
				' . $this->db_table . ' 
			SET 
				paid_membership=:paid_membership 
			WHERE 
				id=:id
		';

		$params = [
			'paid_membership' => $this->paid_membership,
			'id' => $this->id
		];

		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute($params)) ? true : false;
	}

	public function updateEmailVerification(): bool {
    $query = '
			UPDATE 
				' . $this->db_table . ' 
			SET 
				email_verified=1 
			WHERE 
				uid_token=:token';

		$stmt = $this->db_connection->prepare($query);
		
		if ($stmt->execute(['token' => $this->uid_token])) {
			if ($this->isLoggedIn()) {
				$_SESSION['grt_user']['email_verified'] = true;
			}
			return true;
		}
		return false;
	}
	
	public function sendVerificationMail(): bool {
		require_once '../../defines.php';

		$request = new PageRequest($this->db_connection);

		$user_token = $this->uid_token;
		$request_data = $request->getData($user_token);
		$page_token = null;
		$full_name = $this->first_name . ' ' . $this->last_name;
		
		if (empty($request_data['data'])) {
			if ($this->email_verification_type === self::EMAIL_VERIFICATION_TYPE_REGISTRATION) {
				$page_token = App::getRandomString(30);
				$url = SITE_URL . '/user/verify-email/?_vt=' . $page_token . '&_vft=' . $user_token;
			} else if ($this->email_verification_type === self::EMAIL_VERIFICATION_TYPE_RESEND) {
				return false;
			}
		} else {
			$url = SITE_URL . '/user/verify-email/?_vt=' . $request_data['data']['token'] . '&_vft=' . $request_data['data']['requested_for'];
		}

		$send_data = [
			'from_mail' => SERVER_MAIL_ADDRESS,
			'from_name' => SITE_NAME,
			'to_mail' => $this->email,
			'to_name' => $full_name
		];

		$mail_data = [
			'@to_name' => $this->first_name,
			'@verification_url' => $url,
			'@support_email' => SITE_SUPPORT_EMAIL
		];

		$mail_contents = file_get_contents('../templates/mails/verify-email.mailtemplate.html');
		$mail_contents = str_replace(array_keys($mail_data), array_values($mail_data), $mail_contents);

		if (!is_null($page_token)) {
			$request->requested_for = $user_token;
			$request->timestamp = $this->joined_date_unix;
			$request->token = $page_token;
			$request->type = PageRequest::REQUEST_TYPE_VERIFY_EMAIL;
			$request->Create();	
		}

		return (App::sendMail($send_data, 'Verify your email address', $mail_contents)) ? true : false;
	}

	public static function isAdminPageAccessible(): bool {
		if (!isset($_SESSION['admin']['logged_in'])) {
			header('location: /admin/');
		}
		return true;
	}

	public static function isLoggedIn(): bool {
		return isset($_SESSION['grt_user']['logged_in']);
	}

	public function updateAvatar(): bool {
		$query = '
			UPDATE 
				'. $this->db_table .' 
			SET 
				avatar_url=:avatar
			WHERE 
				id=:id';

		$params = [
			'avatar' => $this->avatar_url,
			'id' => $this->id
		];
		
		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute($params)) ? true : false; 
	}

	public function uploadAvatarFile(string $new_avatar, string $current_avatar, string $admin_name, int $admin_id): string {
		$avatar_path = '../../media/avatars/';
		$current_avatar_file = $avatar_path . $current_avatar;

		if ($current_avatar !== 'none' && file_exists($current_avatar_file)) {
			unlink($current_avatar_file);
		}

		$avatar_url = $this->generateAvatarURL($admin_name, $admin_id, time());
		$this->compressAvatar($new_avatar, $avatar_path . $avatar_url, 1);
		return $avatar_url;
	}

	public function removeAvatarFile(string $file) {
		$avatar_path = '../../media/avatars/';
		$current_avatar_file = $avatar_path . $file;

		if (file_exists($current_avatar_file)) {
      unlink($current_avatar_file);
    }
	}

	private function generateAvatarURL(string $user_fullname, int $id, int $unix) {
		$separator = '-';
		$quote = preg_quote($separator, '#');

		$trans = [
		  '&.+?;' => '',
		  '[^\w\d _-]' => '',
		  '\s+' => $separator,
		  '(' . $quote . ')+' => $separator
		];
		
		$user_fullname = strip_tags($user_fullname);

		foreach ($trans as $key => $val){
			$user_fullname = preg_replace('#' . $key . '#i' . (''), $val, $user_fullname);
		}

		$user_fullname = strtolower($user_fullname);
		$dashed_name = trim(trim($user_fullname, $separator));
    $hash = md5('admin_id-' . $id);
    $unix_hash = hash('adler32', $unix);
    
		return 'avt-' . $dashed_name . '-' . $hash . '-' . $unix_hash . '.png';
	}

	private function compressAvatar(string $src, string $destination, int $quality) {
    $img_size = getimagesize($src);

    switch ($img_size['mime']) {
      case 'image/jpeg': 
        $image = imagecreatefromjpeg($src); 
        imagejpeg($image, $destination, $quality);
        break; 
      case 'image/png': 
        $image = imagecreatefrompng($src); 
        imagepng($image, $destination, $quality);
        break; 
      case 'image/gif': 
        $image = imagecreatefromgif($src); 
        imagegif($image, $destination, $quality);
        break; 
      default: 
        $image = imagecreatefromjpeg($src); 
				imagejpeg($image, $destination, $quality);
				break;
    }
    
		return $destination;
	}
}