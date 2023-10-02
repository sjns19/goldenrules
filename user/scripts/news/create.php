<?php

if (!isset($_POST['grt_news_data']))
  exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_news_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  exit(http_response_code(404));

require_once '../../../autoload.php';
require_once '../../messages.php';

$db = new Database();
$connection = $db->connect();
$news = new News($connection);

$title = App::sanitizeArticleTitle($data->news_title);
$url_title = App::strToSEO($title);

if ($news->Exists($url_title, $title))
  exit(Response::Send(MSG_NEWS_EXISTS, Response::ERROR));

$thumbnail_image = (isset($_FILES['news_thumbnail'])) ? $_FILES['news_thumbnail']['tmp_name'] : null;

$news->title = $title;
$news->url_title = $url_title;
$news->body = htmlentities($data->news_body);
$news->thumbnail_url = 'no-thumb.png';
$news->author_id = $_SESSION['grt_user']['id'];

if ($news->Create()) {
  require_once '../../../defines.php';

  if (!is_null($thumbnail_image)) {
    $news->id = $connection->lastInsertId();
    $news->thumbnail_url = $news->generateThumnailURL();
    $news
      ->updateThumbnailURL()
      ->compressThumbnail($thumbnail_image, App::IMAGE_COMPRESS_LEVEL);
  }

  $url = SITE_URL . '/news/' . $url_title;
  $log = new ActivityLog($connection);

  $log->text =  '<strong>' . $_SESSION['grt_user']['name'] . '</strong> posted a news <a href="' . $url . '" target="_blank">' . $url . '</a>';
  $log->Create(ActivityLog::ACTIVITY_LOG_TYPE_ADMIN);
  
  echo Response::Send('News has been posted successfully.<br>You can access it at<br><br><a class="link-white" href="' . $url . '" target="_blank">' . $url . '</a>', Response::SUCCESS);
}
$db->close();