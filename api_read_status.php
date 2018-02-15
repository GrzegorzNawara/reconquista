<?php

/*
// IN
$game_id
$game_id

// OUT
$game_id
status (all the game knows about the player)
error

// FUNCTION
Read user data

*/


// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	
	$game_id=$_GET["game_id"];
	

	include('inc_game.php');
	
	
	update_votes($game_id);
	
	
	$return_data=array();
	$return_data['game_id']=$game_id;
	$return_data['status']=_read_($game_id, 0);
	$return_data['full_status']=_read_full_status($game_id, 1);
	$return_data['error']='';

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

	echo json_encode($return_data);

?>