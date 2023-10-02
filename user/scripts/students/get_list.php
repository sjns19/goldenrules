<?php

if (!isset($_GET['mentor_id']))
  exit(http_response_code(404));

require_once '../../../autoload.php';

$mentor_id = $_GET['mentor_id'];

$db = new Database();
$user = new User($db->connect());
$response = $user->getStudents($mentor_id);
echo json_encode($response);
$db->close();