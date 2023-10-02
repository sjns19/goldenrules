<?php

class Database {
	private $db_host;
	private $db_user;
	private $db_name; 
	private $db_pass;
	private $connection;

	public function __construct() {
		$cfg = parse_ini_file('inc/db_config.ini');
		$this->db_host = $cfg['host'];
		$this->db_user = $cfg['username'];
		$this->db_pass = $cfg['password'];
		$this->db_name = $cfg['database'];
	}

	public function connect(): object {
		$this->connection = null;
		try {
			$this->connection = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name, $this->db_user, $this->db_pass);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->connection->query("SET NAMES 'utf8mb4'");
		} catch (PDOException $e) {
			exit('<strong>Error:</strong> There was a problem connecting to the database.');
		}
		return $this->connection;
	}

  public function close() {
  	$this->connection = null;
  }
}