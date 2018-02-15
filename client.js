const STATUS_ACTIVE = '0';
const STATUS_PLAYER_DESCRIPTION = '1';
const STATUS_ROLE = '2';
const STATUS_USER_ID = '3';
const STATUS_ONEWAY = '4';
const STATUS_TEAMWIN = '5';
const STATUS_FOLLOW = '6';
const STATUS_OPTIONS_EXPECTED_REWARD = '7';
const STATUS_TARGET_REWARD = '8';
const STATUS_PROFIT_OPTION = '9';
const STATUS_OPTIONS_SHOWN_REWARD = '10';
const STATUS_ROLE_BONUS = '11';
const STATUS_PLAYER_ID = '13';
const STATUS_CHOOSEN_OPTION = '14';
const STATUS_TIME_REMAINING = '15';
const STATUS_REWARD = '16';
const STATUS_CHOICE_TIME = '17';
const STATUS_CONFIRM_TIME = '18';
const STATUS_CONFIRMED = '19';
const STATUS_VOTES = '20';
const STATUS_OPT_1_ME_RESULT = '21';
const STATUS_OPT_2_ME_RESULT = '22';
const STATUS_OPT_3_ME_RESULT = '23';
const STATUS_OPT_1_US_RESULT = '24';
const STATUS_OPT_2_US_RESULT = '25';
const STATUS_OPT_3_US_RESULT = '26';


const GAME_START_TIME = '101';
const GAME_FINISH_TIME = '102';
const GAME_TEAM_RESULT = '103';
const GAME_TEAM_CONFIRMED = '104';
const GAME_OPTIONS_VOTES = '105';
const GAME_OPTIONS_LIMITS = '106';
const GAME_ACT_TEAM_REWARD = '107';
const GAME_MAX_TEAM_REWARD = '108';
const GAME_MAX_CHOICE = '109';
const GAME_DECISION_QUALITY = '110';
const GAME_REMAINING_TIME = '111';
const GAME_MAX_ROLE_REWARD = '112';


const OPT_NEEDED_VOTES = '0';
const OPT_MIN_REWARD = '1';
const OPT_MAX_REWARD = '2';


var game_finished = 0;
var maxgame="";
var active_slide=-1;
var user_id=0;
var sliding_direction="right";

var my_roles=[0,0,0,0,0,0,0,0,0,0,0,0];

try {

	
function slide(direction) {
	
	if(direction==1) {
		
		for(var ii=0; ii<12; ii++) {
				
			active_slide=(active_slide+1)%12;
			if(my_roles[active_slide]==1)
				break;
		}
		sliding_direction="left";
		
		if(active_slide!=0)
			$("#demo").carousel(active_slide);
		
	}
	else {
		
		var dont_slide=0;
		
		if(active_slide==0)
			dont_slide=1;
			
		 for(var ii=0; ii<12; ii++) {
			
			active_slide=(active_slide+11)%12;
			if(my_roles[active_slide]==1)
				break;
		}
		sliding_direction="right";

		if(dont_slide==0)
			$("#demo").carousel(active_slide);
	}
	
	read_status();
}	
	


 $(document).ready(function () {
            
	    $('#demo').hammer().on('swipeleft', function () {
                
		//$(this).carousel('next');
		
		for(var ii=0; ii<12; ii++) {
			
			active_slide=(active_slide+1)%12;
			if(my_roles[active_slide]==1)
				break;
		}
		sliding_direction="left";
		if(active_slide!=0)
			$(this).carousel(active_slide);
		
		read_status();
            })
            
	    
	    $('#demo').hammer().on('swiperight', function () {
                
		    //$(this).carousel('prev');
		   var dont_slide=0;
		
		   if(active_slide==0)
			dont_slide=1;
		    
		   for(var ii=0; ii<12; ii++) {
			
			active_slide=(active_slide+11)%12;
			if(my_roles[active_slide]==1)
				break;
		}
		sliding_direction="right";
		if(dont_slide==0)
			$(this).carousel(active_slide);
		
		read_status();
            })
        });




$('#demo').on('slide.bs.carousel', function (ev) {
  var id = ev.relatedTarget.id;
 
   //ev.to=active_slide;
   //active_slide=ev.to;
   //ev.direction=sliding_direction;
	
   document.getElementById("navbar-role").innerHTML=document.getElementById("current-role"+active_slide).innerHTML;
   document.getElementById("navbar-bonus").innerHTML=document.getElementById("current-bonus"+active_slide).innerHTML;
   document.getElementById("navbar-reward").innerHTML=document.getElementById("current-reward"+active_slide).innerHTML;
	
   document.getElementById("body").style.paddingTop=document.getElementById("navigation").offsetHeight+5+"px";
})


function getUrlVars() {
	
	var vars = {};
	var parts = window.location.href.replace(/[?&#]+([^=&#]+)=([^&#]*)/gi, 
		function(m,key,value) {
				
			vars[key] = value;
		});

	return vars;
}

function getHashVars() {
	
	var vars = {};
	var value_order = 0;
	var parts = window.location.hash.replace(/[?\/]+([^?\/]+)/gi, 
		function(m,key,value) {
				
			vars[value_order] = key;
			value_order++;
		});

	return vars;
}


function init() {

  var session = getHashVars();
  my_game_id = session[0];
  my_user_id = session[1];
  
  
  read_status();
  setInterval(read_status, 5000);
 }

 
function read_status() {

	if(game_finished==0) {

	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    
		var myObj = JSON.parse(this.responseText);
		 
		
		var img_class = "card-img-top img-fluid ";
		

		for(var page_ii=0; page_ii<12; page_ii++) {
			
			my_roles[page_ii]=0;
			if(myObj.full_status[page_ii][STATUS_USER_ID]==my_user_id) 
				my_roles[page_ii]=1;
		}
		
		
		if(my_roles[active_slide]==0) {

			for(var ii=0; ii<12; ii++) {
					
				active_slide=(active_slide+1)%12;
				if(my_roles[active_slide]==1)
					break;
			}
			$('#demo').carousel(active_slide);
		}	


		
		
		var non_claimed_roles_count=0;			
		var my_roles_count=0;			
		for(var page_ii=0; page_ii<12; page_ii++) {
	
						
			document.getElementById("current-role"+page_ii).innerHTML=myObj.full_status[page_ii][STATUS_PLAYER_DESCRIPTION].role;
			document.getElementById("current-bonus"+page_ii).innerHTML="+"+myObj.full_status[page_ii][STATUS_ROLE_BONUS];
			document.getElementById("current-reward"+page_ii).innerHTML=myObj.full_status[page_ii][STATUS_REWARD];
			
			
			
			if(myObj.full_status[page_ii][STATUS_USER_ID]!="-1") {
				
				document.getElementById("claim-"+page_ii).className="d-none";
				document.getElementById("claim-"+page_ii+"-used").className="img-fluid";
				
				
				if(myObj.full_status[page_ii][STATUS_USER_ID]==my_user_id) {
					
					my_roles_count++;
					
					
					if(active_slide==-1) {

						active_slide=page_ii;
						document.getElementById("role-screen-"+page_ii).className="carousel-item active";
						$("#demo").carousel(active_slide);
						
					} else if(active_slide==page_ii) {
					
						active_slide=page_ii;
						document.getElementById("role-screen-"+page_ii).className="carousel-item active";
						
					} else {
						
						document.getElementById("role-screen-"+page_ii).className="carousel-item";
					}
				}
				else {
					
					document.getElementById("role-screen-"+page_ii).className="carousel-item";
				}
			}
			else {
				
				non_claimed_roles_count++;
				document.getElementById("role-screen-"+page_ii).className="carousel-item";
			}
			
			
			
			
			for(var opt_ii=1; opt_ii<7; opt_ii++) {
			
				document.getElementById("pro"+page_ii+opt_ii+"0").className="progress-bar bg-base-dark";
				document.getElementById("pro"+page_ii+opt_ii+"1").className="progress-bar bg-vote-dark";
				document.getElementById("bg-desc"+page_ii+opt_ii).className="btn bg-body text-light d-flex align-items-center";
				
				if(myObj.status[GAME_OPTIONS_LIMITS][opt_ii][OPT_NEEDED_VOTES]<=myObj.status[GAME_OPTIONS_VOTES][opt_ii]) {
					
					document.getElementById("pro"+page_ii+opt_ii+"0").className="progress-bar bg-base-light";
					document.getElementById("pro"+page_ii+opt_ii+"1").className="progress-bar bg-vote-light";
					document.getElementById("bg-desc"+page_ii+opt_ii).className="btn bg-warning text-dark d-flex align-items-center";
				}
				
				if(myObj.full_status[page_ii][STATUS_CHOOSEN_OPTION]==opt_ii)			
					document.getElementById("choosen"+page_ii+opt_ii).className="img-fluid bg-my-light rounded"; //"fa fa-forward fa-2x";
				else
					document.getElementById("choosen"+page_ii+opt_ii).className="d-none";
				
				
				if(myObj.full_status[page_ii][STATUS_CHOOSEN_OPTION]==opt_ii) {
					
					document.getElementById("pro"+page_ii+opt_ii+"0").style.width=Math.max(20, 100/15*(15-myObj.status[GAME_OPTIONS_LIMITS][opt_ii][OPT_NEEDED_VOTES]+myObj.status[GAME_OPTIONS_VOTES][opt_ii]-myObj.full_status[page_ii][STATUS_VOTES]))+"%";	
					document.getElementById("pro"+page_ii+opt_ii+"1").style.width=(100/15*(myObj.full_status[page_ii][STATUS_VOTES]))+"%";	
					document.getElementById("role-bg-cover"+page_ii).className="col-12 d-flex bg-cover-"+myObj.full_status[page_ii][STATUS_CHOOSEN_OPTION];	
				}
				else {
				
					document.getElementById("pro"+page_ii+opt_ii+"0").style.width=Math.max(20, 100/15*(15-myObj.status[GAME_OPTIONS_LIMITS][opt_ii][OPT_NEEDED_VOTES]+myObj.status[GAME_OPTIONS_VOTES][opt_ii]))+"%";	
					document.getElementById("pro"+page_ii+opt_ii+"1").style.width="0%";	
				}
				
				//console.log(myObj.full_status[page_ii]);
				document.getElementById("desc"+page_ii+opt_ii).innerHTML=myObj.full_status[page_ii][STATUS_OPTIONS_SHOWN_REWARD][opt_ii];
				
			}
		}		
		
		
		var game_duration=myObj.status[GAME_FINISH_TIME]-myObj.status[GAME_START_TIME];
		var game_remaining_time=myObj.status[GAME_REMAINING_TIME];
		
		if(active_slide>-1) {
			if(myObj.full_status[active_slide][STATUS_CONFIRMED]==1) {
				document.getElementById("confirm").className="d-none";//innerHTML = "GOTOWE";//+myObj.status[GAME_TEAM_CONFIRMED];
			}
		}
		
			
		
		
		document.getElementById("body").className="bg-light";
		document.getElementById("loader").className="d-none";
		document.getElementById("navigation").style.display="block";
		
		if(non_claimed_roles_count>0) {
			
			document.getElementById("postaci").className="container-fluid";	
			document.getElementById("game-screen").className="d-none";	
		}
		else {

			document.getElementById("postaci").className="d-none";
			document.getElementById("game-screen").className="container-fluid bg-white";
			document.getElementById("nav-info1").className="nav-item active navbar-text";
			document.getElementById("nav-info2").className="nav-item active navbar-text";
		}
		
		
		if(active_slide>-1) {
			document.getElementById("navbar-role").innerHTML=document.getElementById("current-role"+active_slide).innerHTML;
			document.getElementById("navbar-bonus").innerHTML=document.getElementById("current-bonus"+active_slide).innerHTML;
			document.getElementById("navbar-reward").innerHTML=document.getElementById("current-reward"+active_slide).innerHTML;
		}
		
		document.getElementById("body").style.paddingTop=document.getElementById("navigation").offsetHeight+5+"px";
		
		
		
		console.log("MAX "+myObj.status[GAME_ACT_TEAM_REWARD]+"/"+myObj.status[GAME_MAX_TEAM_REWARD]);
			
		
		//console.log('TIME '+myObj.status[GAME_REMAINING_TIME]);
		
		document.getElementById("pro-timer").style.width=(100*game_remaining_time/game_duration)+"%";	
		var seconds="0"+Math.round(game_remaining_time%60);
		document.getElementById("pro-timer").innerHTML="<span class=\"text-nowrap\"><span class=\"fa fa-clock-o\"></span> "+Math.floor(game_remaining_time/60)+":"+seconds.substr(seconds.length-2)+" s</span>";
		
		if(game_remaining_time==0) {
			
			//console.log("END");
			
			document.getElementById("game-closed").className="container-fluid";	
			document.getElementById("game-screen").className="d-none";	
			document.getElementById("postaci").className="d-none";
			document.getElementById("navigation").style.display="none";
			document.getElementById("body").style.paddingTop="0px";
			game_finished=1;
			
			// RESULTS
			var result="";
			var quality=myObj.status[GAME_DECISION_QUALITY];
			for(var page_ii=0; page_ii<12; page_ii++) {
				
				var reward=myObj.full_status[page_ii][STATUS_REWARD];
				var max_reward=myObj.status[GAME_MAX_ROLE_REWARD][page_ii];
				
				result=result
					+"<div class=\"w-100\"></div>"
					+"<div class=\"col text-nowrap\" style=\"text-align:right\">"
					+myObj.full_status[page_ii][STATUS_PLAYER_DESCRIPTION].role
					+"</div>"
					
					+"<div class=\"col text-nowrap\" style=\"text-align:center; max-width:40px;\">"
					+" <b>+"+Math.round(Math.max(1,2.5*(10*reward/max_reward-6)))+"</b>"
					+"</div>"
				
					+"<div class=\"col text-nowrap\" style=\"text-align:left\">("
					+" "+myObj.full_status[page_ii][STATUS_REWARD]
					+"/"+myObj.status[GAME_MAX_ROLE_REWARD][page_ii]
					+" <span class=\"fa fa-star\" style=\"color:#999;\"></span>"
					+")</div>";
			}
			// RESULTS
			
			
			
			document.getElementById("score").innerHTML=""
				+"<div class=\"row w-100\">"
					+result
					
					+"<div class=\"col-12\" style=\"margin-top:20px; text-align:center\">"
					+"TEAM DECISION QUALITY: "+myObj.status[GAME_DECISION_QUALITY]
					+"</div>"
				
				+"</div>";
			
		}
		
		
		
	    }
	  };
	  xhttp.open("GET", "api_read_status.php?game_id="+my_game_id, true);
	  xhttp.send();
	}
}



function find_maxgame() {

	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    
		var myObj = JSON.parse(this.responseText);
		maxgame=" Q: "+myObj.status.act_team_reward+"/"+myObj.status.max_team_reward+" CH:"+myObj.status.choices+"<br>";		
	    }
	  };
	  xhttp.open("GET", "api_find_maxgame.php?game_id="+my_game_id, true);
	  xhttp.send();
}




function send_option(page, option) {

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    
	var myObj = JSON.parse(this.responseText);
	read_status();
    }
  };
  xhttp.open("GET", "api_send_option.php?game_id="+my_game_id+"&page="+page+"&option="+option, true);
  xhttp.send();
}


function claim_role(role_id) {

  document.getElementById("claim-"+role_id).className="d-none";
  document.getElementById("claim-"+role_id+"-used").className="img-fluid";
	
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    
	var myObj = JSON.parse(this.responseText);
	read_status();
    }
  };
  xhttp.open("GET", "api_claim_role.php?game_id="+my_game_id+"&user_id="+my_user_id+"&role_id="+role_id, true);
  xhttp.send();
}



function send_confirmation() {

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    
	var myObj = JSON.parse(this.responseText);
	read_status();
    }
  };
  xhttp.open("GET", "api_send_confirmation.php?game_id="+my_game_id+"&user_id="+my_user_id, true);
  xhttp.send();
}





////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



}
catch(err) {
    
	function save_err(msg) {

	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	    
		var myObj = JSON.parse(this.responseText);
		read_status();
	    }
	  };
	  xhttp.open("GET", "api_save_err.php?err="+encodeURIComponent(msg), true);
	  xhttp.send();
	}
	
	
	var nVer = navigator.appVersion;
	var nAgt = navigator.userAgent;
	var browserName  = navigator.appName;
	var fullVersion  = ''+parseFloat(navigator.appVersion); 
	var majorVersion = parseInt(navigator.appVersion,10);
	var nameOffset,verOffset,ix;

	// In Opera, the true version is after "Opera" or after "Version"
	if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
	 browserName = "Opera";
	 fullVersion = nAgt.substring(verOffset+6);
	 if ((verOffset=nAgt.indexOf("Version"))!=-1) 
	   fullVersion = nAgt.substring(verOffset+8);
	}
	// In MSIE, the true version is after "MSIE" in userAgent
	else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
	 browserName = "Microsoft Internet Explorer";
	 fullVersion = nAgt.substring(verOffset+5);
	}
	// In Chrome, the true version is after "Chrome" 
	else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
	 browserName = "Chrome";
	 fullVersion = nAgt.substring(verOffset+7);
	}
	// In Safari, the true version is after "Safari" or after "Version" 
	else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
	 browserName = "Safari";
	 fullVersion = nAgt.substring(verOffset+7);
	 if ((verOffset=nAgt.indexOf("Version"))!=-1) 
	   fullVersion = nAgt.substring(verOffset+8);
	}
	// In Firefox, the true version is after "Firefox" 
	else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
	 browserName = "Firefox";
	 fullVersion = nAgt.substring(verOffset+8);
	}
	// In most other browsers, "name/version" is at the end of userAgent 
	else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) < 
		  (verOffset=nAgt.lastIndexOf('/')) ) 
	{
	 browserName = nAgt.substring(nameOffset,verOffset);
	 fullVersion = nAgt.substring(verOffset+1);
	 if (browserName.toLowerCase()==browserName.toUpperCase()) {
	  browserName = navigator.appName;
	 }
	}
	// trim the fullVersion string at semicolon/space if present
	if ((ix=fullVersion.indexOf(";"))!=-1)
	   fullVersion=fullVersion.substring(0,ix);
	if ((ix=fullVersion.indexOf(" "))!=-1)
	   fullVersion=fullVersion.substring(0,ix);

	majorVersion = parseInt(''+fullVersion,10);
	if (isNaN(majorVersion)) {
	 fullVersion  = ''+parseFloat(navigator.appVersion); 
	 majorVersion = parseInt(navigator.appVersion,10);
	}

	
	 
	
	
	
	
	document.getElementById("body").innerHTML = "<div style=\"background-color:white; padding:20px; margint:auto; \">"
			+"<h1>My mistake, sorry :)</h1>"
			+err.name
			+"<br>"+err.message
			+"<br>"+err.stack
			+"</div>";
	
	save_err(
		err.stack+'\r\n'
		 +browserName+' '
		 +fullVersion+' '
		 +majorVersion+' '
		 +navigator.appName+' '
		 +navigator.userAgent+' '	
	);
	
}
