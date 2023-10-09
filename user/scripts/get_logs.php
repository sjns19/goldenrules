<?php

if (isset($_GET['type'])) {
	$type = $_GET['type'];
	if ($type == 0 || $type == 1) {
		require_once '../../autoload.php';

		$db = new Database();
		$log = new ActivityLog($db->connect());
		$response = $log->getList($type);
		echo json_encode($response);
		$db->close();
	} else {
		http_response_code(404);
		exit;
	}
} else {
	http_response_code(404);
	exit;
}