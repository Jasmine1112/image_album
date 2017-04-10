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
	<h1>Image Album</h1>
	<?php 
		$post_username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
		$post_password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
		if ( empty( $post_username ) || empty( $post_password ) ) {
	?>
		<h2>Log in</h2>
		<form action="login.php" method="post">
			Username: <input type="text" name="username"> <br>
			Password: <input type="password" name="password"> <br>
			<input type="submit" value="Submit">
		</form>
		
	<?php

	} else {

		//Get the config file
		require_once 'includes/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if( $mysqli->connect_errno ) {
			//uncomment the next line for debugging
			echo "<p>$mysqli->connect_error<p>";
			die( "Couldn't connect to database");
		}
		
		//Check for a record that matches the POSTed username
		$query = "SELECT * FROM users WHERE username = '$post_username'";
		$query = "SELECT * FROM users WHERE username = ?";
		$stmt = $mysqli->stmt_init();
		if ($stmt->prepare($query)) { 
			$stmt->bind_param('s', $post_username); 
			$stmt->execute();
			$result = $stmt->get_result();
		}

		//Make sure there is exactly one user with this username
		if ( $result && $result->num_rows == 1) {
			
			$row = $result->fetch_assoc();
			
			$db_hash_password = $row['hashpassword'];
			
			if( password_verify( $post_password, $db_hash_password ) ) {
				$db_username = $row['username'];
				$_SESSION['logged_user'] = $db_username;
			}
		} 
		
		$mysqli->close();
		
		if ( isset($_SESSION['logged_user'] ) ) {
			echo '<h2>Welcome back to our <a href="index.php" class="no_decoration">image album page</a>, admin!</h2>';
		} else {
			echo '<p>You did not login successfully.</p>';
			echo '<p>Please <a href="login.php" class="no_decoration">login</a>.</p>';
		}
		
	} //end if isset username and password
	?>
</body>
</html>
