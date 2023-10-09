<?php

if (!isset($_POST['grt_end_session_data']))
  	exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_end_session_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token) 
  	exit(http_response_code(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();
$user = new User($connection);
$user->id = $data->student_id;

if ($user->removeMentor()) {
	$log = new ActivityLog($connection);
	$log->text =  '<strong>' . $_SESSION['grt_user']['name'] . '</strong> has ended the session with <strong>' . $data->student_name . '</strong>';
	$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_ADMIN);
	
	echo Response::Send('You ended the session with ' . $data->student_name, Response::SUCCESS);
}

$db->close();