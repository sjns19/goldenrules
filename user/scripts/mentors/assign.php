<?php

if (!isset($_POST['grt_assign_mentor_data']))
  exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_assign_mentor_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  exit(http_response_code(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();
$user = new User($connection);

$user->mentor_id = $data->mentor_id;
$user->id = $_SESSION['grt_user']['id'];

$_SESSION['grt_user']['mentor_id'] = $data->mentor_id;
if ($user->assignMentor()) {
  $log = new ActivityLog($connection);
  $log->text =  '<strong>' . $_SESSION['grt_user']['name'] . '</strong> selected <strong>' . $data->mentor_name . '</strong> as their mentor.';
  $log->Create(ActivityLog::ACTIVITY_LOG_TYPE_USER);
  
  echo Response::Send('You have selected ' . $data->mentor_name . ' as your mentor. <p class="mt-1 txt-white">Please patiently wait for their response via the email address.</p>', Response::SUCCESS);
}
$db->close();