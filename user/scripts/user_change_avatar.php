<?php

if (isset($_POST['grt_avatar_data'])) {
  	require_once '../../autoload.php';
	require_once '../messages.php';

	$data = json_decode($_POST['grt_avatar_data']);
  	$user_new_avatar = null;

  	if (isset($_FILES['avatar'])) {
    	$user_new_avatar = $_FILES['avatar']['tmp_name'];
  	}

  	$db = new Database();
  	$connection = $db->connect();
  	$user = new User($connection);

	$user_id = $data->user_id;
	$user_name = $data->user_name;
	$user_current_avatar = $data->user_current_avatar;

	if (is_null($user_new_avatar)) {
		$user->removeAvatarFile($user_current_avatar);
		$avatar_url = 'none';
	} else {
		$file = $user->uploadAvatarFile($user_new_avatar, $user_current_avatar, $user_name, $user_id);
		$avatar_url = $file;
  	}

	$user->avatar_url = $avatar_url;
	$user->id = $user_id;
	
  	if ($user->updateAvatar()) {
		$log = new ActivityLog($connection);

		$log->text = '<strong>' . $admin_name . '</strong> updated their avatar.';
    	$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_USER);
    
    	session_start();

    	$_SESSION['grt_user']['avatar_url'] = $avatar_url;
    
    	echo Response::Send(MSG_AVATAR_UPDATED, Response::SUCCESS);
  	}
  	$db->close();
} else {
	http_response_code(404);
	exit();
}