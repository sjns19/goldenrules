<?php

if (!isset($_POST['grt_change_user_email']))
  exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_change_user_email']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  exit(http_response_code(404));

require_once '../../autoload.php';
require_once '../messages.php';

$new_email = $data->user_new_email;

if (!filter_var($new_email, FILTER_VALIDATE_EMAIL))
  exit(Response::Send(MSG_INVALID_EMAIL_FORMAT, Response::ERROR));

if (strlen($new_email) > 100)
  exit(Response::Send(MSG_EMAIL_TOO_LONG, Response::ERROR));

$db = new Database();
$connection = $db->connect();

$user = new User($connection);
$tmp = $user->getDataByEmail($new_email);

if (!empty($tmp['data']))
  exit(Response::Send(MSG_EMAIL_TAKEN, Response::ERROR));

$uid_token = $_SESSION['grt_user']['uid_token'];
$user->temporary_email = $new_email;
$user->uid_token = $uid_token;

if ($user->requestEmailChange()) {
  require '../../defines.php';

  $page_token = App::getRandomString(30);
  $url = SITE_URL . '/user/change-email/?_cet=' . $page_token . '&_ceft=' . $uid_token;
  $full_name = $_SESSION['grt_user']['name'];

  $send_data = [
    'from_mail' => SERVER_MAIL_ADDRESS,
    'from_name' => SITE_NAME,
    'to_mail' => $new_email,
    'to_name' => $full_name
  ];

  $mail_data = [
    '@to_name' => $_SESSION['grt_user']['first_name'],
    '@change_to_email' => $new_email,
    '@change_from_email' => $_SESSION['grt_user']['email'],
    '@verification_url' => $url,
    '@support_email' => SITE_SUPPORT_EMAIL
  ];

  $mail_contents = file_get_contents('../templates/mails/change-email.mailtemplate.html');
  $mail_contents = str_replace(array_keys($mail_data), array_values($mail_data), $mail_contents);

  if (!App::sendMail($send_data, 'Change your email address ', $mail_contents)) {
    $db->close();
    exit(Response::Send('Something went wrong while trying to process your request.', Response::ERROR));
  }
  
  $request = new PageRequest($connection);

  $request->requested_for = $uid_token;
  $request->token = $page_token;
  $request->timestamp = (time() + 300); // 5 minutes
  $request->type = PageRequest::REQUEST_TYPE_CHANGE_EMAIL;
  $request->Create();

  $log = new ActivityLog($connection);

  $log->text = '<strong>' . $full_name . '</strong> has requested the system to change their email address.';
  $log_type = (bool) $_SESSION['grt_user']['is_admin'] ? ActivityLog::ACTIVITY_LOG_TYPE_ADMIN : ActivityLog::ACTIVITY_LOG_TYPE_USER;
  $log->Create($log_type);
  
  echo Response::Send('<div class="txt-grey txt-mds mt-2">A verification mail has been sent to <strong class="txt-gold">' . $new_email . '</strong>. Please check your inbox and verify. <p class="mt-1">Your email address will only be changed once you verify it.</p><p class="mt-1">If you did not get any verification mail, please re-submit your email address.</p><p class="mt-1">Your request to change the email address will expire in 5 minutes so please be in time.</p></div>', Response::SUCCESS);
}
$db->close();