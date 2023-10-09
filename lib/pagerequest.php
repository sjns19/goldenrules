<?php

class PageRequest {
	public $requested_for;
	public $timestamp;
	public $token;
	public $type;

	public const REQUEST_TYPE_RESET_PASSWORD = 'RESET_PASSWORD';
	public const REQUEST_TYPE_CHANGE_EMAIL = 'CHANGE_EMAIL';
	public const REQUEST_TYPE_VERIFY_EMAIL = 'VERIFY_EMAIL';

	private $db_connection;
	private $db_table = 'grt_temp_pages';
  
	function __construct(object $db_connection) {
		$this->db_connection = $db_connection;
	}

	public function Create(): bool {
		$query = '
			INSERT INTO 
				' . $this->db_table . ' 
			SET 
				requested_for=:user_token,
				timestamp=:timestamp,
				token=:token,
				type=:type
		';

		$params = [
			'user_token' => $this->requested_for,
			'timestamp' => $this->timestamp,
			'token' => $this->token,
			'type' => $this->type
		];

		$stmt = $this->db_connection->prepare($query);

		return ($stmt->execute($params)) ? true : false;
	}

	public function Delete(): bool {
		$query = '
			DELETE FROM 
				' . $this->db_table . ' 
			WHERE 
				token=:token
		';

		$stmt = $this->db_connection->prepare($query);
    	
		return $stmt->execute(['token' => $this->token]) ? true : false;
	}

	public function getData(string $token): array {
		$data_arr = [];
		$data_arr['data'] = [];
		$query = '
			SELECT 
				token,
				requested_for,
				timestamp,
				type 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				token=:token 
			OR 
				requested_for=:user_token 
			LIMIT 1
		';

		$params = [
			'token' => $token,
			'user_token' => $token
		];

		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute($params)) {
			if ($stmt->rowCount() > 0) {
				$data_arr['data'] = $stmt->fetch();
			}
		}
		
		return $data_arr;
	}
}