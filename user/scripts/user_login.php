<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_POST['grt_user_login']))
  exit(http_response_code(404));

require_once '../../autoload.php';
require_once '../messages.php';

$data = json_decode($_POST['grt_user_login']);

$db = new Database();
$connection = $db->connect();

$user = new User($connection);

$email_or_username = filter_var($data->user_email_username, FILTER_SANITIZE_STRING);
$tmp = $user->getTemporaryData($email_or_username);

if (empty($tmp['data']))
  exit(Response::Send(MSG_ACCOUNT_DOES_NOT_EXIST, Response::ERROR));

if (!password_verify($data->user_password, $tmp['data']['password']))
  exit(Response::Send(MSG_INCORRECT_CREDS, Response::ERROR));

$id = $tmp['data']['id'];
$ip = App::getClientIP();
$user_data = $user->getDataById($id);

session_start();
session_regenerate_id();

$_SESSION['grt_user'] = $user_data;
$_SESSION['grt_user']['logged_in'] = true;

$user->last_logged_ip = $ip;
$user->last_active_unix = time();
$user->id = $id;
$user->session = session_id();
$user->updateLatestLoginState();

$log = new ActivityLog($connection);
$log->text = '<strong>' . $user_data['name'] . '</strong> logged in. <strong>(IP: ' . $ip . ')</strong>';
$log_type = (bool) $user_data['is_admin'] ? ActivityLog::ACTIVITY_LOG_TYPE_ADMIN : ActivityLog::ACTIVITY_LOG_TYPE_USER;
$log->Create($log_type);

echo Response::Send('', Response::SUCCESS);
$db->close();