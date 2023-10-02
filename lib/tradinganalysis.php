<?php

class TradingAnalysis {
  public $id;
  public $title;
  public $url_title;
  public $body;
  public $author_id;
  public $thumbnail_url;
  public $editor_id;

  private const THUMBNAIL_DIR = '../../../media/thumbnails/analysis/';

  private $db_table_analysis = 'grt_trading_analysis';
  private $db_table_users = 'grt_users';

  private $db_connection;

  public function __construct(object $db_connection) {
    $this->db_connection = $db_connection;
  }

  public function Create(): bool {
    $query = '
      INSERT INTO
        ' . $this->db_table_analysis . ' 
      SET 
        title=:title,
        url_title=:url_title,
        body=:body,
        thumbnail_url=:thumbnail,
        author_id=:author_id,
        posted_date_unix=UNIX_TIMESTAMP()
    ';
    
    $params = [
      'title' => $this->title,
      'url_title' => $this->url_title,
      'body' => $this->body,
      'thumbnail' => $this->thumbnail_url,
      'author_id' => $this->author_id
    ];

    return ($this->db_connection->prepare($query)->execute($params)) ? true : false;
  }

  public function Update(): bool {
    $query = '
      UPDATE 
        ' . $this->db_table_analysis . ' 
      SET 
        title=:title,
        body=:body,
        editor_id=:editor_id,
        edited_date_unix=UNIX_TIMESTAMP() 
      WHERE 
        id=:id
    ';

    $params = [
      'title' => $this->title,
      'body' => $this->body,
      'editor_id' => $this->editor_id,
      'id' => $this->id
    ];
    
    return ($this->db_connection->prepare($query)->execute($params)) ? true : false;
  }

  public function readSingle(string $url_title): array {
    $data = [];
    $query = '
      SELECT 
        t.id,
        t.title,
        t.body,
        t.posted_date_unix,
        t.thumbnail_url,
        t.author_id,
        t.edited_date_unix,
        CONCAT(e.first_name, " ", e.last_name) AS editor,
        CONCAT(a.first_name, " ", a.last_name) AS author,
        LEFT(a.first_name,1) AS author_first_letter,
        a.avatar_url AS author_avatar_url,
        a.avatar_color AS author_avatar_color 
      FROM 
        ' . $this->db_table_analysis . ' t 
      LEFT JOIN 
        ' . $this->db_table_users . ' a 
      ON 
        a.id=t.author_id 
      LEFT JOIN 
        ' . $this->db_table_users . ' e 
      ON 
        e.id=t.editor_id 
      WHERE 
        url_title=:title 
      LIMIT 1
    ';

    $stmt = $this->db_connection->prepare($query);
    
    if ($stmt->execute(['title' => $url_title]) && $stmt->rowCount()) {
      $data = $stmt->fetch();
    }

    return $data;
  }

  public function readAll(int $first_results = null, int $limit = 0, int $author_id = 0): array {
    $data = [];
    $query = '
      SELECT 
        t.id,
        t.title,
        t.body,
        CONCAT(a.first_name, " ", a.last_name) AS author,
        LEFT(a.first_name,1) AS author_first_letter,
        a.avatar_color AS author_avatar_color,
        a.avatar_url AS author_avatar_url,
        t.thumbnail_url,
        t.posted_date_unix,
        t.url_title
      FROM 
        ' . $this->db_table_analysis . ' t 
      INNER JOIN 
        ' . $this->db_table_users . ' a 
      ON 
        a.id=t.author_id ';

    if (is_null($first_results) && !$limit) {
      $query .= ($author_id) ? 'WHERE author_id=' . $author_id . ' ORDER BY id DESC' : 'ORDER BY id DESC';
    } else {
      $query .= 'ORDER BY id DESC LIMIT ' . $first_results . ',' . $limit;
    }
    
    $stmt = $this->db_connection->prepare($query);
    
    if ($stmt->execute() && $stmt->rowCount()) {
      $data = $stmt->fetchAll();
    }

    return $data;
  }

  public function Delete(): bool {
    $query = 'DELETE FROM ' . $this->db_table_analysis . ' WHERE id=:id';
    return ($this->db_connection->prepare($query)->execute(['id' => $this->id])) ? true : false;
  }

  public function getTotal() {
    $total = 0;
    $query = 'SELECT COUNT(title) AS total FROM ' . $this->db_table_analysis;
    $stmt = $this->db_connection->prepare($query);
    
    if ($stmt->execute()) {
      $rows = $stmt->fetch();
    
      if ($rows > 0) {
        $total = $rows['total'];
      }
    }

    return $total;
  }

  public function replaceThumbnail(string $thumbnail, int $quality) {
    $this->deleteThumbnail();
    $this->thumbnail_url = $this->generateThumnailURL();
    $this->updateThumbnailURL()->compressThumbnail($thumbnail, $quality);
  }
  
  public function updateThumbnailURL() {
    $query = '
      UPDATE
        ' . $this->db_table_analysis . ' 
      SET 
        thumbnail_url=:thumbnail
      WHERE 
        id=:id
    ';
    
    $params = [
      'id' => $this->id,
      'thumbnail' => $this->thumbnail_url
    ];
    
    $this->db_connection->prepare($query)->execute($params);
    return $this;
  }
  
  public function deleteThumbnail() {
    if ($this->thumbnail_url != 'no-thumb.png') {
      $thumbnail_path = self::THUMBNAIL_DIR . $this->thumbnail_url;
      
      if (file_exists($thumbnail_path)) {
        unlink($thumbnail_path);
      }
    }

    return $this;
  }
  
  public function compressThumbnail(string $src, int $quality) {
    $img_size = getimagesize($src);
    
    switch ($img_size['mime']) {
      case 'image/jpeg': 
        $image = imagecreatefromjpeg($src); 
        break; 
      case 'image/png': 
        $image = imagecreatefrompng($src); 
        break; 
    }
    
    imagejpeg($image, self::THUMBNAIL_DIR . $this->thumbnail_url, $quality);
  }

  public function generateThumnailURL(): string {
    $hash = md5('analysis_id-' . $this->id);
    $unix_hash = hash('adler32', time());
    $result = 'thumb-' . $hash . '-' . $unix_hash . '.jpg';
    
    return $result;
  }
}