<?php

if (!isset($_POST['grt_payment_deny_data']))
  exit(http_response_code(404));

session_start();
$data = json_decode($_POST['grt_payment_deny_data']);

if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $data->csrf_token)
  exit(http_response_code(404));

require_once '../../../autoload.php';

$db = new Database();
$connection = $db->connect();

$payment = new Payment($connection);
$payment->status = Payment::STATUS_DENIED;
$payment->id = $data->payment_id;

if ($payment->setStatus()) {
  require_once '../../../defines.php';
  require_once '../../messages.php';

  $payer_name = $data->payment_payer_name;
  $payer_email = $data->payment_payer_email;
  $payer_username = $data->payment_payer_username;
  $admin_name = $_SESSION['grt_user']['name'];
  $deny_reason = $data->payment_deny_reason;
  $trans_id = $data->payment_transaction_id;

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
    '@deny_reason' => $deny_reason,
    '@amount' => $data->payment_amount,
    '@date' => $data->payment_date,
    '@username' => $payer_username,
    '@support_email' => SITE_SUPPORT_EMAIL
  ];

  $mail_contents = file_get_contents('../../templates/mails/payment-deny.mailtemplate.html');
  $mail_contents = str_replace(array_keys($mail_data), array_values($mail_data), $mail_contents);

  $log = new ActivityLog($connection);
  $log->text = '<strong>' . $admin_name . '</strong> denied <strong>' . $payer_name . '</strong>\'s payment. (Trans. ID: <strong>' . $trans_id . '</strong>) - Reason: <strong>' . $deny_reason . '</strong>';
  $log->Create(ActivityLog::ACTIVITY_LOG_TYPE_ADMIN);

  if (App::sendMail($send_data, 'Your payment was denied', $mail_contents)) {
    echo Response::Send('You denied ' . $payer_name . '\'s payment<br>Reason: ' . $deny_reason, Response::SUCCESS);
  } else {
    echo Response::Send('You denied ' . $payer_name . '\'s payment<br>Reason: ' . $deny_reason . '<p class="mt-1">Note: There was a problem while trying to send email to the respected user.</p>', Response::SUCCESS);
  }
}
$db->close();