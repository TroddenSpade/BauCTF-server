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

$pid = $mysqli->escape_string($data['pid']);
$code = $mysqli->escape_string($data['code']);
$link = $mysqli->escape_string($data['link']);
$body = $mysqli->escape_string($data['body']);
$score = $mysqli->escape_string($data['score']);
$author = $mysqli->escape_string($data['author']);
$state = $mysqli->escape_string($data['state']);

$response = array();

if (
    isset($pid) &&
    isset($code) &&
    !empty($link) &&
    !empty($body) &&
    isset($score) &&
    !empty($author) &&
    isset($state)
) {
    
    if ($mysqli->query("INSERT INTO problems(pid,code,link,body,score,author,state) VALUES('$pid','$code','$link','$body','$score','$author','$state')")
        === TRUE) {
        http_response_code(200);
        $response['pid']=$pid;
        $response['body']=$body;
    } else {
        http_response_code(400);
        $response['message'] = "Error creating User: " . $mysqli->error;;
    }

} else {
    http_response_code(400);
    $response['message'] = "Incomplete Data.";
}

$response = json_encode($response);
echo $response;

