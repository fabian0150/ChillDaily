<?php
	header("Content-Type: application/json; charset=UTF-8");
	$return_arr = array();
	session_start();
	$servername = "localhost";
	$username = "dailychill_user";
	$password = "h!79i5kF";
	$dbname = "dailychill";
	
	
	$video_path = "";
	$video_name = "";
	$video_length = "";
	$video_link = "";
	$video_count = 0;
	$video_id = -1;
	
	if(isset($_SESSION['video_id'])) {
		$video_id = $_SESSION['video_id'];
	}
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	   
	}
	if (!$conn->set_charset("utf8")) {
		
	}

	
	
	$sql = "SELECT ID FROM TBL_VIDEO WHERE disabled = 0;";
	$result = $conn->query($sql);
		
	$video_count = $result->num_rows;
	
	

		$sql = "SELECT ID, video_path, video_length, video_name, video_link FROM TBL_VIDEO WHERE ID != " . $video_id . " AND disabled = 0 					ORDER BY RAND() LIMIT 1;";
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
	
	
	$timer_video = 0;
		
	$video_split = explode(":", $video_length);
	$hour_sec = $video_split[0]*3600;
	$min_sec = $video_split[1]*60;
	$sec = $video_split[2];
	
	$timer_video = $hour_sec + $min_sec + $sec;
	
	$video_path = $video_path;

	

	$row_array['video_id'] = $video_id;
	
	$row_array['video_path'] = $video_path;

	$row_array['timer_video'] = $timer_video;

	$row_array['video_link'] = $video_link;
	$row_array['video_name'] = $video_name;
	$row_array['video_count'] = $video_count;
	array_push($return_arr, $row_array);
	
	echo json_encode($return_arr);
?>
