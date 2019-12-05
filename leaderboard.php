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

$state = $mysqli->escape_string($data['state']);

$response = array();

if( isset($state) ){
    $users = $mysqli->query("SELECT username,uid FROM users");

    while($row = $users->fetch_assoc()) {
        $uid = $row['uid'];
        $username = $row['username'];
        $total_ts = 0;
        $total_score = 0;
        $list['subs'] = array();

        $problems = $mysqli->query("SELECT problems.pid,ts,score 
            FROM submission JOIN problems ON submission.pid = problems.pid AND submission.code = problems.code 
            WHERE uid = '$uid' AND state = '$state' GROUP BY  problems.pid ");

        while($p_row = $problems->fetch_assoc()){
            $total_score += $p_row['score'];
            $total_ts += strtotime($p_row['ts']); 
            $p_row['time'] = date("H:i", strtotime($p_row['ts']));
            array_push($list['subs'],$p_row);
        }
        $list['username'] = $username;
        $list['total_score'] = $total_score;
        $list['total_ts'] = $total_ts;
        
        array_push($response,$list);
    }

}else{

    http_response_code(400);
    $response['error'] = "Incomplete Data.";
}

$response = json_encode($response);
echo $response;
