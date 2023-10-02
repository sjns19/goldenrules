<?php

require_once '../../../autoload.php';

$db = new Database();
$user = new User($db->connect());
$list = $user->getMentors();
echo json_encode($list);
$db->close();