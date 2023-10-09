<?php

if (!isset($_POST['grt_user_register']))
  	exit(http_response_code(404));

require_once '../../autoload.php';
require_once '../messages.php';

$data = json_decode($_POST['grt_user_register']);

$db = new Database();
$connection = $db->connect();

$user = new User($connection);

$firstname = filter_var($data->user_firstname, FILTER_SANITIZE_STRING);
$lastname = filter_var($data->user_lastname, FILTER_SANITIZE_STRING);
$password = $data->user_password;
$username = filter_var($data->user_username, FILTER_SANITIZE_STRING);
$email = $data->user_email;

if (strlen($firstname) < 2)
  	exit(Response::Send(MSG_FIRSTNAME_TOO_SHORT, Response::ERROR));

if (strlen($firstname) > 24)
  	exit(Response::Send(MSG_FIRSTNAME_TOO_LONG, Response::ERROR));

if (strlen($lastname) < 2)
  	exit(Response::Send(MSG_LASTNAME_TOO_SHORT, Response::ERROR));

if (strlen($lastname) > 24)
  	exit(Response::Send(MSG_LASTNAME_TOO_LONG, Response::ERROR));
  
if (strlen($username) < 2)
  	exit(Response::Send(MSG_USERNAME_TOO_SHORT, Response::ERROR));

if (strlen($username) > 24)
  	exit(Response::Send(MSG_USERNAME_TOO_LONG, Response::ERROR));

if (strlen($password) < 8)
  	exit(Response::Send(MSG_PASSWORD_TOO_SHORT, Response::ERROR));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
  	exit(Response::Send(MSG_INVALID_EMAIL_FORMAT, Response::ERROR));

if ($user->isTaken($email, 'email'))
  	exit(Response::Send(MSG_EMAIL_TAKEN, Response::ERROR));

if ($user->isTaken($username, 'username'))
  	exit(Response::Send(MSG_USERNAME_TAKEN, Response::ERROR));

$user->uid_token = App::getRandomString(30); // Unique id token for URL related processes such as confirming email and changing password
$user->first_name = $firstname;
$user->last_name = $lastname;
$user->email = $email;
$user->password = password_hash($password, PASSWORD_DEFAULT);
$user->username = $username;
$user->joined_date_unix = time();
$user->avatar_color = App::getRandomColor();
$user->ip = App::getClientIP();
$user->email_verification_type = User::EMAIL_VERIFICATION_TYPE_REGISTRATION;

if ($user->Create()) {
	$user->sendVerificationMail();
	
	$log = new ActivityLog($connection);

	$log->text = 'New account registration: <strong>Name: ' . $firstname . ' ' . $lastname . '</strong>, <strong>Username: ' . $username . '</strong>, <strong>Email: ' . $email . '</strong>, <strong>(IP: ' . $user->ip . ')</strong>';
	$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_USER);
	
	echo Response::Send(MSG_SUCCESSFULLY_REGISTERED, Response::SUCCESS);
}

$db->close();