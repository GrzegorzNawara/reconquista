<?php

/*
// IN
session_id

// OUT
error

// FUNCTION
Read user data

*/


// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	
	$game_id=$_GET["game_id"];
	$user_id=$_GET["user_id"];
	

	include('inc_game.php');
	
	$status=_read_full_status($game_id, 1);
	
	for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
		
		if($status[$current_player][STATUS_USER_ID]==$user_id) {
		
			$status[$current_player][STATUS_CONFIRMED]=1;
			$status[$current_player][STATUS_CONFIRM_TIME]=time();
			
			_write_($game_id, $current_player, $status[$current_player]);
		}
	}
		
	$return_data=array();
	$return_data['error']='';

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

	echo json_encode($return_data);

?>