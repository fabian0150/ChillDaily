<?php
	$servername = "localhost";
	$username = "dailychill_user";
	$password = "h!79i5kF";
	$dbname = "dailychill";
	
	
	$video_path = "";
	$video_length = "";
	$audio_path = "";
	$sketch_path = "";
	$music_name = "";
	$music_length = "";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	
	$sql = "SELECT video_path, video_length FROM TBL_VIDEO ORDER BY RAND() LIMIT 1;";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	       $video_path = $row["video_path"];
		   $video_length = $row["video_length"];
	    }
	}
	$sql = "SELECT music_path, music_name, music_length FROM TBL_MUSIC ORDER BY RAND() LIMIT 1;";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	       $audio_path = $row["music_path"];
	       $music_name = $row["music_name"];
		   $music_length = $row["music_length"];
	    }
	}
	$sql = "SELECT sketch_path FROM TBL_SKETCH ORDER BY RAND() LIMIT 1;";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	       $sketch_path = $row["sketch_path"];
	    }
	}
	
	
	$ip = "Not found";
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	    $ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	}
	
	$sql = "INSERT INTO TBL_LOG (music_name, video_name, sketch_name, user_ip)
		VALUES ('" . $music_name . "', '" . $video_path . "', '" . $sketch_path . "','" . $ip . "')";

		if ($conn->query($sql) === TRUE) {
			
		} 
	$conn->close();
	
	$timer_music = 0;
	
	
	$music_split = explode(":", $music_length);
	$hour_sec = $music_split[0]*3600;
	$min_sec = $music_split[1]*60;
	$sec = $music_split[2];
	
	$timer_music = $hour_sec + $min_sec + $sec;
	
	
	$video_path = "media/video/" . $video_path;
	$sketch_path = "media/sketch/" . $sketch_path;
	$audio_path = "media/audio/" . $audio_path;
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DailyChill</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.7.2/p5.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
	<meta http-equiv="refresh" content="<?php echo $timer_music; ?>; URL=http://chilldaily.online/">
	<script src="<?php echo $sketch_path; ?>"></script>
	
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
		
		#myVideo {
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
			 z-index: 100;
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
		
		.music-title {
			position:fixed;
			top:50px;
			left:100px;
			z-index: 10;
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
  <body>
	 <div id="wrapper">
		<video autoplay muted loop id="myVideo">
			  <source src="<?php echo $video_path; ?>" type="video/mp4">
		</video>
		<iframe src="media/audio/silence.mp3" allow="autoplay" id="audio" style="display:none"></iframe>
		<audio id="player" autoplay loop>
		    <source src="<?php echo $audio_path; ?>" type="audio/mp3">
		</audio>
		<div class="blackout" id="fade"></div>
		<div  id="canvas"></div>
		
		<div class="content" id="content">
			<img src="media/image/logo1.png" width="100%" height="100%" />
			
		</div>
		
		<div class="music-title" id="music-title">
			<h1><?php echo $music_name; ?></h1>
		</div>
	
  </body>
  
  <script>
	$( document ).ready(function() {
		$("#canvas").hide();
		$("#content").hide();
		$("#music-title").hide();
		
		$("#fade").fadeOut(10000);
		$("#canvas").fadeIn(20000);
		$("#content").fadeIn(50000);
		$("#music-title").fadeIn(100000);
		$("#music-title").fadeOut(100000);
		$("#content").fadeOut(50000);
		console.log("done");
		
	});
  </script>
</html>