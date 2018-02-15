<html>
<?php

	$game_id='test'.rand(1,9);
	$user_id='u'.rand(11111,99999);
	
	if(file_exists('./game-data/'.$game_id.'.txt')) {
		
		unlink('./game-data/'.$game_id.'.txt');
		sleep(1);
	}
	
	echo '<a href="./#/'.$game_id.'/'.$user_id.'">CLICK ME</a><br>';
	
?>
</html>