<?php

if (!isset($_POST['grt_user_ip']))
  exit(http_response_code(404));

$data = json_decode($_POST['grt_user_ip']);
$ip = $data->ip;

if (!filter_var($ip, FILTER_VALIDATE_IP))
  exit(json_encode(['response' => 'error']));

require_once '../../../autoload.php';

$location_arr = App::getUserOrigin($ip);
echo json_encode($location_arr);