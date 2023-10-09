<?php

if (!isset($_POST['grt_analysis_updated_data']))
  	exit(http_build_query(404));

session_start();
$data = json_decode($_POST['grt_analysis_updated_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  	exit(http_build_query(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();
$analysis = new TradingAnalysis($connection);

$thumbnail_image = (isset($_FILES['edited_analysis_thumbnail'])) ? $_FILES['edited_analysis_thumbnail']['tmp_name'] : null;
$current_thumbnail = $data->edited_analysis_current_thumb;

$analysis->title = App::sanitizeArticleTitle($data->edited_analysis_title);
$analysis->body = htmlentities($data->edited_analysis_body);
$analysis->id = $data->edited_analysis_id;
$analysis->thumbnail_url = $current_thumbnail;
$analysis->editor_id = $_SESSION['grt_user']['id'];

if ($analysis->Update()) {
	if (!is_null($thumbnail_image)) {
		$analysis->replaceThumbnail($thumbnail_image, App::IMAGE_COMPRESS_LEVEL);
	}

	$log = new ActivityLog($connection);
	$log->text = '<strong>' . $_SESSION['grt_user']['name'] . '</strong> edited a trading analysis <strong>(ID: ' . $analysis->id . ')</strong>';
	$log_type = (bool) $_SESSION['grt_user']['is_admin'] ? ActivityLog::ACTIVITY_LOG_TYPE_ADMIN : ActivityLog::ACTIVITY_LOG_TYPE_USER;
	$log->Create($log_type);
	
	echo Response::Send('Trading analysis has been updated successfully.', Response::SUCCESS);
}

$db->close();