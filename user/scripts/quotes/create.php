<?php

if (!isset($_POST['grt_quote_data']))
  	exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_quote_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  	exit(http_response_code(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();

$quote = new Quotes($connection);

$quote_text = strip_tags($data->quote_text);
$quote_text = trim($quote_text);
$quote_text = ucfirst($quote_text);

if ($quote->Exists($quote_text))
  	exit(Response::Send('Quote similar to this already exists.', Response::ERROR));

$quote_author = strip_tags($data->quote_author);
$quote_author = trim($quote_author);
$quote_author = ucfirst($quote_author);

$quote->text= $quote_text;
$quote->author = $quote_author;

if ($quote->Create()) {
	$log = new ActivityLog($connection);
	$log->text = '<strong>' . $_SESSION['grt_user']['name'] . '</strong> added a new quote <strong>(ID: ' . $connection->lastInsertId() . ')</strong>';
	$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_ADMIN);

	echo Response::Send('Quote has been successfully added.', Response::SUCCESS);
}

$db->close();