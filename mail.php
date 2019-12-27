<?php

include_once 'config/config.php';
global $mysqli;

$url = 'data.json';
$data = file_get_contents($url);
$teams = json_decode($data);
 
$from = 'support@kntuctf.ir';

$headers = 'From: KNTUCTF '.$from . "\r\n" ;
$headers .='Reply-To: '. $to . "\r\n" ;
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .='X-Mailer: PHP/' . phpversion();

if($_GET["pass"]=="1234")
foreach ($teams as $team) {
	$chars = "abcdefghijklmnopqrstuvwxyz0123456789!@#$%&*_";
    do{
		$pass = substr(str_shuffle($chars), 0, 6);
		$result = $mysqli->query("SELECT password FROM users WHERE password='$pass'");
	}while($result->num_rows > 0) ;
	
	$user = $team->name;
	$token = uniqid($user);
	$to = $team->email;
	$subject = 'اطلاعات شرکت در مسابقه';
	$message = '<html><body>'; 
	$message .= '<h3>'.$user.' , </h3>';
	$message .= '<p> :اطلاعات ورود به سایت مسابقه برای تیم شما به شرح زیر است</p>';
	$message .= '<p>username:'.$user.'</p>';
	$message .= '<p>password:'.$pass.'</p>';
	$message .= '<p>قبل از مسابقه برای تست اطلاعات ورود در سایت وارد شوید.</p>';
	$message .= '<p> telegram : @kntuctfs پشتیبانی </p>';
	$message .= '<p> این نامه بصورت خودکار برای شما ارسال شده است. لطفا به آن پاسخ ندهید. </p>';
	$message .= '</body></html>';

	$result_user = $mysqli->query("SELECT username FROM users WHERE username='$user'");
    if ($result_user->num_rows > 0) {
        echo "user ".$user." already exists!\r\n";
    } else {
        if ($mysqli->query("INSERT INTO users(username,password,token) VALUES('$user','$pass','$token')")
            === TRUE) {
			if(mail($to, $subject, $message, $headers)){
				echo 'Your mail has been sent to '.$to.' successfully.'."\r\n";
			} else{
				echo 'Something went wrong sending to '.$to."\r\n";
			}
        } else {
            http_response_code(400);
            echo "Error creating User: ".$user . $mysqli->error."\r\n";
        }
	}
}

?>