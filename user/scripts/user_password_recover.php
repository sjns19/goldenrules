<?php

if (!isset($_POST['grt_password_recover_email']))
  exit(http_response_code(404));

require_once '../../autoload.php';
require_once '../messages.php';

$email = $_POST['grt_password_recover_email'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
  exit(Response::Send(MSG_INVALID_EMAIL_FORMAT, Response::TYPE_ERROR));

$db = new Database();
$connection = $db->connect();
$user = new User($connection);

if (!$user->isTaken($email, 'email')) {
  $db->close();
  exit(Response::Send(MSG_NO_ACCOUNT_WITH_EMAIL, Response::ERROR));
}

require_once '../../defines.php';

$user_data = $user->getDataByEmail($email);
$uid_token = $user_data['data']['uid_token'];
$first_name = $user_data['data']['first_name'];
$full_name = $first_name . ' ' . $user_data['data']['last_name'];
$token = App::getRandomString(30);
$expire_unix = (time() + 300);
$url = SITE_URL . '/user/reset-password?_rt=' . $token . '&_rft=' . $uid_token;

$mail_send_data = [
  'from_mail' => SERVER_MAIL_ADDRESS,
  'from_name' => SITE_NAME,
  'to_mail' => $email,
  'to_name' => $full_name
];

$mail_data = [
  '@to_name' => $first_name,
  '@reset_url' => $url,
  '@support_email' => SITE_SUPPORT_EMAIL
];

$mail_contents = file_get_contents('../templates/mails/password-reset.mailtemplate.html');
$mail_contents = str_replace(array_keys($mail_data), array_values($mail_data), $mail_contents);

if (!App::sendMail($mail_send_data, 'Reset your forgotten account Password', $mail_contents)) {
  $db->close();
  exit(Response::Send(MSG_SOMETHING_WENT_WRONG, Response::ERROR));
}

$request = new PageRequest($connection);

$request->requested_for = $uid_token;
$request->timestamp = $expire_unix;
$request->token = $token;
$request->type = PageRequest::REQUEST_TYPE_RESET_PASSWORD;

if ($request->Create()) {
  $ip = App::getClientIP();

  $log = new ActivityLog($connection);
  $log->text = '<strong>' . $full_name . '</strong> has requested the system to reset their password via email. <strong>(IP: ' . $ip . ')</strong>';
  $log->Create(ActivityLog::ACTIVITY_LOG_TYPE_USER);
  
  echo Response::Send('
    <h3>Check your mail</h3><br>
    <p class="txt-grey">
      An email has been sent to <strong>' . $email . '</strong>. Please check your inbox to continue changing your password.</p><br>
      <p class="txt-grey"><strong>Note:</strong> Your request to change the account password will expire in 5 minutes so please be in time.</p>
    ', Response::SUCCESS);
}
$db->close();