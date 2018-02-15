<?php

// Report simple running errors
error_reporting(0);




define('CRLF', "\r\n");
define('MAX_NUMBER_OF_PLAYERS', 12);
define('BYTES_FOR_GAME_STATUS', 4000);
define('BYTES_PER_PLAYER', 1000);
define('FILLING_SPACES', str_repeat(" ", BYTES_PER_PLAYER));


define('STATUS_ACTIVE',0);
define('STATUS_PLAYER_DESCRIPTION',1);
define('STATUS_ROLE',2);
define('STATUS_USER_ID',3);
define('STATUS_ONEWAY',4);
define('STATUS_TEAMWIN',5);
define('STATUS_FOLLOW',6);
define('STATUS_OPTIONS_EXPECTED_REWARD',7);
define('STATUS_TARGET_REWARD',8);
define('STATUS_PROFIT_OPTION',9);
define('STATUS_OPTIONS_SHOWN_REWARD',10);
define('STATUS_ROLE_BONUS',11);
define('STATUS_PLAYER_ID',13);
define('STATUS_CHOOSEN_OPTION',14);
define('STATUS_TIME_REMAINING',15);
define('STATUS_REWARD',16);
define('STATUS_CHOICE_TIME',17);
define('STATUS_CONFIRM_TIME',18);
define('STATUS_CONFIRMED',19);
define('STATUS_VOTES',20);
define('STATUS_OPT_1_RESULT',21);
define('STATUS_OPT_2_RESULT',22);
define('STATUS_OPT_3_RESULT',23);
define('STATUS_OPT_1_US_RESULT',24);
define('STATUS_OPT_2_US_RESULT',25);
define('STATUS_OPT_3_US_RESULT',26);


define('GAME_STRUCTURE_VERSION',100);
define('STATUS_STATUS_PROPERTIES_MIN_II',100);
define('GAME_START_TIME',101);
define('GAME_FINISH_TIME',102);
define('GAME_TEAM_RESULT',103);
define('GAME_TEAM_CONFIRMED',104);
define('GAME_OPTIONS_VOTES',105);
define('GAME_OPTIONS_LIMITS',106);
define('GAME_ACT_TEAM_REWARD',107);
define('GAME_MAX_TEAM_REWARD',108);
define('GAME_MAX_CHOICE',109);
define('GAME_DECISION_QUALITY',110);
define('GAME_REMAINING_TIME',111);
define('GAME_MAX_ROLE_REWARD',112);

// op_array( needed votes, min reward, max reward)
define('OPT_NEEDED_VOTES',0);
define('OPT_MIN_REWARD',1);
define('OPT_MAX_REWARD',2);




function _init_game_data($game_id) {

	if(!file_exists('./game-data/'.$game_id.'.txt')){
		
		$fp=fopen('./game-data/'.$game_id.'.txt', 'ab');
		
			$game_status_array=array();
			$game_status_array[GAME_STRUCTURE_VERSION]='Choices v2.0 ('.date('Y-m-d').')';
			$game_status_array[GAME_START_TIME]=time();
			$game_status_array[GAME_FINISH_TIME]=time()+60*10;
			$game_status_array[GAME_TEAM_RESULT]=0;
			
			$game_status_array[GAME_MAX_CHOICE]=array(1,1,1,1,1,1,1,1,1,1,1,1);
			
			// GENERATE OPTIONS
			// votes needed, min reward, max reward
			for($current_option=1; $current_option<7; $current_option++) {
				
				$tmp_exp_reward=rand(50,80);
				$needed_votes=rand(7,15);
				$game_status_array[GAME_OPTIONS_LIMITS][$current_option][OPT_NEEDED_VOTES]=$needed_votes;
				$game_status_array[GAME_OPTIONS_LIMITS][$current_option][OPT_MIN_REWARD]=4*$needed_votes*rand(1,5);
				$game_status_array[GAME_OPTIONS_LIMITS][$current_option][OPT_MAX_REWARD]=10*round(rand($game_status_array[GAME_OPTIONS_LIMITS][$current_option][OPT_MIN_REWARD]+5,$game_status_array[GAME_OPTIONS_LIMITS][$current_option][OPT_MIN_REWARD]+50)/10);
			}
			// GENERATE OPTIONS
			
			
			$line=json_encode($game_status_array);
			
			fwrite($fp, substr($line.str_repeat(' ',BYTES_FOR_GAME_STATUS), 0, BYTES_FOR_GAME_STATUS));
		
			for($ii=0; $ii<MAX_NUMBER_OF_PLAYERS; $ii++) {
			
				$status_array=array();
				$status_array[STATUS_ACTIVE]=0;
				$status_array[STATUS_CHOOSEN_OPTION]=0;//rand(1,3);
				$status_array[STATUS_PLAYER_ID]=$ii;
				$status_array[STATUS_REWARD]=0;
				$status_array[STATUS_TARGET_REWARD]=0;
				$status_array[STATUS_CONFIRM_TIME]=0;
				$status_array[STATUS_CONFIRMED]=0;
				$status_array[STATUS_ROLE_BONUS]=0;
				$status_array[STATUS_USER_ID]=-1;
				
				for($current_option=1; $current_option<7; $current_option++)
					$status_array[STATUS_OPTIONS_SHOWN_REWARD][$current_option]='?';
	
	
				$line=json_encode($status_array);
			
				fwrite($fp, substr(CRLF.$line.FILLING_SPACES, 0, BYTES_PER_PLAYER));
			}
		fclose($fp);
		
		init_roles($game_id);
	}
}


function _write_($game_id, $player_ii, $status_array) {

   _init_game_data($game_id);
    
    $status_array[STATUS_PLAYER_DESCRIPTION]='';
  
     //REMOVE GAME STATUS
    $clean_status_array=array();
    foreach($status_array as $key => $val) {
	
	if($key<STATUS_STATUS_PROPERTIES_MIN_II)
		$clean_status_array[$key]=$val;
    }
    $status_array=$clean_status_array;
     //REMOVE GAME STATUS


     //WRITE
    $line=json_encode($status_array);
    $fp=fopen('./game-data/'.$game_id.'.txt', 'r+b');
	
	fseek($fp, BYTES_FOR_GAME_STATUS + BYTES_PER_PLAYER*$player_ii);
        fwrite($fp, substr(CRLF.$line.FILLING_SPACES, 0, BYTES_PER_PLAYER));
	
    fclose($fp);
    //WRITE
}


function _write_game_status($game_id, $status_array) {

   _init_game_data($game_id);
  
     //REMOVE PLAYER STATUS
    $game_status_array=array();
    foreach($status_array as $key => $val) {
	
	if($key>=STATUS_STATUS_PROPERTIES_MIN_II)
		$game_status_array[$key]=$val;
    }
     //REMOVE PLAYER STATUS


    //WRITE GAME STATUS
    $fp=fopen('./game-data/'.$game_id.'.txt', 'r+b');
	
		$line=json_encode($game_status_array);
		fwrite($fp, substr($line.str_repeat(' ',BYTES_FOR_GAME_STATUS), 0, BYTES_FOR_GAME_STATUS));
		
    fclose($fp);
    //WRITE GAME STATUS
}


function _read_($game_id, $player_ii) {
	
    _init_game_data($game_id);
    
    $fp=fopen('./game-data/'.$game_id.'.txt', 'rb');
   
	$game_line=fread($fp, BYTES_FOR_GAME_STATUS);
    
        fseek($fp, BYTES_FOR_GAME_STATUS + BYTES_PER_PLAYER*$player_ii+2);
	$line=fread($fp, BYTES_PER_PLAYER-2);
    fclose($fp);

    
    $status_array=json_decode(trim($line),true);
    
    $game_array=json_decode(trim($game_line),true);
    foreach($game_array as $key => $val) {
    
	$status_array[$key]=$val;
    }

   
   //READ ROLE 
   $status_array=choose_role($status_array, $player_ii);
   //READ ROLE 


    return($status_array);
}


function _read_full_status($game_id, $return_players_only=0) {

    $full_status_array=array();
    
    
    _init_game_data($game_id);
   
   
    $fp=fopen('./game-data/'.$game_id.'.txt', 'rb');
   
	$line=fread($fp, BYTES_FOR_GAME_STATUS);
	
	if($return_players_only==0)
		$full_status_array['game']=json_decode(trim($line),true);


	for($ii=0; $ii<MAX_NUMBER_OF_PLAYERS; $ii++) {
    
		fseek($fp, BYTES_FOR_GAME_STATUS + BYTES_PER_PLAYER*$ii+2);
		$line=fread($fp, BYTES_PER_PLAYER-2);
		$full_status_array[$ii]=json_decode(trim($line),true);
		
		 //READ ROLE 
		$full_status_array[$ii]=choose_role($full_status_array[$ii], $ii);
		 //READ ROLE 
	}
	
    fclose($fp);

    return($full_status_array);
}


function init_roles($game_id) {

	_init_game_data($game_id);
	
	$player_ii=-1;	
	for($ii=0; $ii<MAX_NUMBER_OF_PLAYERS; $ii++) {
	
		
		$status_array=_read_($game_id, $ii);
		
		
		if($status_array[STATUS_ACTIVE]==0) {
			
			$player_ii=$ii;
			
			$status_array[STATUS_ACTIVE]=1;
			$status_array[STATUS_ROLE_BONUS]=0;
			$status_array[STATUS_PROFIT_OPTION]=rand(1,6);
			$status_array=choose_role($status_array, $ii);
		
			for($current_option=1; $current_option<=6; $current_option++) {
				
				
				$status_array[STATUS_OPTIONS_SHOWN_REWARD][$current_option]='?';
				$status_array[STATUS_OPTIONS_EXPECTED_REWARD][$current_option]=10*round(rand($status_array[GAME_OPTIONS_LIMITS][$current_option][OPT_MIN_REWARD],$status_array[GAME_OPTIONS_LIMITS][$current_option][OPT_MAX_REWARD])/10);
				
				if(rand(0,100)<30)
					$status_array[STATUS_OPTIONS_SHOWN_REWARD][$current_option]=$status_array[STATUS_OPTIONS_EXPECTED_REWARD][$current_option];
			}
		
			_write_($game_id, $player_ii, $status_array);
			
			//break
			//$ii=MAX_NUMBER_OF_PLAYERS;
		}
	}
}










// UPDATE VOTES
function update_votes($game_id) {

	find_maxgame($game_id);
	
	$status=_read_full_status($game_id);


	$status['game'][GAME_TEAM_CONFIRMED]=0;
	$status['game'][GAME_TEAM_RESULT]=0;

	for($current_option=1; $current_option<=6; $current_option++)
		$status['game'][GAME_OPTIONS_VOTES][$current_option]=0;
		
		
	
	
	


	$sum_of_votes=0;
	for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
		
		$sum_of_votes+=$status[$current_player][STATUS_VOTES];
		
		for($current_option=1; $current_option<=6; $current_option++) {
	
			if($status[$current_player][STATUS_CHOOSEN_OPTION]==$current_option)
				$status['game'][GAME_OPTIONS_VOTES][$current_option]+=$status[$current_player][STATUS_VOTES];
		}
	}	
		
		
	//CONFIRMATION
	$tmp_roles_confirmed=0;
	for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
		if($status[$current_player][STATUS_CONFIRMED]==1) {
		
			$tmp_roles_confirmed++;			
		}
	}
	$status['game'][GAME_TEAM_CONFIRMED]=round(100*$tmp_roles_confirmed/MAX_NUMBER_OF_PLAYERS);
	//CONFIRMATION
	
	
	$status['game'][GAME_REMAINING_TIME]=$status['game'][GAME_FINISH_TIME]-time()-(60*$tmp_roles_confirmed);	
	
	if($status['game'][GAME_REMAINING_TIME]<0)
		$status['game'][GAME_REMAINING_TIME]=0;
	
	
	_write_game_status($game_id, $status['game']);
	
	
	
	for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
			
		$status[$current_player][STATUS_REWARD]+=1*($status[$current_player][STATUS_TARGET_REWARD]-$status[$current_player][STATUS_REWARD]);
		_write_($game_id, $current_player, $status[$current_player]);	
	}
}
//UPDATE VOTES


function choose_option($game_id, $player_id, $option) {

	$status=_read_full_status($game_id);
	
	if(1) { //$status[$player_id][STATUS_CONFIRMED]==0) {
	
		$status[$player_id][STATUS_CHOOSEN_OPTION]=$option;
		$status[$player_id][STATUS_CHOICE_TIME]=time();
		
		$status=calc_points($status);
		
		for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
			
			_write_($game_id, $current_player, $status[$current_player]);
		}
	}
}


function find_maxgame($game_id) {

	$status=_read_full_status($game_id);

	$maxgame=array();
	$maxgame['act_team_reward']=0;
	$maxgame['tmp_team_reward']=0;
	
	// ACTUAL
	$status=calc_points($status);
		
	for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
		$maxgame['act_team_reward']+=$status[$current_player][STATUS_TARGET_REWARD];
	}
	// ACTUAL
	
	$maxgame['tmp_team_reward']=$maxgame['act_team_reward'];
	$maxgame['max_team_reward']=$status['game'][GAME_MAX_TEAM_REWARD];
	$maxgame['max_role_reward']=$status['game'][GAME_MAX_ROLE_REWARD];
	$maxgame['max_choice']=$status['game'][GAME_MAX_CHOICE];
	
	
	if($maxgame['max_team_reward']<$maxgame['tmp_team_reward']) {
			
		for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
		
			$maxgame['max_role_reward'][$current_player]=$status[$current_player][STATUS_TARGET_REWARD];
			$maxgame['max_choice'][$current_player]=$status[$current_player][STATUS_CHOOSEN_OPTION];
			
			if(rand(0,100)<60)
				$maxgame['max_choice'][$current_player]=rand(1,6);
		}
		
		$maxgame['max_team_reward']=$maxgame['tmp_team_reward'];
	}
	
	
	for($case=0; $case<100; $case++) {
	
		$maxgame['tmp_team_reward']=0;
		
		// CHOOSING RANDOM OPTIONS
		for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
		
			$status[$current_player][STATUS_CONFIRMED]=0;
			//$status[$current_player][STATUS_CHOOSEN_OPTION]=rand(1,6);
			$status[$current_player][STATUS_CHOICE_TIME]=5; //$current_player; //simulate time
			
			$status[$current_player][STATUS_CHOOSEN_OPTION]=$maxgame['max_choice'][$current_player];
			
			if(rand(0,100)<60)
				$status[$current_player][STATUS_CHOOSEN_OPTION]=rand(1,6);
			
			
		}
		// CHOOSING RANDOM OPTIONS


		$status=calc_points($status);

		
		for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
			$maxgame['tmp_team_reward']+=$status[$current_player][STATUS_TARGET_REWARD];
		}
		
		if($maxgame['max_team_reward']<$maxgame['tmp_team_reward']) {
			
			for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
			
				$maxgame['max_role_reward'][$current_player]=$status[$current_player][STATUS_TARGET_REWARD];
				$maxgame['max_choice'][$current_player]=$status[$current_player][STATUS_CHOOSEN_OPTION];
			}
			
			$maxgame['max_team_reward']=$maxgame['tmp_team_reward'];
		}
	}
	
	$maxgame['quality']=round($maxgame['act_team_reward']/(1+$maxgame['max_team_reward']),3);
	
	$status['game'][GAME_ACT_TEAM_REWARD]=$maxgame['act_team_reward'];
	$status['game'][GAME_MAX_TEAM_REWARD]=$maxgame['max_team_reward'];
	$status['game'][GAME_MAX_CHOICE]=$maxgame['max_choice'];
	$status['game'][GAME_DECISION_QUALITY]=$maxgame['quality'];
	$status['game'][GAME_MAX_ROLE_REWARD]=$maxgame['max_role_reward'];
			
	_write_game_status($game_id, $status['game']);

	return($maxgame);
}



// CALCULATE POINTS
function calc_points($status) {

	for($current_option=1; $current_option<=6; $current_option++)
		$status['game'][GAME_OPTIONS_VOTES][$current_option]=0;
			
	$king_player=-1;	
	$rebel_player=-1;
	$levy_player=-1;
	for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
		
		if($status[$current_player][STATUS_ROLE]=='KING')
			$king_player=$current_player;
		
		if($status[$current_player][STATUS_ROLE]=='REBEL')
			$rebel_player=$current_player;
		
		if($status[$current_player][STATUS_ROLE]=='LEVY')
			$levy_player=$current_player;
			
		
		for($current_option=1; $current_option<=6; $current_option++) {
	
			if($status[$current_player][STATUS_CHOOSEN_OPTION]==$current_option) {
			
				$status['game'][GAME_OPTIONS_VOTES][$current_option]+=$status[$current_player][STATUS_VOTES];
			}
		}
	}
	
	for($current_player=0; $current_player<MAX_NUMBER_OF_PLAYERS; $current_player++) {
		
		if(1) { //$status[$current_player][STATUS_CONFIRMED]==0) {
			
			$status[$current_player][STATUS_TARGET_REWARD]=0;
			
			for($current_option=1; $current_option<=6; $current_option++) {
				
				if($status['game'][GAME_OPTIONS_VOTES][$current_option]>=$status['game'][GAME_OPTIONS_LIMITS][$current_option][OPT_NEEDED_VOTES]) {
					
					$status[$current_player][STATUS_TARGET_REWARD]+=$status[$current_player][STATUS_OPTIONS_EXPECTED_REWARD][$current_option];
					$status[$current_player][STATUS_OPTIONS_SHOWN_REWARD][$current_option]=$status[$current_player][STATUS_OPTIONS_EXPECTED_REWARD][$current_option];
				}
			}
			
			
			//ROLE REWARDS
			if($status[$current_player][STATUS_ROLE]=='KING') {
			
				$status[$current_player][STATUS_ROLE_BONUS]=0;
				
				$status[$current_player][STATUS_ROLE_BONUS]=round(1*$status[$current_player][STATUS_TARGET_REWARD]);
				$status[$current_player][STATUS_TARGET_REWARD]+=$status[$current_player][STATUS_ROLE_BONUS];
			
			}
			
			
			if($status[$current_player][STATUS_ROLE]=='KNIGHT') {
			
				$status[$current_player][STATUS_ROLE_BONUS]=0;
				
				if(	$status[$current_player][STATUS_CHOOSEN_OPTION]==$status[$king_player][STATUS_CHOOSEN_OPTION]
					//and $status[$current_player][STATUS_CHOICE_TIME]>$status[$king_player][STATUS_CHOICE_TIME]
				) {
				
					$status[$current_player][STATUS_ROLE_BONUS]=100;
					$status[$current_player][STATUS_TARGET_REWARD]+=$status[$current_player][STATUS_ROLE_BONUS];
				}
			}
			
			
			if($status[$current_player][STATUS_ROLE]=='REBEL') {
			
				$status[$current_player][STATUS_ROLE_BONUS]=0;
				
				if(	$status[$current_player][STATUS_CHOOSEN_OPTION]!=$status[$king_player][STATUS_CHOOSEN_OPTION]
					//or $status[$current_player][STATUS_CHOICE_TIME]<$status[$king_player][STATUS_CHOICE_TIME])
					and $status[$current_player][STATUS_CHOOSEN_OPTION]!=$status[$levy_player][STATUS_CHOOSEN_OPTION]
					//or $status[$current_player][STATUS_CHOICE_TIME]<$status[$levy_player][STATUS_CHOICE_TIME])
				) {
				
					for($another_player=0; $another_player<MAX_NUMBER_OF_PLAYERS; $another_player++) {
					
						if($status[$current_player][STATUS_CHOOSEN_OPTION]==$status[$another_player][STATUS_CHOOSEN_OPTION]) {
						
							$status[$current_player][STATUS_ROLE_BONUS]+=50;
						}
					}
					$status[$current_player][STATUS_TARGET_REWARD]+=$status[$current_player][STATUS_ROLE_BONUS];
				}
			}
			
			
			if($status[$current_player][STATUS_ROLE]=='MERCHANT') {
			
				$status[$current_player][STATUS_ROLE_BONUS]=0;
				if($status[$current_player][STATUS_CHOOSEN_OPTION]==$status[$current_player][STATUS_PROFIT_OPTION]) {
					
					$status[$current_player][STATUS_ROLE_BONUS]=100;
				}
				
				for($another_player=0; $another_player<MAX_NUMBER_OF_PLAYERS; $another_player++) {
				
					if(	$status[$current_player][STATUS_CHOOSEN_OPTION]==$status[$another_player][STATUS_CHOOSEN_OPTION]
						and $status[$another_player][STATUS_ROLE]=='LEVY'
					) {
					
						$status[$current_player][STATUS_ROLE_BONUS]=max(0, $status[$current_player][STATUS_ROLE_BONUS]-80);
					}
				}
				
				$status[$current_player][STATUS_TARGET_REWARD]+=$status[$current_player][STATUS_ROLE_BONUS];
			}
			
			
			if($status[$current_player][STATUS_ROLE]=='FARMER') {
			
				$status[$current_player][STATUS_ROLE_BONUS]=0;
				
				for($another_player=0; $another_player<MAX_NUMBER_OF_PLAYERS; $another_player++) {
				
					if(	$status[$current_player][STATUS_CHOOSEN_OPTION]==$status[$another_player][STATUS_CHOOSEN_OPTION]
						and $status[$another_player][STATUS_ROLE]=='FARMER'
					) {
					
						$status[$current_player][STATUS_ROLE_BONUS]+=50;
					}
				}
				
				for($another_player=0; $another_player<MAX_NUMBER_OF_PLAYERS; $another_player++) {
				
					if(	$status[$current_player][STATUS_CHOOSEN_OPTION]==$status[$another_player][STATUS_CHOOSEN_OPTION]
						and $status[$another_player][STATUS_ROLE]=='LEVY'
					) {
					
						$status[$current_player][STATUS_ROLE_BONUS]=max(0, $status[$current_player][STATUS_ROLE_BONUS]-200);
					}
				}
				
				$status[$current_player][STATUS_TARGET_REWARD]+=$status[$current_player][STATUS_ROLE_BONUS];				
			}
			
			
			
			if($status[$current_player][STATUS_ROLE]=='LEVY') {
			
				$status[$current_player][STATUS_ROLE_BONUS]=0;
				
				if(	$status[$current_player][STATUS_CHOOSEN_OPTION]!=$status[$rebel_player][STATUS_CHOOSEN_OPTION]
					//or $status[$current_player][STATUS_CHOICE_TIME]<$status[$rebel_player][STATUS_CHOICE_TIME])
				) {
				
					for($another_player=0; $another_player<MAX_NUMBER_OF_PLAYERS; $another_player++) {
					
						if(	$status[$current_player][STATUS_CHOOSEN_OPTION]==$status[$another_player][STATUS_CHOOSEN_OPTION]
							and $status[$another_player][STATUS_ROLE]=='FARMER'
						) {
						
							$status[$current_player][STATUS_ROLE_BONUS]+=50;
						}
					}
					
					for($another_player=0; $another_player<MAX_NUMBER_OF_PLAYERS; $another_player++) {
					
						if(	$status[$current_player][STATUS_CHOOSEN_OPTION]==$status[$another_player][STATUS_CHOOSEN_OPTION]
							and $status[$another_player][STATUS_ROLE]=='MERCHANT'
						) {
						
							$status[$current_player][STATUS_ROLE_BONUS]+=50;
						}
					}
					
					$status[$current_player][STATUS_TARGET_REWARD]+=$status[$current_player][STATUS_ROLE_BONUS];
				}
			}
			
			//ROLE REWARDS
		}
	}
		
	return($status);
}
// CALCULATE POINTS



//CHOOSE ROLE FOR PLAYER ii
function choose_role($status_array, $role_ii) {

	/*
	$status_array[STATUS_PLAYER_DESCRIPTION]['opt1']='Opt 1';
	$status_array[STATUS_PLAYER_DESCRIPTION]['opt2']='Opt 2';
	$status_array[STATUS_PLAYER_DESCRIPTION]['opt3']='Opt 3';
	$status_array[STATUS_PLAYER_DESCRIPTION]['opt4']='Opt 4';
	$status_array[STATUS_PLAYER_DESCRIPTION]['opt5']='Opt 5';
	$status_array[STATUS_PLAYER_DESCRIPTION]['opt6']='Opt 6';
	*/
	//$status_array[GAME_OPTIONS_VOTES]['actual']=array(0,0,0,0,0,0);
	
	$came_from=array();
	$came_from[1]="z Gór";
	$came_from[2]="z Łąk";
	$came_from[3]="z Lasów";
	$came_from[4]="z Miasta";
	$came_from[5]="z Morza";
	$came_from[6]="z Pustyni";


	if($role_ii==0) {
		
		$status_array[STATUS_PLAYER_DESCRIPTION]['role']='Król';
		
		$status_array[STATUS_ROLE]='KING';
		$status_array[STATUS_VOTES]=5;
	} 
	elseif($role_ii<=2) {
		
		$status_array[STATUS_PLAYER_DESCRIPTION]['role']='Rycerz '.($role_ii);
		
		$status_array[STATUS_ROLE]='KNIGHT';
		$status_array[STATUS_VOTES]=3;
	} 
	elseif($role_ii<=3) {
		
		$status_array[STATUS_PLAYER_DESCRIPTION]['role']='Buntownik';
		
		$status_array[STATUS_ROLE]='REBEL';
		$status_array[STATUS_VOTES]=4;
	}
	elseif($role_ii<=4) {
		
		$status_array[STATUS_PLAYER_DESCRIPTION]['role']='Poborca';
		
		$status_array[STATUS_ROLE]='LEVY';
		$status_array[STATUS_VOTES]=4;
	} 	
	elseif($role_ii<=7) {
		
		$status_array[STATUS_PLAYER_DESCRIPTION]['role']='Kupiec '.$came_from[$status_array[STATUS_PROFIT_OPTION]];
		
		$status_array[STATUS_ROLE]='MERCHANT';
		$status_array[STATUS_VOTES]=3;
	} 
	else {	
		
		$status_array[STATUS_PLAYER_DESCRIPTION]['role']='Chłop '.($role_ii-7);
		
		$status_array[STATUS_ROLE]='FARMER';
		$status_array[STATUS_VOTES]=2;
	}
	
	
	return($status_array);

}
//CHOOSE ROLE FOR PLAYER ii










?>