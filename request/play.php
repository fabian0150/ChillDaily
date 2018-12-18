<?php
	header("Content-Type: application/json; charset=UTF-8");
	$return_arr = array();
	session_start();
	$servername = "localhost";
	$username = "";
	$password = "";
	$dbname = "";
	
	
	$video_path = "";
	
	$audio_path = "";
	$sketch_path = "";
	
	
	$music_name = "";
	$music_length = "";
	$music_link = "";
	$music_count = 0;
	
	$video_name = "";
	$video_length = "";
	$video_link = "";
	$video_count = 0;
		
	$music_id = -1;
	$video_id = -1;
	$sketch_id = -1;
	
	if(isset($_SESSION['music_id'])) {
		$music_id = $_SESSION['music_id'];
	}
	if(isset($_SESSION['video_id'])) {
		$video_id = $_SESSION['video_id'];
	}
	if(isset($_SESSION['sketch_id'])) {
		$sketch_id = $_SESSION['sketch_id'];
	}
	
	
	
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	   
	}
	if (!$conn->set_charset("utf8")) {
		
	}

	
	$sql = "SELECT ID FROM TBL_MUSIC WHERE disabled = 0;";
	$result = $conn->query($sql);
		
	$music_count = $result->num_rows;
	
	$sql = "SELECT ID FROM TBL_VIDEO WHERE disabled = 0;";
	$result = $conn->query($sql);
		
	$video_count = $result->num_rows;
	
	

		$sql = "SELECT ID, video_path, video_length, video_name, video_link FROM TBL_VIDEO WHERE ID != " . $video_id . " AND disabled = 0 ORDER BY RAND() LIMIT 1;";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			   $video_path = $row["video_path"];
			   $video_length = $row["video_length"];
			   $video_id = $row["ID"];
			   $video_name = $row["video_name"];
			   $video_link = $row["video_link"];

			}
			
		}

	$_SESSION['video_id'] = $video_id;
	
		$sql = "";
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$sql = "SELECT ID, music_path, music_name, music_length, music_link FROM TBL_MUSIC WHERE ID = " . $id . " AND disabled = 0 LIMIT 1;";

		} else {
			$sql = "SELECT ID, music_path, music_name, music_length, music_link FROM TBL_MUSIC WHERE ID != " . $music_id . " AND disabled = 0 ORDER BY RAND() LIMIT 1;";

		}

		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			   $audio_path = $row["music_path"];
			   $music_name = $row["music_name"];
			   $music_length = $row["music_length"];
			   $music_id = $row["ID"];
			   $music_link = $row["music_link"];
			}
		}

	$_SESSION['music_id'] = $music_id;	
	
	
		
		$sql = "SELECT ID, sketch_path FROM TBL_SKETCH WHERE ID != " . $sketch_id . " AND disabled = 0 ORDER BY RAND() LIMIT 1;";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			   $sketch_path = $row["sketch_path"];
			   $sketch_id = $row["ID"];
			}
		}

	$_SESSION['sketch_id'] = $sketch_id;	
	
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
	$timer_video = 0;
	
	
	$music_split = explode(":", $music_length);
	$hour_sec = $music_split[0]*3600;
	$min_sec = $music_split[1]*60;
	$sec = $music_split[2];
	
	$timer_music = $hour_sec + $min_sec + $sec;
	
	$video_split = explode(":", $video_length);
	$hour_sec = $video_split[0]*3600;
	$min_sec = $video_split[1]*60;
	$sec = $video_split[2];
	
	$timer_video = $hour_sec + $min_sec + $sec;

	
	
	$music_name_split = explode("-", $music_name);
	$music_name_interpret = $music_name_split[0];
	$music_name_title = $music_name_split[1];
	
	
	$video_path = $video_path;
	$sketch_path = $sketch_path;
	$audio_path = $audio_path;
	
	$row_array['audio_id'] = $music_id;
	$row_array['video_id'] = $video_id;
	
	$row_array['video_path'] = $video_path;
	$row_array['sketch_path'] = $sketch_path;
	$row_array['audio_path'] = $audio_path;
	$row_array['timer_video'] = $timer_video;
	$row_array['timer_music'] = $timer_music;
	$row_array['music_link'] = $music_link;
	$row_array['video_link'] = $video_link;
	$row_array['music_name'] = $music_name;
	$row_array['video_name'] = $video_name;
	$row_array['music_count'] = $music_count;
	$row_array['video_count'] = $video_count;
	array_push($return_arr, $row_array);
	
	echo json_encode($return_arr);
?>
