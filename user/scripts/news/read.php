<?php

require_once '../../../autoload.php';
require_once '../../../defines.php';

$db = new Database();
$news = new News($db->connect());
$response = [];
$data = $news->readAll(null);

if (!empty($data)) {
	foreach ($data as $key => $val) {
		$response[$key] = [
		'id' => $val['id'],
		'author' => $val['author'],
		'title' => $val['title'],
		'url' => SITE_URL . '/news/' . $val['url_title'],
		'date' => date('M j, Y', $val['posted_date_unix']),
		'thumbnail_url' => $val['thumbnail_url']
		];
	}
}

echo json_encode($response);
$db->close();