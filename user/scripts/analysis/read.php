<?php

require_once '../../../autoload.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
$db = new Database();
$analysis = new TradingAnalysis($db->connect());
$response = [];
$data = $analysis->readAll(null, 0, $user_id);

if (!empty($data)) {
	foreach ($data as $key => $val) {
		$response[$key] = [
		'id' => $val['id'],
		'author' => $val['author'],
		'title' => $val['title'],
		'date' => date('M j, Y', $val['posted_date_unix']),
		'url' => $val['url_title'],
		'thumbnail_url' => $val['thumbnail_url']
		];
	}
}

echo json_encode($response);

$db->close();