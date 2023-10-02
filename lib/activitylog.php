<?php

class ActivityLog {
  public $text;
  public $timestamp;

  private $db_table_admin = 'grt_admin_activity_logs';
  private $db_table_user = 'grt_user_activity_logs';

  public const ACTIVITY_LOG_TYPE_ADMIN = 0;
  public const ACTIVITY_LOG_TYPE_USER = 1;

  private $db_connection;

  public function __construct(object $db_connection) {
    $this->db_connection = $db_connection;
  }

  public function Create(int $type): bool {
    $table = $this->getDatabaseTable($type);
    $query = 'INSERT INTO ' . $table . ' SET text=:text';
    return $this->db_connection->prepare($query)->execute(['text' => $this->text]) ? true : false;
  }

  public function getList(int $type): array {
    $data = [];
    $table = $this->getDatabaseTable($type);
    $query = '
      SELECT 
        text,
        timestamp 
      FROM 
        ' . $table . '
      ORDER BY 
        id 
      DESC';

    $stmt = $this->db_connection->prepare($query);
    if ($stmt->execute() && $stmt->rowCount()) {
      $data = $stmt->fetchAll();
    }
    return $data;
  }

  private function getDatabaseTable(int $type): string {
    return ($type === self::ACTIVITY_LOG_TYPE_ADMIN) ? $this->db_table_admin : $this->db_table_user;
  }
}