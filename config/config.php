<?php
header("Content-type: text/html; charset=utf-8");
global $mysqli;
$mysqli = new mysqli('localhost','root','','kntu_db');
$mysqli->set_charset("utf8");

?>
