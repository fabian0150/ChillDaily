<?php
	session_start();
	
	
	require_once("Mobile_Detect.php");
	$detect = new Mobile_Detect;
	if ( !$detect->isMobile() ) {
		header('Location: index.php');
		exit();
	}
	
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillDaily - Your portion of daily chill</title>
    <link rel="shortcut icon" href="media/image/favicon.ico">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.7.2/p5.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

	
	<style type="text/css">
        html, body {
	        font-family: 'Ubuntu', sans-serif;
            height: 100%;
			width: 100%;
            margin: 0;
            background-color: black;
            overflow: hidden
            text-decoration: none;
        }

        #wrapper {
	        position: absolute;
            min-height: 100%;
			min-width: 100%;
			background-image: url("media/image/bg_2.gif");
			background-position: center; /* Center the image */
			background-repeat: no-repeat; /* Do not repeat the image */
			background-size: cover; /* Resize the background image to cover the entire container */
			z-index: 3;
        }
		
		
		
		.blackout {
			position:fixed;
			top:0;
			left:0;
			min-height: 100%;
			min-width: 100%;
			background-color: #000000;
			 z-index: 25;
		}
		
		.content {
		  position: absolute;
		  width: 300px;
		  height: 300px;
		  z-index: 15;
		  top: 60%;
		  left: 50%;
		  margin: -100px 0 0 -150px;
		  background: rgba(255, 0, 0, 0);
		   text-align: center;
		}
		
		.content-logo {
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  z-index: 10;

		
		  background: rgba(255, 0, 0, 0);
		  z-index: 26;
		  
		}
		
		.music-title {
			position:fixed;
			top:50px;
			left:10px;
			z-index: 10;
			cursor: pointer;
			padding: 15px;
			border-radius: 25px;
			color:rgba(255, 255, 255, 0.77);
			
		}
		.video-title {
			position:fixed;
			top:60%;
			left:10px;
			z-index: 10;
			cursor: pointer;
			padding: 15px;
			border-radius: 25px;
			color:rgba(255, 255, 255, 0.77);
			margin-bottom: 20px;
		}
		
		#canvas {
			position: absolute;
			z-index: 2;
			-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)";       /* IE 8 */
			filter: alpha(opacity=20);  /* IE 5-7 */
			-moz-opacity: 0.2;          /* Netscape */
			-khtml-opacity: 0.2;        /* Safari 1.x */
			opacity: 0.2; 
			bottom: 0;
			width: 100%;
			
		}
		
		.control {
			position: fixed;
			left: 50%;
			bottom: 0px;
			transform: translate(-50%, -50%);
			margin: 0 auto;
			z-index: 26;
			opacity: 1;
			background-color: #ffffff21;
			border-radius: 25px;
		}

		input[type="range"] {
			-webkit-appearance: none;
			border: 1px solid black;

			min-width: 50px;
			width: 100%;
			height: 15px;
		 
			-webkit-border-radius: 20px;
			-moz-border-radius: 20px;
			border-radius: 20px;
			background-color: #0000007d;

		 
			-webkit-box-shadow: inset 0px 4px 4px rgba(0,0,0,.6);
			-moz-box-shadow: inset 0px 4px 4px rgba(0,0,0,.6);
			box-shadow: inset 0px 4px 4px rgba(0,0,0,.6);
		}
		 
		input::-webkit-slider-thumb {
			-webkit-appearance: none;
			width: 20px;
			height: 20px;
			border:1px solid black;
		 
			-webkit-border-radius: 10px;
			border-radius: 10px;
			background: -moz-linear-gradient(-45deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg, rgba(255,255,255,1) 0%,rgba(255,255,255,0) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg, rgba(255,255,255,1) 0%,rgba(255,255,255,0) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#00ffffff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
		}
		
		
		#control-table td {
		
			padding: 10px;
		}
		
		.ctrl-img { 
		  border: 1px solid black; 
		  border-radius: 10px;
		  background: #1d1b1b45;
		  padding: 2px;
		  width: 25px;
		  height: 25px;
		}
		
		.ctrl-img:hover { 
		  border: 1px solid black; 
		  border-radius: 10px;
		  background: #ffffff45;
		  
		} 
		
		.tooltip {
		  position: relative;
		  display: inline-block;
		  border-bottom: 1px dotted black;
		}

		.tooltip .tooltiptext {
		  visibility: hidden;
		  width: 120px;
		  background-color: black;
		  color: #fff;
		  text-align: center;
		  border-radius: 6px;
		  padding: 5px 0;

		  /* Position the tooltip */
		  position: absolute;
		  z-index: 1;
		}

		.tooltip:hover .tooltiptext {
		  visibility: visible;
		  -webkit-transition: opacity 5s;
			-moz-transition: opacity 5s;
			transition: opacity 5s;
			opacity: 0.8;
		}
		
    </style>
    <!--[if lte IE 6]>
    <style type="text/css">
        #container {
            height: 100%;
        }
    </style>
    <![endif]-->
  </head>
  <body id="body">
	 <div id="wrapper">
		<video autoplay muted loop id="player_video">
			  <source src="" type="video/mp4">
		</video>
		<iframe src="media/audio/silence.mp3" allow="autoplay" id="audio" style="display:none"></iframe>
		<audio id="player_audio" autoplay>
		    <source src="" type="audio/mp3">
		</audio>
		<div class="blackout" id="fade"></div>
		<div  id="canvas"></div>
		
		<div class="content-logo" id="content-logo">
			<img src="media/image/chill1.jpg" width="100%" height="100%" />
			
		</div>
		
		<div class="content" id="content">
			<a href="https://chilldaily.online"><img src="media/image/logo1.png" width="100%" height="100%" /></a>
			
			
		</div>
		
		<div class="content" id="play-warning">
			<h1>Please press <img src="media/image/play.png" class="ctrl-img" /> when using a iPhone</h1>
			<p>iPhones don't allow autoplay and video backgrounds, even not after a song change!</p>
			
		</div>
		
		<div class="music-title" id="music-title" onClick="openMusic();">
			<p>Playing now...</p>
			<h1 id="txt_title"></h1>
			<hr>
			<h2 id="txt_interpret"></h2>
		</div>
		
		
		<div class="control" id="control">
			<table id="control-table">
			  <tr>
				<th></th>
				<th></th>
				<th></th> 
				<th></th>
				<th></th>
			  </tr>
			 <tr> 
				<td><img src="media/image/left.png" class="ctrl-img" onClick="changeSong(-1);" /></td>
				<td><img src="media/image/play.png" class="ctrl-img"  onClick="startStopSong();" id="btn_stopPlay"/></td>
				<td><img src="media/image/right.png" class="ctrl-img" onClick="changeSong(1);"/></td>
				<td><img src="media/image/unmuted.png" class="ctrl-img" onClick="muteSong();" id="btn_mute"/></td>
				<td><img src="media/image/random.png" class="ctrl-img" onClick="loadPlayerInfo();" id="btn_random"/></td>
			  </tr>
			</table>
			
			
		</div>
		
		
  </body>
  
  <script>
  
	var played_songs = [];
  
	var music_count = 0;
	var audio_player;
	var player_info;
	var music_interval;
	
	var volume = 1;
	var muted = false;
	var sketches_allowed = false;
	
	var player_audio 	= document.getElementById("player_audio");
	var txt_interpret 	= document.getElementById("txt_interpret");
	var txt_title 		= document.getElementById("txt_title"); 	
	 
		
	$( document ).ready(function() {
		console.log("Thanks for chilling with me <3");
		if(loadPlayerInfo()) {
			initAnimation();
		}
	});
		
	function loadPlayerInfo(id) {
		
		if (typeof audio_player != 'undefined') {
			audio_player.pause();
		}
		
		var play_url = "request/play.php";
		if (typeof id != 'undefined') {
			play_url =  play_url + "?id=" + id
		} else {
			var music_id = 0;
			var found = false;
			if (music_count > 0) {
				if(played_songs.length >= music_count - 1) {
					played_songs = [];
				}
				
				do {
					found = false;
					var rand_id = getRandomInt(1, player_info.music_count);
					play_url =  "request/play.php?id=" + rand_id;
					
					var i;
					for (i = 0; i < played_songs.length; i++) { 
						
						if(rand_id == played_songs[i]) {found = true;}
					}
					
				}while (found == true);
				
				
			}
		}
		
		$.ajax({url: play_url}).done(function( json ) {
			var json_obj = JSON.parse(JSON.stringify(json));
			player_info = {
				audio_id: json_obj[0].audio_id,
				sketch_path: json_obj[0].sketch_path,
				audio_path: json_obj[0].audio_path,
				timer_music: json_obj[0].timer_music,
				music_link: json_obj[0].music_link,
				music_name: json_obj[0].music_name,
				music_count: json_obj[0].music_count
			}
			if(player_info.audio_path == "") {
				loadPlayerInfo(id);
				return false;
			}
			
			music_count = player_info.music_count;
						
			var name_arr = player_info.music_name.split(" - ");
			txt_interpret.innerHTML = name_arr[0];
			txt_title.innerHTML = name_arr[1];
	
			
			if (typeof audio_player != 'undefined') {
				audio_player.pause();
			}
			audio_player = new Audio("media/audio/" + player_info.audio_path);
			
			audio_player.volume = volume;
			audio_player.addEventListener("ended", function(){
				 audio_player.currentTime = 0;
				
				 loadPlayerInfo();
				 
			});
			if(sketches_allowed) {
				$.getScript("media/sketch/" + player_info.sketch_path, function() {
				   //console.log("Sketch loaded: " + player_info.sketch_path);
				});
			}
		
			var btn_mute 	= document.getElementById("btn_mute");
			if(muted) {
				audio_player.muted = true;
				btn_mute.setAttribute('src', "media/image/muted.png");
			} else {
				audio_player.muted = false;
				btn_mute.setAttribute('src', "media/image/unmuted.png");
			}	
			
			var btn_startStop 	= document.getElementById("btn_stopPlay");
			btn_startStop.setAttribute('src', "media/image/play.png");	
			
			played_songs.push(player_info.audio_id);
			
			$("#play-warning").fadeIn(2000);
			$("#play-warning").fadeOut(5000);
			
			$("#music-title").fadeIn(10000);
			$("#music-title").fadeOut(10000);
			
			
			console.log("==============================");
			console.log("Title: " + player_info.music_name)
			console.log("Song Link: " + player_info.music_link);
		});
		return true;
	}
	
	function changeSong(way) {
		var btn_startStop 	= document.getElementById("btn_stopPlay");
		btn_startStop.setAttribute('src', "media/image/play.png");	
		var id = parseInt(player_info.audio_id);
		var new_id = id + parseInt(way);
		
		if(new_id == 0) {
			new_id = player_info.music_count;
		}
		if(new_id > player_info.music_count) {
			new_id = 1;
		}
		audio_player.pause();
		loadPlayerInfo(new_id);
		
	}
	

	function startStopSong() {
		var btn_startStop 	= document.getElementById("btn_stopPlay");
		$("#music-title").fadeIn(10000);
		$("#music-title").fadeOut(10000);
		if (!audio_player.paused) { //if is playing
			btn_startStop.setAttribute('src', "media/image/play.png");	
			pauseSong();
		} else {
			btn_startStop.setAttribute('src', "media/image/pause.png");
			playSong();
		}
	}
	
	
	function playSong() {
		audio_player.play();
	}
	
	function pauseSong() {
		audio_player.pause();
	}
	
	function muteSong() {
		var btn_mute 	= document.getElementById("btn_mute");
		if(audio_player.muted) {
			audio_player.muted = false;
			muted = false;
			btn_mute.setAttribute('src', "media/image/unmuted.png");
		} else {
			audio_player.muted = true;
			muted = true;
			btn_mute.setAttribute('src', "media/image/muted.png");
		}		
	}
		
	function openMusic() {
		var win = window.open(player_info.music_link, '_blank');
		win.focus();
	}
	
	
	function switchBoxColor() {
		$( "#music-title" ).animate({
          backgroundColor: "#fff"
        }, 1000 );
	}
	
	function initAnimation() {
		$("#canvas").hide();
		$("#play-warning").hide();
		$("#content").hide();
		$("#music-title").hide();
		$("#control").hide();
		$("#control").fadeIn(5000);
		$("#play-warning").fadeIn(5000);
		
		$("#content-logo").fadeOut(10000);
		$("#fade").fadeOut(15000);
		
		$("#canvas").fadeIn(20000);
		$("#content").fadeIn(50000);
		$("#music-title").fadeIn(30000);
		$("#play-warning").fadeOut(10000);
		$("#music-title").fadeOut(30000);
		$("#content").fadeOut(50000);
	}
	
	
	
	function getRandomInt(min, max) {
	    min = Math.ceil(min);
	    max = Math.floor(max);
	    return Math.floor(Math.random() * (max - min + 1)) + min;
	}

  </script>
</html>