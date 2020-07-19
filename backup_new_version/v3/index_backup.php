<?php
	session_start();
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
	<meta http-equiv="refresh" content="<?php echo $timer_music; ?>; URL=http://chilldaily.online/">
	<script src=""></script>
	
	<style type="text/css">
        html, body {
	        font-family: 'Ubuntu', sans-serif;
            height: 100%;
			width: 100%;
            margin: 0;
            background-color: black;
            overflow: hidden
            
        }

        #wrapper {
	        position: absolute;
            min-height: 100%;
			min-width: 100%;
			background-color: #707070;
			z-index: 3;
        }
		
		#player_video {
		  position: absolute;
		  right: 0;
		  bottom: 0;
		  min-width: 100%; 
		  min-height: 100%;
		  z-index: 1;
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
		  top: 50%;
		  left: 50%;
		  margin: -100px 0 0 -150px;
		  background: rgba(255, 0, 0, 0);
		  
		}
		
		.content-logo {
		  position: absolute;
		  width: 1000px;
		  height: 1000px;
		  z-index: 15;
		  top: 50%;
		  left: 50%;
		  margin: -500px 0 0 -500px;
		  background: rgba(255, 0, 0, 0);
		  z-index: 26;
		  
		}
		
		.music-title {
			position:fixed;
			top:50px;
			left:100px;
			z-index: 10;
			cursor: pointer;
			padding: 15px;
			border-radius: 25px;
			color:rgba(255, 255, 255, 0.77);
			
		}
		.video-title {
			position:fixed;
			top:60%;
			left:10%;
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
    </style>
    <!--[if lte IE 6]>
    <style type="text/css">
        #container {
            height: 100%;
        }
    </style>
    <![endif]-->
  </head>
  <body onClick="playMedia();">
	 <div id="wrapper">
		<video autoplay muted loop id="player_video">
			  <source src="" type="video/mp4">
		</video>
		<iframe src="../media/audio/silence.mp3" allow="autoplay" id="audio" style="display:none"></iframe>
		<audio id="player_audio" autoplay loop>
		    <source src="" type="audio/mp3">
		</audio>
		<div class="blackout" id="fade"></div>
		<div  id="canvas"></div>
		
		<div class="content-logo" id="content-logo">
			<img src="../media/image/chill1.jpg" width="100%" height="100%" />
			
		</div>
		
		<div class="content" id="content">
			<a href="https://chilldaily.online"><img src="../media/image/logo1.png" width="100%" height="100%" /></a>
			
			<button onClick="change(-1);">Back</button>
			<button onClick="change(1);">Next</button>
			<button>Mute</button>
			<button onClick="playSong();">Start</button>
			<button onClick="pauseSong();">Pause</button>
		</div>
		
		<div class="music-title" id="music-title" onClick="openMusic();">
			<p>Playing now...</p>
			<h1 id="txt_title"></h1>
			<hr>
			<h2 id="txt_interpret"></h2>
		</div>
		
		<div class="video-title" id="video-title" onClick="openVideo();">
			<p>Video now...</p>
			<h1 id="txt_video_name"></h1>
		</div>
	
  </body>
  
  <script>

	var player_info;
	var player_audio = document.getElementById("player_audio");
	var player_video = document.getElementById("player_video");

	var txt_interpret = document.getElementById("txt_interpret");
	var txt_title = document.getElementById("txt_title");
	var txt_video_name = document.getElementById("txt_video_name");  	
	 
		
	$( document ).ready(function() {
		if(loadPlayerInfoStartup()) {
			initAnimation();
			console.log("Player started");
		}
		
	});
	
	function loadPlayerInfoStartup() {
		$.ajax({url: "request/play.php"}).done(function( json ) {
			var json_obj = JSON.parse(JSON.stringify(json));
			player_info = {
				audio_id: json_obj[0].audio_id,
				video_path: json_obj[0].video_path,
				sketch_path: json_obj[0].sketch_path,
				audio_path: json_obj[0].audio_path,
				timer_music: json_obj[0].timer_music,
				timer_music: json_obj[0].timer_music,
				music_link: json_obj[0].music_link,
				video_link: json_obj[0].video_link,
				music_name: json_obj[0].music_name,
				video_name: json_obj[0].video_name,
				music_count: json_obj[0].music_count
			}
			txt_video_name.innerHTML = player_info.video_name;
			
			var name_arr = player_info.music_name.split("-");
			
			txt_interpret.innerHTML = name_arr[0];
			txt_title.innerHTML = name_arr[1];
			
			console.log(player_info.music_count);
			console.log("Thanks for chilling with me :D");
			console.log("==============================");
			console.log("Title: " + player_info.music_name)
			console.log("Song Link: " + player_info.music_link);
			console.log("Video Link: " + player_info.video_link);
			start();
		});
		return true;
	}
	
	function change(way) {
		var id = parseInt(player_info.audio_id);
		var new_id = id + parseInt(way);
		
		if(new_id == 0) {
			new_id = player_info.music_count;
		}
		if(new_id > player_info.music_count) {
			new_id = 1;
		}
		
		console.log(id + " --> " + parseInt(new_id));
		loadPlayerInfo(new_id);
		initAnimation();
	}
	
	function playSong() {
		document.getElementById("audio").play();
		player_audio.play();
		console.log("Play");
			
	}
	
	function pauseSong() {
		document.getElementById("audio").pause();
		player_audio.pause();
		console.log("Pause");
			
	}
	
	function loadPlayerInfo(id) {
		console.log("Requested song id: " + id);
		$.ajax({url: "request/play.php?id=" + id}).done(function( json ) {
			var json_obj = JSON.parse(JSON.stringify(json));
			player_info = {
				audio_id: json_obj[0].audio_id,
				video_path: json_obj[0].video_path,
				sketch_path: json_obj[0].sketch_path,
				audio_path: json_obj[0].audio_path,
				timer_music: json_obj[0].timer_music,
				timer_music: json_obj[0].timer_music,
				music_link: json_obj[0].music_link,
				video_link: json_obj[0].video_link,
				music_name: json_obj[0].music_name,
				video_name: json_obj[0].video_name,
				music_count: json_obj[0].music_count
			}
			
			txt_video_name.innerHTML = player_info.video_name;
			
			var name_arr = player_info.music_name.split(" - ");
			
			txt_interpret.innerHTML = name_arr[0];
			txt_title.innerHTML = name_arr[1];
			
			console.log("==============================");
			console.log("Title: " + player_info.music_name)
			console.log("Song Link: " + player_info.music_link);
			console.log("Video Link: " + player_info.video_link);
			
			start();
		
		});
		return true;
	}
	
	function start() {
		player_video.setAttribute('src', "../media/video/" + player_info.video_path);
		player_audio.setAttribute('src', "../media/audio/" + player_info.audio_path);
	}
	function initAnimation() {
		$("#canvas").hide();
		//$("#content").hide();
		$("#music-title").hide();
		$("#video-title").hide();
		
		$("#content-logo").fadeOut(5000);
		$("#fade").fadeOut(15000);
		
		$("#canvas").fadeIn(20000);
		//$("#content").fadeIn(50000);
		$("#music-title").fadeIn(100000);
		$("#video-title").fadeIn(30000);
		
		$("#music-title").fadeOut(100000);
		$("#video-title").fadeOut(30000);
		//$("#content").fadeOut(50000);
	}
	
	function playMedia() { 
		player_audio.play();
		//player_video.play();
	} 
	
	function changeVideo() {
		
		var new_video_id = getRandomInt(1, 15);
		
		player_video.pause();

		var path = "../media/video/" + new_video_id + ".mp4";
		player_video.setAttribute('src', path); 
	    player_info.video_path = new_video_id + ".mp4";
	    
	
	    player_video.load();
	    player_video.play();
	   
	}
	
	function openMusic() {
		var win = window.open("", '_blank');
		win.focus();
	}
	function openVideo() {
		var win = window.open("", '_blank');
		win.focus();
	}
	
	function switchBoxColor() {
		$( "#music-title" ).animate({
          backgroundColor: "#fff"
        }, 1000 );
	}
	
	function getRandomInt(min, max) {
	    min = Math.ceil(min);
	    max = Math.floor(max);
	    return Math.floor(Math.random() * (max - min + 1)) + min;
	}

  </script>
</html>