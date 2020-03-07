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

$username = $mysqli->escape_string($data['username']);
$password = $mysqli->escape_string($data['password']);
$open = $mysqli->escape_string($data['open']);
$email = $mysqli->escape_string($data['email']);

$response = array();
    
http_response_code(400);
$response['error']="The Registration is closed !";

if (
   !empty($username) &&
   !empty($password)
) {
   $result = $mysqli->query("SELECT username FROM users WHERE username='$username' or email='$email'");
   if ($result->num_rows > 0) {
       http_response_code(400);
       $response['error']="username already exists!";
   } else {
       $token = uniqid($username);

       if ($mysqli->query("INSERT INTO users(username,password,token,open,email) VALUES('$username','$password','$token','$open','$email')")
           === TRUE) {
           http_response_code(200);
           $response['token']=$token;
           $response['username']=$username;
           $response['open']=$open;
       } else {
           http_response_code(400);
           $response['error'] = "Error creating User: " . $mysqli->error;
       }
   }

} else {
   http_response_code(400);
   $response['error'] = "Incomplete Data.";
}

$response = json_encode($response);
echo $response;

