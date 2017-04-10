<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Image Album - Add Album</title>
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

<div id="container">

	<?php
	if ( isset( $_SESSION[ 'logged_user' ] ) ) {
	//Protected content here
	?>

	<h2>Fill in the form~</h2>

	<div id="add_form">
		<form action="add_album.php" method="post">
			<span>Album title: <input type="text" name="album_title"></span><br>
			<span>Album style: <input type="text" name="album_style"></span><br>
			<input type="submit" name="submit_add" value="Add">
		</form>
	</div> <!-- end of add_form div -->

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
		$sql = "SELECT title FROM albums;";

		//get data from the form submitted
		if (isset($_POST["submit_add"])) {
			$title_input = filter_input(INPUT_POST, 'album_title', FILTER_SANITIZE_STRING);
			$style_input = filter_input(INPUT_POST, 'album_style',FILTER_SANITIZE_STRING);
			//check there's no duplicate of album title
			$result = $mysqli->query($sql);
			//if no result, print the error
			if (!$result) {
				print($mysqli->error);
				exit();
			}

			$count=0;
			while ( $row = $result->fetch_assoc() ) {
				$check_title = $row[ 'title' ];
				if ($check_title==$title_input) {
					$count++;
				}
			}
			//if there's information not filled out
			if (empty($title_input)||empty($style_input)) {

				echo "<h3 class=\"yellow\">New album failed to be saved. Please fill in all the fields.</h3>";
			} 
			//if the album title contains space
			elseif (preg_match('/\s/',$title_input)) {
				echo "<h3 class=\"yellow\">Make sure that the album title does not contain space because we want concise title!</h3>";
			}
			//if the album title contains space
			elseif (preg_match('/\s/',$title_input)) {
				echo "<h3 class=\"yellow\">Make sure that the album title does not contain space because we want concise title!</h3>";
			}
			//if the style dexcription is longer than 15 characters
			elseif (strlen($style_input)>30) {
				echo "<h3 class=\"yellow\">The style should be kept brief. Please shorten it to within 30 characters.</h3>";
			}
			//if there's a duplicate in album title
			elseif ($count>0) {
				echo "<h3 class=\"yellow\">New album failed to be saved. Album title $check_title already exists! Please change to a different one.</h3>";
			}
			//album title "(none)" is not allowed 
			elseif ($title_input=="(none)") {
				echo "<h3 class=\"yellow\">New album failed to be saved. Album title \"(none)\" is not allowed! Please change to a different one.</h3>";
			}
			//if all filled out
			else {
				$sql_insert = "INSERT INTO albums (title, date_created, date_modified, style) 
					VALUES (\"$title_input\",CURRENT_DATE,CURRENT_DATE,\"$style_input\");";
			}
			//see the saving is successful
			if( ! empty( $sql_insert ) ) {
				if( $mysqli->query($sql_insert) ) {
					echo "<h3 class=\"yellow\">New album saved!</h3>";
				} else {
					echo "<h3 class=\"yellow\">New album couldn't be saved :(</h3>";
				}
			}
		}

	?>

	<?php
	} else {
		print "<h2>You must be logged in to use this feature.</h2>";
		print "<h2>Please <a href='login.php' class='no_decoration'>login</a></h2>";
	}
	?>

</div> <!-- end of container div -->




</body>
</html>