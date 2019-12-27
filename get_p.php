<?php

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: https://samnotsum.kntuctf.ir');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

include_once 'config/config.php';
global $mysqli;

$ip = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];

$rawBody = file_get_contents("php://input");
$data = array();
$data = json_decode($rawBody, true);

$token = $mysqli->escape_string($data['token']);
$state = $mysqli->escape_string($data['state']);

$response = array();

if( !empty($token) ){
    $result = $mysqli->query("SELECT token FROM users WHERE token='$token' LIMIT 1");

    if($result->num_rows>0){
        $q = $mysqli->query("SELECT * FROM problems WHERE state='$state' ORDER BY pid ASC");
        if($q->num_rows>0){
            $problems = array();
            while($row = $q->fetch_assoc()) {
                $list = array();
                $list['pid'] = $row['pid'];
                $list['author'] = $row['author'];
                $list['link'] = $row['link'];
                $list['body'] = $row['body'];
                $list['title'] = $row['title'];
                $list['score'] = $row['score'];
                array_push($response,$list);
            }
        }else{
            http_response_code(400);
            $response['error'] = "q doesnt work !";
        }
    }else{
        http_response_code(401);
        $response['error'] = "Access Unauthorized.";
    }

}else{

    http_response_code(400);
    $response['error'] = "Incomplete Data.";
}

$response = json_encode($response);
echo $response;
