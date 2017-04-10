<?php
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    echo '
	<div id="header">
	';
    if ( isset($_SESSION['logged_user'] ) ) {
		echo '<a class="log" href="logout.php">Logout</a>';
			
	}else{
		echo '<a class="log" href="login.php">Login as Admin</a>';
	}
	echo '
		<h1>Image Album</h1>
			<div id="nav_bar">
				<span id="all_albums_link"><a href="index.php">All albums</a></span>
				<span>|</span>
				<span id="all_images_link"><a href="all_images.php">All images</a></span>
	';

	if ( isset($_SESSION['logged_user'] ) ) {
	//Protected content here
		echo '<span>|</span>
		<span id="add_album_link"><a href="add_album.php">Add album</a></span>
		<span>|</span>
		<span id="add_image_link"><a href="add_image.php">Add image</a></span>';
	}
	echo'
		</div> <!-- end of nav_bar div -->
	</div> <!-- end of header div -->
	';
?>