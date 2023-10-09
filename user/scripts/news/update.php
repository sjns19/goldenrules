<?php

if (!isset($_POST['grt_news_updated_data']))
  	exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_news_updated_data']);

if (!isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] != $data->csrf_token)
  	exit(http_response_code(404));

require_once '../../../autoload.php';
require_once '../../messages.php';

$data = json_decode($_POST['grt_news_updated_data']);

$db = new Database();
$connection = $db->connect();

$news = new News($connection);

$title = App::sanitizeArticleTitle($data->edited_news_title);
$url_title = App::strToSEO($title);

if ($news->Exists($url_title, $title, $data->edited_news_id))
  	exit(Response::Send(MSG_NEWS_EXISTS, Response::ERROR));

$thumbnail_image = (isset($_FILES['edited_news_thumbnail'])) ? $_FILES['edited_news_thumbnail']['tmp_name'] : null;
$current_thumbnail = $data->edited_news_current_thumb;

$news->title = $title;
$news->url_title = $url_title;
$news->body = htmlentities($data->edited_news_body);
$news->id = $data->edited_news_id;
$news->thumbnail_url = $current_thumbnail;
$news->editor_id = $_SESSION['grt_user']['id'];

if ($news->Update()) {
	if (!is_null($thumbnail_image)) {
		$news->replaceThumbnail($thumbnail_image, App::IMAGE_COMPRESS_LEVEL);
	} else if (isset($data->is_removing_thumbnail)) {
		$news->deleteThumbnail()->thumbnail_url = 'no-thumb.png';
	}

	$news->updateThumbnailURL();

	$log = new ActivityLog($connection);
	$log->text = '<strong>' . $_SESSION['grt_user']['name'] . '</strong> edited a news <strong>(ID: ' . $news->id . ')</strong>';
	$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_ADMIN);
	
	echo Response::Send('News has been updated successfully.', Response::SUCCESS);
}

$db->close();