<?php

if (!isset($_POST['grt_change_user_password']))
  exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_change_user_password']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  exit(http_response_code(404));

require_once '../../autoload.php';
require_once '../messages.php';

$new_password = $data->user_new_password;

if (strlen($new_password) < 8)
  exit(Response::Send(MSG_PASSWORD_TOO_SHORT, Response::ERROR));

$db = new Database();
$connection = $db->connect();

$user = new User($connection);

$current_password = $data->user_current_password;
$uid_token = $_SESSION['grt_user']['uid_token'];
$tmp = $user->getTemporaryData($uid_token);

if (!password_verify($current_password, $tmp['data']['password']))
  exit(Response::Send(MSG_PASSWORD_MISMATCH, Response::ERROR));

if (password_verify($new_password, $tmp['data']['password']))
  exit(Response::Send(MSG_PASSWORD_SIMILAR, Response::ERROR));

$user->uid_token = $uid_token;
$user->password = password_hash($new_password, PASSWORD_DEFAULT);

if ($user->updatePassword()) {
  $log = new ActivityLog($connection);
  $log->text = '<strong>' . $_SESSION['grt_user']['name'] . '</strong> changed their password.';
  $log_type = (bool) $_SESSION['grt_user']['is_admin'] ? ActivityLog::ACTIVITY_LOG_TYPE_ADMIN : ActivityLog::ACTIVITY_LOG_TYPE_USER;
  $log->Create($log_type);

  echo Response::Send(MSG_PASSWORD_SUCCESSFULLY_CHANGED, Response::SUCCESS);
}
$db->close();