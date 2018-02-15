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
	$role_id=$_GET["role_id"];


	include('inc_game.php');


	$status_array=_read_($game_id, $role_id);
	$status_array[STATUS_USER_ID]=$user_id;
	//_write_($session['game_id'], $session['player_id'], $status_array);
	_write_($game_id, $role_id, $status_array);


	$return_data=array();
	$return_data['error']='';

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

	echo json_encode($return_data);

?>
