<?php

if (!isset($_POST['grt_news_delete_data']))
  exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_news_delete_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  exit(http_response_code(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();
$news = new News($connection);

$news->id = $data->news_id;
$news->thumbnail_url = $data->news_thumbnail_url;

if ($news->Delete()) {
  $log = new ActivityLog($connection);

  $log->text = '<strong>' . $_SESSION['grt_user']['name'] . '</strong> deleted a news <strong>(ID: ' . $data->news_id . ')</strong>';
  $log->Create(ActivityLog::ACTIVITY_LOG_TYPE_ADMIN);
  $news->deleteThumbnail();
  
  echo Response::Send('News has been successfully deleeted.', Response::SUCCESS);
}
$db->close();