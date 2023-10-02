<?php

require_once '../../../autoload.php';

$db = new Database();
$payment = new Payment($db->connect());
$response = $payment->getList();
echo json_encode($response);
$db->close();