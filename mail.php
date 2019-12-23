<?php

$url = 'data.json';
$data = file_get_contents($url);
$teams = json_decode($data);


foreach ($team as $teams) {
	echo $team['name'] . '\t'.$team['email'].'\n';
}

$teamName = "Hasan";
$user = "user123";
$pass = "pass4555";
$to = 'troddenspade@gmail.com';
$subject = 'اطلاعات شرکت در مسابقه';
$from = 'support@kntuctf.ir';
 
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html;' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: '.$from."\r\n".'Reply-To: '.$from."\r\n" .'X-Mailer: PHP/' . phpversion();
 
$message = '<html><body>';
$message .= '<h2>'.$teamName.' ,</h2>';
$message .= '<p>اطلاعات ورود به سایت مسابقه برای تیم شما به شرح زیر است</p>';
$message .= '<p>username:'.$user.'</p>';
$message .= '<p>password:'.$pass.'</p>';
$message .= '</body></html>';
 

if(mail($to, $subject, $message, $headers)){
    echo 'Your mail has been sent to '.$to.' successfully.';
} else{
    echo 'Something went wrong';
}
?>