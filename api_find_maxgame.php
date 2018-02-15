<?php

/*
// IN
session_id

// OUT
$game_id
$maximum points possible
error

// FUNCTION
Read user data

*/


// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	
	$game_id=$_GET["game_id"];
	

	include('inc_game.php');
	
	
	$return_data=array();
	$return_data['game_id']=$game_id;
	$return_data['status']=find_maxgame($game_id);
	$return_data['error']='';

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

	echo json_encode($return_data);

?>