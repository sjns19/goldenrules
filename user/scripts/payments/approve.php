<?php

if (!isset($_POST['grt_payment_approve_data']))
  	exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_payment_approve_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  	exit(http_response_code(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();

$payment = new Payment($connection);
$payment->status = Payment::STATUS_APPROVED;
$payment->id = $data->payment_id;

if ($payment->setStatus()) {
	$user = new User($connection);
	$user->id = $data->payment_payer_id;
	$user->paid_membership = true;

	if ($user->setPaidMembership()) {
		require_once '../../../defines.php';
		require_once '../../messages.php';

		$payer_name = $data->payment_payer_name;
		$payer_email = $data->payment_payer_email;
		$admin_name = $_SESSION['grt_user']['name'];
		$trans_id = $data->payment_transaction_id;
		$payer_username = $data->payment_payer_username;

		$send_data = [
			'from_mail' => SERVER_MAIL_ADDRESS,
			'from_name' => SITE_NAME,
			'to_mail' => $payer_email,
			'to_name' => $payer_name
		];

		$mail_data = [
			'@to_name' => $payer_name,
			'@transaction_id' => $trans_id,
			'@email' => $payer_email,
			'@admin_name' => $admin_name,
			'@amount' => $data->payment_amount,
			'@date' => $data->payment_date,
			'@username' => $payer_username,
			'@support_email' => SITE_SUPPORT_EMAIL
		];

		$mail_contents = file_get_contents('../../templates/mails/payment-approve.mailtemplate.html');
		$mail_contents = str_replace(array_keys($mail_data), array_values($mail_data), $mail_contents);

		$log = new ActivityLog($connection);
		$log->text = '<strong>' . $admin_name . '</strong> approved <strong>' . $payer_name . '</strong>\'s payment. (Trans. ID: <strong>' . $trans_id . '</strong>)';
		$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_ADMIN);

		if (!App::sendMail($send_data, 'Your payment has been approved', $mail_contents)) {
			echo Response::Send('You approved ' . $payer_name . '\'s payment.', Response::SUCCESS);
		} else {
			echo Response::Send('You approved ' . $payer_name . '\'s payment.<p class="mt-1">Note: There was a problem while trying to send email to the respected user.</p>', Response::SUCCESS);
		}
	}
}

$db->close();