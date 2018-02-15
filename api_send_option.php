<?php

/*
// IN
session_id
option

// OUT
error

// FUNCTION
Read user data

*/


// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	
	$game_id=$_GET["game_id"];
	$page=$_GET["page"];
	$option=$_GET["option"];
	

	include('inc_game.php');
	
	choose_option($game_id, $page, $option);
		
	$return_data=array();
	$return_data['error']='';

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

	echo json_encode($return_data);

?>