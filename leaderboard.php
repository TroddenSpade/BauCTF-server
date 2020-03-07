<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Origin,Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/config.php';
global $mysqli;
    
$ip_block = array(
        
);

$rawBody = file_get_contents("php://input");
$data = array();
$data = json_decode($rawBody, true);
    
if (isset($_SERVER['HTTP_CLIENT_IP']))
{
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
    
$blocked = false;
foreach($ip_block as $ip)
{
    if($client_ip == $ip)
    {
        $blocked = true;
    }
}

$kntuTime = strtotime("2020-03-06 16:00:00");
$openTime = strtotime("2020-03-06 19:30:00");

$state = $mysqli->escape_string($data['state']);
$open = $mysqli->escape_string($data['open']);

$response = array();

if( isset($state) && !$blocked ){
    $mysqli->query("INSERT INTO leader_req(ip) VALUES('$client_ip')");
    $users = $mysqli->query("SELECT username,uid FROM users WHERE open = '$open'");

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
            if($open==1){
                $diff = strtotime($p_row['ts']) - $openTime;
            }else{
                $diff = strtotime($p_row['ts']) - $kntuTime;
            }
            $p_row['time'] = date("H:i", $diff);
            array_push($list['subs'],$p_row);
        }
        $list['username'] = $username;
        $list['total_score'] = $total_score;
        $list['total_ts'] = $total_ts;
        
        array_push($response,$list);
    }

}else{
   if($blocked){
       $response['error'] = "You are Temporarily Blocked";
   }else{
       $response['error'] = "Incomplete Data.";
   }
   http_response_code(400);
}

$response = json_encode($response);
echo $response;
