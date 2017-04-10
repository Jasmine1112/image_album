<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Image Album - Homepage</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Yeseva+One" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Crimson+Text" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="js/nav_bar.js"></script>
</head>
<body>

<?php
	include 'includes/header.php';
?>

<?php
	//Get the connection info for the DB. 
	require_once 'includes/config.php';
	
	//Establish a database connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	//Was there an error connecting to the database?
	if ($mysqli->errno) {
		//The page isn't worth much without a db connection so display the error and quit
		print($mysqli->error);
		exit();
	}

	$album_sql = 'SELECT * FROM albums;';

	//get the data
	$album_result = $mysqli->query($album_sql);
	//if no result, print the error
	if (!$album_result) {
		print($mysqli->error);
		exit();
	}
?>

<div id="container">

	<div id="albums">
	<?php

		//Loop through the $result rows fetching each one as an associative array
		while ( $row = $album_result->fetch_assoc() ) {
			$title = htmlentities($row[ 'title' ]);
			$style = htmlentities($row[ 'style' ]);
			//use sql to find the first image in the album as cover
			$cover_sql = "SELECT images.file_path
							FROM saves INNER JOIN albums ON saves.title=albums.title
										INNER JOIN images ON saves.imageID=images.imageID
							WHERE albums.title=\"$title\"
							ORDER BY images.imageID
							LIMIT 1;";
			//get the data
			$cover_result = $mysqli->query($cover_sql);
			//if no result, print the error
			if (!$cover_result) {
				print($mysqli->error);
				exit();
			}
			//fetch cover image
			//in case there's no image in the album
			if (($cover_result->num_rows)==0) {
				$cover="images/empty.jpg";
				//credit:
				echo "<!-- image from http://supperstudio.com/wp-content/uploads/empty-spaces-logo.jpg -->";
				
			}else{
				$cover_result_fetch = $cover_result->fetch_assoc();
				$cover = $cover_result_fetch['file_path'];
			}
			
			echo "
			<div class=\"album_cell\">
				<a href=\"album.php?title=$title\" class=\"album_link\">
					<img src=\"$cover\" alt=\"$title\">
				</a>
				<div class=\"album_info\">
					<h3>Album title: $title</h3>
					<h3>Album style: $style</h3>
				</div><!-- end of album_info div -->
			</div> <!-- end of album_cell div -->
			";
		}

	?>

	</div> <!-- end of images div -->

</div> <!-- end of container div -->



</body>
</html>