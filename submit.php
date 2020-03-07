<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Origin,Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/config.php';
global $mysqli;

$rawBody = file_get_contents("php://input");
$data = array();
$data = json_decode($rawBody, true);

$token = $mysqli->escape_string($data['token']);
$ctf = $mysqli->escape_string($data['ctf']);
$pid = $mysqli->escape_string($data['pid']);
$bare_code = $mysqli->escape_string($data['bare_code']);

$response = array();

if (!empty($token) &&
   !empty($ctf) &&
   isset($pid)
   ) {
     http_response_code(400);
     $response['error'] = "Time's Up :)";

    //change open = 0 to open = 1 for accessing Open Users

   $user_query_result = $mysqli->query("SELECT uid FROM users WHERE open = '1' and token = '$token' LIMIT 1");
   if ($user_row = $user_query_result->fetch_assoc()) {
       $uid = $user_row['uid'];
       $get_problem_result = $mysqli->query("SELECT pid FROM problems WHERE pid = '$pid' AND code = '$ctf' LIMIT 1");
       if($problems = $get_problem_result->fetch_assoc()){
           if ($mysqli->query("INSERT INTO submission(uid,pid,code) VALUES ('$uid','$pid','$ctf')")
           === TRUE) {
               $response['msg'] = "Correct Flag.";
           } else {
               http_response_code(400);
               $response['error'] = "Error Submitting: " . $mysqli->error;
           }
       }else{
           if ($mysqli->query("INSERT INTO submission(uid,pid,code,bare_code) VALUES ('$uid','$pid','$ctf','$bare_code')")
           === TRUE) {
               $response['msg'] = "Wrong Flag!";
           } else {
               http_response_code(400);
               $response['error'] = "Error Submitting: " . $mysqli->error;
           }
       }
       http_response_code(200);

   } else {
       http_response_code(400);
       $response['error'] = "you are not allowed to submit";
   }

} else {
   http_response_code(400);
   $response['error'] = "Insufficient data.";
}
$response = json_encode($response);
echo $response;

