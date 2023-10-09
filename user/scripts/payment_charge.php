<?php

if (!isset($_POST['stripeToken']))
	exit(http_response_code(404));

session_start();

if (!isset($_SESSION['grt_user']['logged_in']))
	exit(http_response_code(404));

require_once '../../autoload.php';
require_once '../../lib/stripe/7.67.0/init.php';

try {
	\Stripe\Stripe::SetApiKey('...');

	$user = $_SESSION['grt_user'];
	$token = $_POST['stripeToken'];
	$id = $user['id'];
	$name = $user['name'];
	$username = $user['username'];
	$email = $user['email'];
	$paid_date = date('m/j/Y h:i:s');

	$customer = \Stripe\Customer::create([
		'email' => $email,
		'source' => $token
	]);

	$charge = \Stripe\Charge::create([
		'amount' => Payment::DEFAULT_PAYMENT_CHARGE_AMOUNT,
		'currency' => 'usd',
		'description' => 'GoldenRules Paid Membership',
		'customer' => $customer->id
	]);

	$db = new Database();
	$payment = new Payment($db->connect());

	$amount = Payment::getPaymentAmount();
	$payment->id = $customer->id;
	$payment->transaction_id = $payment->generateTransactionID() . $id;
	$payment->customer_id = $id;
	$payment->amount = $amount;
	$payment->status = Payment::STATUS_PENDING;

	if ($payment->Create()) {
		require '../../defines.php';

		$trans_id = $payment->transaction_id;
		$payer_username = $username;

		$_SESSION['card_payment_data'] = [
			'payer_name' => $name,
			'payer_email' => $email,
			'transaction_id' => $trans_id,
			'amount' => $amount,
			'paid_date' => $paid_date
		];

		$send_data = [
			'from_mail' => SERVER_MAIL_ADDRESS,
			'from_name' => SITE_NAME,
			'to_mail' => $email,
			'to_name' => $name
		];

		$mail_data = [
			'@to_name' => $name,
			'@transaction_id' => $trans_id,
			'@email' => $email,
			'@amount' => $amount,
			'@date' => $paid_date,
			'@username' => $payer_username,
			'@support_email' => SITE_SUPPORT_EMAIL
		];

		$mail_contents = file_get_contents('../templates/mails/payment.mailtemplate.html');
		$mail_contents = str_replace(array_keys($mail_data), array_values($mail_data), $mail_contents);
	
		App::sendMail($send_data, $user['first_name'] . ', here is your payment summary to GoldenRules', $mail_contents);
		echo Response::Send('/user/paid-membership/checkout?payment_status=success', Response::SUCCESS);
	}
} catch (\Stripe\Exception\CardException $e) {
	/*// Since it's a decline, \Stripe\Exception\CardException will be caught
	echo 'Status is:' . $e->getHttpStatus() . '<br>';
	echo 'Type is:' . $e->getError()->type . '<br>';
	echo 'Code is:' . $e->getError()->code . '<br>';
	// param is '' in this case
	echo 'Param is:' . $e->getError()->param . '<br>';
	echo 'Message is:' . $e->getError()->message . '<br>';*/

	echo Response::Send('<h3 class="mb-1">Payment Failed</h3><p>' . $e->getError()->message . '</p>', Response::ERROR);
} catch (\Stripe\Exception\RateLimitException $e) {
	// Too many requests made to the API too quickly
	echo Response::Send('<h3 class="mb-1">Payment Failed</h3><p>' . $e->getError()->message . '</p>', Response::ERROR);
} catch (\Stripe\Exception\InvalidRequestException $e) {
	// Invalid parameters were supplied to Stripe's API
	echo Response::Send('<h3 class="mb-1">Payment Failed</h3><p>' . $e->getError()->message . '</p>', Response::ERROR);
} catch (\Stripe\Exception\AuthenticationException $e) {
	// Authentication with Stripe's API Payment Failed
	// (maybe you changed API keys recently)
	echo Response::Send('<h3 class="mb-1">Payment Failed</h3><p>' . $e->getError()->message . '</p>', Response::ERROR);
} catch (\Stripe\Exception\ApiConnectionException $e) {
	// Network communication with Stripe Payment Failed
	echo Response::Send('<h3 class="mb-1">Payment Failed</h3><p>' . $e->getError()->message . '</p>', Response::ERROR);
} catch (\Stripe\Exception\ApiErrorException $e) {
	// Display a very generic error to the user, and maybe send
	// yourself an email
	echo Response::Send('<h3 class="mb-1">Payment Failed</h3><p>' . $e->getError()->message . '</p>', Response::ERROR);
} catch (Exception $e) {
	// Something else happened, completely unrelated to Stripe
	echo Response::Send('<h3 class="mb-1">Payment Failed</h3><p>' . $e->getError()->message . '</p>', Response::ERROR);
}