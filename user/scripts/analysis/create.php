<?php

if (!isset($_POST['grt_analysis_data']))
  exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_analysis_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  exit(http_response_code(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();

$analysis = new TradingAnalysis($connection);

$url_title = 'chart-analysis-' . App::getRandomString(30);
$thumbnail_image = (isset($_FILES['analysis_thumbnail'])) ? $_FILES['analysis_thumbnail']['tmp_name'] : null;

$analysis->title = App::sanitizeArticleTitle($data->analysis_title);
$analysis->url_title = $url_title;
$analysis->body = htmlentities($data->analysis_body);
$analysis->thumbnail_url = 'no-thumb.png';
$analysis->author_id = $_SESSION['grt_user']['id'];

if ($analysis->Create()) {
  require_once '../../../defines.php';

  if (!is_null($thumbnail_image)) {
    $analysis->id = $connection->lastInsertId();
    $analysis->thumbnail_url = $analysis->generateThumnailURL();
    $analysis
      ->updateThumbnailURL()
      ->compressThumbnail($thumbnail_image, App::IMAGE_COMPRESS_LEVEL);
  }

  $access_url = SITE_URL . '/trading-analysis/' . $url_title;
  $log = new ActivityLog($connection);

  $log->text = '<strong>' .  $_SESSION['grt_user']['name'] . '</strong> posted a trading analysis <a href="' . $access_url . '" target="_blank">' . $access_url;
  $log_type = (bool) $_SESSION['grt_user']['is_admin'] ? ActivityLog::ACTIVITY_LOG_TYPE_ADMIN : ActivityLog::ACTIVITY_LOG_TYPE_USER;
  $log->Create($log_type);
  
  echo Response::Send('Trading analysis has been posted successfully.', Response::SUCCESS);
}
$db->close();