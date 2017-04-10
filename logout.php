<?php
	//Need to start a session in order to access it to be able to end it
	session_start();
	
	if (isset($_SESSION['logged_user'])) {
		$olduser = $_SESSION['logged_user'];
		unset($_SESSION['logged_user']);
	} else {
		$olduser = false;
	}
?>
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
		<div id="container">
			<?php
				if ( $olduser ) {
					print("<p>Thanks for using our page, $olduser!</p>");
					print("<p>Return to our <a href='login.php' class='no_decoration'>login page</a></p>");
					print("<p>or go back to our <a href='index.php' class='no_decoration'>index page</a> as a common user.</p>");

				} else {
					print("<p>You logged out.</p>");
					print("<p>Go to our <a href='login.php' class='no_decoration'>login page</a></p>");
					print("<p>or go back to our <a href='index.php' class='no_decoration'>index page</a> as a common user.</p>");
				}
			?>
		</div>

		
	</body>
</html>