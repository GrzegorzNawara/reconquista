<?php

/*
// IN
err

// FUNCTION
Send error

*/

// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$err=$_GET["err"];

//$err = json_decode($err, true);
$err = "\r\n\r\n".date('h:i').' '.urldecode($err);

$line=$err;
	/*
		['date']
		.'$UserAgent='.$err['UserAgent']
		.'$OS='.$err['OS']
		.'$Browser='.$err['Browser']
		.'$Device='.$err['Device']
		.'$OS_Version='.$err['OS_Version']
		.'$Browser_Version='.$err['Browser_Version']
		.'$Error='.$err['Error']."\r\n";
	*/
$fp=fopen('./game-errors/'.date('Ymd').'.txt', 'ab');
fwrite($fp, $line);
fclose($fp);



header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

echo json_encode($return_data);


?>