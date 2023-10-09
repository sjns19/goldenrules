<?php

if (!isset($_POST['grt_quote_delete_data']))
  	exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_quote_delete_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  	exit(http_response_code(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();
$quote = new Quotes($connection);
$quote->id = $data->quote_id;

if ($quote->Delete()) {
	$log = new ActivityLog($connection);
	$log->text = '<strong>' . $_SESSION['grt_user']['name'] . '</strong> deleted a quote <strong>(ID: ' . $data->quote_id . ')</strong>';
	$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_ADMIN);
	
	echo Response::Send('Quote has been successfully deleted.', Response::SUCCESS);
}

$db->close();