<?php

class Quotes {
	public $id;
	public $author;
	public $text;

	private $db_connection;
	private $db_table = 'grt_quotes';

	public function __construct(object $db_connection) {
		$this->db_connection = $db_connection;
	}

	public function Create(): bool {
		$query = 'INSERT INTO ' . $this->db_table . ' SET text=:text,author=:author';

		$params = [
			'text' => $this->text,
			'author' => $this->author
		];
		
		return ($this->db_connection->prepare($query)->execute($params)) ? true : false;
	}

	public function Exists(string $quote): bool {
		$query = 'SELECT text FROM ' . $this->db_table . ' WHERE text=:text LIMIT 1';
		$stmt = $this->db_connection->prepare($query);
		
		return ($stmt->execute(['text' => $quote]) && $stmt->rowCount()) ? true : false;
	}

	public function ReadAll(): array {
		$data_arr = [];
		$stmt = $this->db_connection->prepare('SELECT * FROM ' . $this->db_table);
	
		if ($stmt->execute()) {
			$rows = $stmt->rowCount();
			
			if ($rows > 0) {
				$data_arr['data'] = $stmt->fetchAll();
				$data_arr['total'] = $rows;
			}
		}

		return $data_arr;
	}

	public function getRandom(): array {
		$random = [];
		$query = 'SELECT author,text FROM ' . $this->db_table . ' ORDER BY RAND() LIMIT 1';
		$stmt = $this->db_connection->prepare($query);
	
		if ($stmt) {
			if ($stmt->execute() && $stmt->rowCount()) {
				$random = $stmt->fetch();
			}
		}

		return $random;
	}

	public function Delete(): bool {
		$query = 'DELETE FROM ' . $this->db_table . ' WHERE id=:id';
		
		return ($this->db_connection->prepare($query)->execute(['id' => $this->id])) ? true : false;
	}
}