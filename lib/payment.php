<?php

class Payment {
  public $id;
  public $transaction_id;
  public $customer_id;
  public $amount;
  public $date;
  public $status;

  private $db_table = 'grt_payments';
  private $db_table_users = 'grt_users';
  private $db_connection;
  
  public const STATUS_DENIED = 'DENIED';
  public const STATUS_APPROVED = 'APPROVED';
  public const STATUS_PENDING = 'PENDING';

  public const DEFAULT_PAYMENT_CHARGE_AMOUNT = 49999; // 500 USD

  public function __construct(object $db_connection) {
    $this->db_connection = $db_connection;
  }

  public function Create(): bool {
    $query = '
      INSERT INTO 
        ' . $this->db_table . ' 
      SET 
        id=:id,
        transaction_id=:transaction_id,
        customer_id=:customer_id,
        amount=:amount,
        status=:status
    ';

    $params = [
      'id' => $this->id,
      'transaction_id' => $this->transaction_id,
      'customer_id' => $this->customer_id,
      'amount' => $this->amount,
      'status' => $this->status
    ];
    
    return ($this->db_connection->prepare($query)->execute($params)) ? true : false;
  }

  public function getList(): array {
    $data = [];
    $query = '
      SELECT 
        p.id,
        p.transaction_id,
        p.customer_id,
        p.amount,
        p.status,
        p.date,
        CONCAT(c.first_name, " ", c.last_name) AS customer_name,
        c.username AS customer_username,
        c.email AS customer_email 
      FROM 
        ' . $this->db_table . ' p 
      INNER JOIN 
        ' . $this->db_table_users . ' c 
      ON 
        c.id=p.customer_id
    ';

    $stmt = $this->db_connection->prepare($query);

    if ($stmt->execute() && $stmt->rowCount()) {
      $data = $stmt->fetchAll();
    }

    return $data;
  }

  public function setStatus(): bool {
    $query = '
      UPDATE 
        ' . $this->db_table . ' 
      SET 
        status=:status 
      WHERE 
        id=:id
    ';

    $params = [
      'status' => $this->status,
      'id' => $this->id
    ];
    
    return ($this->db_connection->prepare($query)->execute($params)) ? true : false;
  }

  public function countPending(): int {
    $count = 0;
		$query = '
			SELECT 
				COUNT(customer_id) AS total_payments 
			FROM 
				' . $this->db_table . ' 
			WHERE 
				status="pending"
		';
		$stmt = $this->db_connection->prepare($query);

		if ($stmt->execute()) {
			$rows = $stmt->fetch();
			if ($rows > 0) {
				$count = $rows['total_payments'];
			}
		}
		return $count;
  }

  public function hasAlreadyPaid(int $user_id): bool {
		$query = '
			SELECT 
				customer_id 
			FROM 
				' . $this->db_table . ' 
			WHERE 
        customer_id=:user_id 
      AND 
        status <> "DENIED" 
      LIMIT 
        1
		';

		$stmt = $this->db_connection->prepare($query);
		return ($stmt->execute(['user_id' => $user_id]) && $stmt->rowCount()) ? true : false;
  }

  public function generateTransactionID(): int {
    $numbers = '1234567890';
		$num_len = strlen($numbers);
		$final = '';

		for ($i = 0; $i < 5; $i++) {
			$final .= $numbers[rand(0, $num_len - 1)];
    }

		return $final;
  }
  
  public static function getPaymentAmount(): string {
    return sprintf('%.2f', (self::DEFAULT_PAYMENT_CHARGE_AMOUNT / 100));
  }
}