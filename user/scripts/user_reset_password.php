<?php

if (isset($_POST['grt_reset_user_password'])) {
	require_once '../../autoload.php';
	require_once '../messages.php';

	$data = json_decode($_POST['grt_reset_user_password']);

	if ($data->timestamp < time())
		exit(Response::Send(MSG_PASSWORD_RESET_REQUEST_EXPIRED, Response::ERROR));

	$new_password = $data->user_new_password;
	
	if (strlen($new_password) < 8)
		exit(Response::Send(MSG_PASSWORD_TOO_SHORT, Response::ERROR));

	$db = new Database();
	$connection = $db->connect();

	$user = new User($connection);

	$uid_token = $data->user_uid_token;
	$tmp = $user->getTemporaryData($uid_token);

	if (password_verify($new_password, $tmp['data']['password']))
		exit(Response::Send(MSG_PASSWORD_CANNOT_BE_SAME, Response::ERROR));

	$user->uid_token = $uid_token;
	$user->password = password_hash($new_password, PASSWORD_DEFAULT);

	if ($user->updatePassword()) {
		$request = new PageRequest($connection);

		$request->token = $data->request_page_token;
		$request->Delete();

		$log = new ActivityLog($connection);

		$log->text = 'Password was resetted via email for user <strong>' . $tmp['data']['username'] . '</strong>.';
		$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_USER);

		echo Response::Send(MSG_PASSWORD_SUCCESSFULLY_RESET, Response::SUCCESS);
	}
	$db->close();
} else {
	http_response_code(404);
	die;
}