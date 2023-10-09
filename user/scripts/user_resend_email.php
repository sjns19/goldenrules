<?php

if (!isset($_POST['grt_resend_email']))
  	exit(http_response_code(404));

require_once '../../autoload.php';

$data = json_decode($_POST['grt_resend_email']);

$db = new Database();
$connection = $db->connect();

$user = new User($connection);

$user->first_name = $data->user_first_name;
$user->last_name = $data->user_last_name;
$user->uid_token = $data->user_uid_token;
$user->email = $data->user_email;
$user->email_verification_type = User::EMAIL_VERIFICATION_TYPE_RESEND;

$response = $user->sendVerificationMail();
$status = ($response) ? 'success' : 'error';
$msg = ($response) ? '<strong>Email has been sent</strong><br>Please check your inbox and verify your email address.' : '<strong>Could not send email</strong><p class="mt-1">Your email may have already been verified or the system has failed. If you keep on seeing this error, please contact us.</p>';

echo json_encode([
	'status' => $status,
	'message' => $msg
]);

if ($response === true) {
	$log = new ActivityLog($connection);
	$log->text = '<strong>' . $data->user_first_name . ' ' . $data->user_last_name . '</strong> requested to re-send a verification email.';
	$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_USER);
}

$db->close();