<?php

header("Access-Control-Allow-Origin: http://localhost:8080");
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
$username = $mysqli->escape_string($data['username']);
$password = $mysqli->escape_string($data['password']);
$response = array();

$result = $mysqli->query("SELECT token FROM users WHERE username='$username' AND password='$password' LIMIT 1");
if ($result->num_rows > 0) {

    $user_row = $result->fetch_assoc();
    $token = $user_row['token'];
    http_response_code(200);

    $response['token'] = $token;
    $response['username'] = $username;

} else {
    http_response_code(400);
    $response['error'] = "User does not exist!";
}

$response = json_encode($response);
echo $response;
