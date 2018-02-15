<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    
    <!-- Bootstrap CSS 	-->
    <link rel="stylesheet" href="./lib/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    
	<style>
	@import url('https://fonts.googleapis.com/css?family=Roboto:100,400');

	

	    h4 {
		font-size: 120%;
	    }
	    progress {
		
	    }

	.bg-white {
	    background-color: #fff;
	}

	.choosen_option {
	    background-color: #0f0;
	} 

	.container-fluid {
		max-width: 960px;
	}
	
	.progress {
		background-color: #c9e3e2;
		height: 30px;
	}
	.bg-base-dark {
		background-color: #3b7270;
		height: 30px;
	}
	.bg-base-light {
		background-color: #3b7243;
		height: 30px;
	}
	.bg-vote-dark {
		background-color: #739a98;
		height: 30px;
	}
	.bg-vote-light {
		background-color: #4a8e54;
		height: 30px;
	}
	
	.bg-body {
		
		background-color: #739a98;
	}
	
	.bg-my-light {
		
		background-color: #c9e3e2;
	}
	
	<?php   
		
			
		for($option=1; $option<7; $option++) {
			
			echo '
			.bg-cover-'.$option.' {
			  width: 100%;
			  height: 100px;
			  background: url("images/land'.$option.'-1.png") no-repeat center;
			  background-size: cover;
			}';
		}
	
	?>

	.card_choosen {
	
		border: 10px #3b7270 solid;
	}
	
	#loader-spin {
	  position: absolute;
	  left: 50%;
	  top: 50%;
	  z-index: 1;
	  width: 100px;
	  height: 100px;
	  margin: -75px 0 0 -75px;
	  border: 16px solid #739a98;
	  border-radius: 50%;
	  border-top: 16px solid #3b7270;
	  border-bottom: 16px solid #3b7270;
	  width: 100px;
	  height: 100px;
	  -webkit-animation: spin 2s linear infinite;
	  animation: spin 2s linear infinite;
	}

	@-webkit-keyframes spin {
	  0% { -webkit-transform: rotate(0deg); }
	  100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
	  0% { transform: rotate(0deg); }
	  100% { transform: rotate(360deg); }
	}

	.game_timer {
	    position: fixed;
	    z-index: 100; 
	    bottom: 0; 
	    left: 0;
	    width: 100%;
	}
	
	
	</style>

    <title>RECONQUISTA</title>
  </head>
  <body id="body" onload="init();" class="bg-body">


	<nav id="navigation" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="display:none">
	  <a class="navbar-brand" href="#"><img src="images/reconquista-logo.png"></img></a>
	  
	  
	  <div id="nav-info1" class="d-none nav-item active navbar-text" style="margin:auto;"> <span id="navbar-role">Król</span> </div>
	  <div id="nav-info2" class="d-none nav-item active navbar-text" style="margin-left:10px;"><span id="navbar-reward">0</span> <span class="navbar-text fa fa-star"></span> (<span id="navbar-bonus">+0</span>)</div>
	  
	</nav>


	<div id="game-closed" class="d-none" style="padding:0; text-align:center; background-color: white;">
	
		<div class="w-100 bg-body" style="margin-bottom:20px;">
		<img class="img-fluid " style="padding: 20px; max-width:300px;" src="images/reconquista-logo-big.png"></img>
		</div>
		
		<h1 style="word-wrap: break-word;">WYNIKI GRY</h1>
		<div id="score"></div>
	
		<div class="col-12" style="height:30px;"></div>
		
		<div class="w-100 bg-body" style="margin-bottom:0px;">
		<img class="img-fluid " style="padding: 20px; max-width:200px;" src="images/abc-logo-small.png"></img>
		</div>
	</div>
	
	
	
	<div id="loader" style="text-align:center;">
	
		<img class="img-fluid" style="padding: 20px;" src="images/reconquista-logo-big.png"></img>
		<div id="loader-spin"></div>
	</div>
	
	<div id="postaci" class="d-none" style="padding-top:10px; padding:0;background-color: #739a98;">
	<?php   
	
		echo '<div class="row">';
		
		echo '<div class="col-12 d-flex justify-content-center" style="margin: 20px 0; color:white;"><h1>Wybierz postaci</h1></div>';
		
		$role_img_number=array(1,2,2,3,4,5,5,5,6,6,6,6);
		
		for($role=0; $role<12; $role++) {
		
			echo '<div class="col-6 col-sm-4" style="margin-bottom: 20px; text-align: center;">
				<img id="claim-'.$role.'" style="border-radius: 5%;" class="img-fluid" src="images/card'.$role_img_number[$role].'.png"  onclick="claim_role('.$role.');"></img>
				<img id="claim-'.$role.'-used" style="border-radius: 5%;"  class="img-fluid d-none" src="images/card0.png"></img>
			</div>';
		}
		
		echo '</div>';
	?>
		
		<div class="col-12 bg-white" style="margin-bottom:0px; text-align: center;">
			<img class="w-100 img-fluid " style="margin: auto; padding: 20px; max-width:200px;" src="images/abc-logo-small.png"></img>
		</div>
		
	</div>
	
	
	
	
	
	<div id="game-screen" class="d-none" style="padding-top:10px;">
	

			
								

		<div id="demo" class="carousel slide" data-interval="false" data-keyboard="true">
			
			<div class="carousel-inner">
			
				<?php   
			
					$lands=array();
					$lands[1]="GÓRY";
					$lands[2]="ŁĄKI";
					$lands[3]="LASY";
					$lands[4]="MIASTO";
					$lands[5]="MORZE";
					$lands[6]="PUSTYNIA";
			
					for($page=0; $page<12; $page++) {
						
						if($page==0)
							echo '<div id="role-screen-'.$page.'" class="carousel-item active">';
						else	
							echo '<div id="role-screen-'.$page.'" class="carousel-item">';
						



						

						
						
							
							
							
						echo '<div class="row d-flex align-items-center">';		
												
							echo '<div id="role-bg-cover'.$page.'" class="col-12 bg-my-light d-flex"  style="max-height:100px;" onclick="send_option('.$page.','.$option.');">';
								
								echo '<img class="img-fluid bg-white rounded" style="padding:5px; margin:5px;" src="images/role'.$page.'.png"></img>';
								
								echo '<h2 class="bg-white rounded" style="font-size:1.5em; padding:5px 15px; margin:auto;"><span id="current-role'.$page.'"></span></h2>';	

								echo '<h4 id="bg-current-bonus'.$page.'" style="max-width:90px; height:40%; text-align:center; margin:5px;" class="btn bg-light text-dark d-flex align-items-center">';
									echo '<span id="current-reward'.$page.'">?</span> <span style="margin-left:5px;" class="fa fa-star" ></span>';
								echo '</h4>';
					
							echo '</div>';
							
						
						
							echo '<div class="col-4" style="margin-top:15px;" onclick="slide(0)">';
							echo '<span class="fa fa-3x fa-chevron-left"></span>';
							echo '</div>';
							
							echo '<div class="col-4 text-nowrap align-center d-flex align-items-center justify-content-center" style="margin-top:15px;">';
								echo '<h4 id="bg-current-bonus'.$page.'" style="max-width:90px; height:40%; text-align:center; margin-right:5px; margin-top:5px;" class="btn bg-warning text-dark d-flex align-items-center">';
									echo '<span id="current-bonus'.$page.'">?</span> <span style="margin-left:5px;" class="fa fa-star" ></span>';
								echo '</h4>';
							echo '</div>';
							
							echo '<div class="col-4" style="text-align:right; margin-top:15px;" onclick="slide(1)">';
							echo '<span class="fa fa-3x  fa-chevron-right"></span>';
							echo '</div>';
						
						
						echo '</div>';
						
						
						
						
						echo '<div class="row d-flex align-items-center" style="margin-top:0px;">';						
							
							echo '<div class="col-12" style="margin-top:10px;">';
							echo '</div>';
							
							for($option=1; $option<7; $option++) {
								
								echo '<div class="col-12 bg-white d-flex justify-content-end bg-cover-'.$option.'"  onclick="send_option('.$page.','.$option.');">';
								
									//echo '<div class="card bg-white">';
										
										echo '<img id="choosen'.$page.$option.'" class="img-fluid bg-body rounded" style="padding:5px; margin:10px 20px;" src="images/role'.$page.'.png"></img>';
								
										
										echo '<div class="btn d-flex justify-content-end align-items-start" style="margin:auto;">';
											//echo '<h4><span  style="color:#fff;" class="d-none"></span></h4>';
										echo '</div>';
										

										echo '<h4 id="bg-desc'.$page.$option.'" style="max-width:90px; height:40%; margin: 5px;" class="btn bg-light text-dark d-flex align-items-center">';
											echo '<span id="desc'.$page.$option.'">?</span> <span style="margin-left:5px;" class="fa fa-star" />';
										echo '</h4>';
										
										
									//echo '</div>';
									
								echo '</div>';	
										
										
										
								
								echo '<div class="progress w-100">';
								  echo '<div id="pro'.$page.$option.'0" class="progress-bar bg-base-dark" role="progressbar" style="width:50%">'.$lands[$option].'</div>';
								  echo '<div id="pro'.$page.$option.'1" class="progress-bar bg-vote-dark" role="progressbar" style="width:20%"></div>';
								echo '</div>';
										
								echo '<div class="col-12" style="height:30px;">';
								echo '</div>';
							}
							
						
						echo '</div>';

						
						
						
						echo '<div class="col-12" style="height:20px;">';
						echo '</div>';
						
						
						echo '</div>';
					}
				?>
				
				
			</div>
			
		</div>
		
		
		<?php
		
			echo '<div class="progress" style="height:60px; background-color:#ff8888">';
				echo '<div id="confirm" style="background-color:#a22727; border: none; margin:5px; text-align:center; padding: 10px 45px 10px 10px;" class="btn btn-danger" onclick="send_confirmation();"><i class="fa fa-2x fa-backward" aria-hidden="true"></i></div>';
				
				echo '<div id="pro-timer" style="font-size:2em;" class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width:100%"></div>';
				echo '<div id="pro-my-timer" class="progress-bar bg-danger" role="progressbar" style="width:0%"></div>';
			echo '</div>';
			
			
			echo '<div class="col-12" style="height:20px;">';
			echo '</div>';
					
		?>
		
	</div>
	
	

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="./lib/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="./lib/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
       
     <!-- MDB core JavaScript -->
    <script type="text/javascript" src="./lib/mdb.min.js"></script>

    <script src="client.js"></script>
  
  
  </body>
</html>