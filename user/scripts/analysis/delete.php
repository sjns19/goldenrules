<?php

if (!isset($_POST['grt_analysis_delete_data']))
  	exit(http_build_query(404));

session_start();
$data = json_decode($_POST['grt_analysis_delete_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  	exit(http_build_query(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();
$analysis = new TradingAnalysis($connection);
$analysis->id = $data->analysis_id;
$analysis->thumbnail_url = $data->analysis_thumbnail_url;

if ($analysis->Delete()) {
	$analysis->deleteThumbnail();
	
	$log = new ActivityLog($connection);
	$log->text = '<strong>' . $_SESSION['grt_user']['name'] . '</strong> deleted a trading analysis <strong>(ID: ' . $data->analysis_id . ')</strong>';
	$log_type = (bool) $_SESSION['grt_user']['is_admin'] ? ActivityLog::ACTIVITY_LOG_TYPE_ADMIN : ActivityLog::ACTIVITY_LOG_TYPE_USER;
	$log->Create($log_type);
	
	echo Response::Send('Trading analysis has been deleted successfully.', Response::SUCCESS);
}

$db->close();