<?php

require_once '../../../autoload.php';

$db = new Database();
$quote = new Quotes($db->connect());
$response = $quote->ReadAll();

echo json_encode($response);

$db->close();