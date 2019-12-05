<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Origin,Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/config.php';
global $mysqli;

$ip = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];

$rawBody = file_get_contents("php://input");
$data = array();
$data = json_decode($rawBody, true);

$token = $mysqli->escape_string($data['token']);
$ctf = $mysqli->escape_string($data['ctf']);
$pid = $mysqli->escape_string($data['pid']);

$response = array();

if (!empty($token) &&
    !empty($ctf) &&
    isset($pid)
    ) {
    $user_query_result = $mysqli->query("SELECT uid FROM users WHERE token = '$token' LIMIT 1");
    if ($user_row = $user_query_result->fetch_assoc()) {
        $uid = $user_row['uid'];
        if ($mysqli->query("INSERT INTO submission(uid,pid,code) VALUES ('$uid','$pid','$ctf')")
            === TRUE) {
            http_response_code(200);
            $response['msg'] = "Code has been submitted successfully.";
        } else {
            http_response_code(400);
            $response['error'] = "Error Submitting: " . $mysqli->error;;
        }
    } else {
        http_response_code(400);
        $response['error'] = "User does not exists!";
    }

} else {
    http_response_code(400);
    $response['error'] = "Insufficient data.";
}
$response = json_encode($response);
echo $response;

