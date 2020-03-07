<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Origin,Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/config.php';
global $mysqli;


if (isset($_SERVER['HTTP_CLIENT_IP'])){

    $client_ip = $_SERVER['HTTP_CLIENT_IP'];

} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {

    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

} else if(isset($_SERVER['HTTP_X_FORWARDED'])) {

    $client_ip = $_SERVER['HTTP_X_FORWARDED'];

} else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {

    $client_ip = $_SERVER['HTTP_FORWARDED_FOR'];

} else if(isset($_SERVER['HTTP_FORWARDED'])) {

    $client_ip = $_SERVER['HTTP_FORWARDED'];

} else if(isset($_SERVER['REMOTE_ADDR'])) {

    $client_ip = $_SERVER['REMOTE_ADDR'];

}

$rawBody = file_get_contents("php://input");
$data = array();
$data = json_decode($rawBody, true);
$username = $mysqli->escape_string($data['username']);
$password = $mysqli->escape_string($data['password']);
$response = array();

$result = $mysqli->query("SELECT token,open FROM users WHERE username='$username' AND password='$password' LIMIT 1");
if ($result->num_rows > 0) {

    $user_row = $result->fetch_assoc();
    $token = $user_row['token'];
    http_response_code(200);

    $response['token'] = $token;
    $response['username'] = $username;
    $response['open'] = $user_row['open'];

} else {
    http_response_code(400);
    $response['error'] = "invalid username or password!";
}

$response = json_encode($response);
echo $response;
