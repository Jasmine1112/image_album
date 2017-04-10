<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Image Album - Album</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Yeseva+One" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Crimson+Text" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="js/modal.js"></script>
</head>

<body>

<?php
	include 'includes/header.php';
?> 

<!--  ................................. edit album modal....................-->

<div class="modal modal_edit_album">
	<a class="exit"> X </a>
	<form method="post">
		<span>Album title: <input type="text" name="edit_title" id="edit_title"></span><br>
		<span>Album style: <input type="text" name="edit_style" id="edit_style"></span><br><br>
		<span>Images in the album:</span> <br>
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
			//list all the title in albums
			$sql_album = "SELECT * FROM images;";
			$albums_result = $mysqli->query($sql_album);
			//if no result, print the error
			if (!$albums_result) {
				print($mysqli->error);
				exit();
			}
			while ( $row = $albums_result->fetch_assoc() ) {
				$each_path = $row['file_path'];
				$each_caption = $row['caption'];
				echo "<input type=\"checkbox\" name=\"edit_album_image[]\" value=\"$each_path\" id=\"checkbox_$each_path\"> 
						<label for=\"checkbox_$each_path\">
						<img src=\"$each_path\" alt=\"$each_caption\" class=\"checkbox_image\">
						</label><br>";
			}
		?>
		<input type="submit" name="submit_edit_album" value="Save" class="submit_edit_button"><br>
		<span id="delete_album_button" class="edit_button">*DELETE*</span>
		<div class="hidden confirmation">
			<input type="submit" name="delete" value="Delete">
			<input type="submit" name="cancel" value="Cancel">
		</div>
	</form>
</div><!-- end of modal_edit_album div -->

<?php
	//get data from the form submitted
	if (isset($_POST["submit_edit_album"])) {
		$title_input = htmlentities(filter_input(INPUT_POST, 'edit_title', FILTER_SANITIZE_STRING));
		$style_input = htmlentities(filter_input(INPUT_POST, 'edit_style',FILTER_SANITIZE_STRING));
		$album_image_input = $_POST['edit_album_image'];
		$edit_current_album = end(explode("title=", $_SERVER['QUERY_STRING'],2));

		//check if there's a duplicate in album titles except itself
		require_once 'includes/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($mysqli->errno) {
			//The page isn't worth much without a db connection so display the error and quit
			print($mysqli->error);
			exit();
		}
		$sql_check = "SELECT title, style FROM albums;";
		$result_check = $mysqli->query($sql_check);
		//if no result, print the error
		if (!$result_check) {
			print($mysqli->error);
			exit();
		}

		$count=0;
		while ( $row = $result_check->fetch_assoc() ) {
			$check_title = $row[ 'title' ];
			if ($check_title==$title_input) {
				$count++;
			}
		}

		//now, different query for different conditions:
		//if there's information needed not filled out
		if (empty($title_input)||empty($style_input)) {
			echo "<h3 class=\"yellow\">Album failed to be edited. Please make sure that you filled out its title and style.</h3>";
		}
		//if the album title contains space
		elseif (preg_match('/\s/',$title_input)) {
			echo "<h3 class=\"yellow\">Make sure that the album title does not contain space because we want concise title!</h3>";
		}
		//if the style dexcription is longer than 15 characters
		elseif (strlen($style_input)>30) {
			echo "<h3 class=\"yellow\">The style should be kept brief. Please shorten it to within 30 characters.</h3>";
		}
		//if the admin changed the album title to an existing one
		elseif ($count>0 && $title_input!=$edit_current_album) {
			echo "<h3 class=\"yellow\"> Album title $title_input already exists! Please change to a different one.</h3>";
		}
		//album title "(none)" is not allowed 
		elseif ($title_input=="(none)") {
			echo "<h3 class=\"yellow\">Album failed to be editted. Album title \"(none)\" is not allowed! Please change to a different one.</h3>";
		}
		else{
			$sql_insert_in_album=[];
			$sql_update_album=[];
			$sql_delete_in_album=[];

			$sql_update_album[]= "UPDATE albums SET title=\"$title_input\" WHERE title=\"$edit_current_album\";";
			$sql_update_album[]= "UPDATE albums SET style=\"$style_input\" WHERE title=\"$edit_current_album\";";
			$sql_update_album[]= "UPDATE albums SET date_modified=CURRENT_DATE WHERE title=\"$edit_current_album\";";
			$sql_delete_in_album[]= "DELETE saves FROM saves INNER JOIN albums ON saves.title=albums.title
									WHERE albums.title=\"$edit_current_album\";";

			foreach ($album_image_input as $checked) {
				$sql_insert_in_album[]= "INSERT INTO saves (date_saved, imageID, title)
										SELECT CURRENT_DATE, images.imageID, \"$title_input\"
										FROM images
										WHERE images.file_path=\"$checked\";";
			}
		}

		$count_success=0;
		//see if the saving is successful
		if (!empty($sql_update_album) && !empty($sql_delete_in_album)) {
			foreach ($sql_delete_in_album as $delete) {
				if ($mysqli->query($delete)) {
					$count_success++;
				}else{
					echo "<h2>failed to delete old links :(</h2>";
					echo $mysqli->error;
				}
			}
			foreach ($sql_update_album as $update) {
				if ($mysqli->query($update)) {
					$count_success++;
				}else{
					echo "<h2>failed to update fields :(</h2>";
					echo $mysqli->error;
				}
			}
		}
		if (!empty($sql_insert_in_album)) {

			foreach ($sql_insert_in_album as $insert) {
				if ($mysqli->query($insert)) {
					$count_success++;
				}else{
					echo "<h2>failed to add the new image :(</h2>";
					echo $mysqli->error;
				}
			}
		}
		if ($count_success==sizeof($sql_insert_in_album)+sizeof($sql_update_album)+sizeof($sql_delete_in_album)) {
			echo "<h2>Album successfully edited.</h2>";
		}
		if ($edit_current_album!=$title_input) {
			echo "<h2>Return to All Albums page to check your new Album $title_input</h2>";
		}
	}

	//if delete album button is clicked
	if (isset($_POST["delete"])) {
		$edit_current_album = end(explode("title=", $_SERVER['QUERY_STRING'],2));
		$delete_album_sql=[];
		$delete_album_sql[] = "DELETE FROM albums WHERE title=\"$edit_current_album\";";
		$delete_album_sql[] = "DELETE FROM saves WHERE title=\"$edit_current_album\";";
		$count = 0;
		foreach ($delete_album_sql as $delete) {
			if ($mysqli->query($delete)) {
				$count++;
			}else{
				echo "<h2>Failed to delete album</h2>";
			}
		}
		if ($count==sizeof($delete_album_sql)) {
			echo "<h2>Album deleted</h2>";
		}
	}

?>


<!--  ................................. end of edit album modal...................-->


<!--  ................................. edit image modal form....................-->

	<div class="modal modal_edit_image">
		<a class="exit"> X </a>
		<img src="images/img1.jpg" class="modal_image_to_edit" alt="image to be edited">
		<form method="post" enctype="multipart/form-data">
			<input type="text" name="old_pic" class="hidden" id="old_pic">
			<input type="file" name="new_image" class="hidden" id="edit_image_upload">
			<span>Image caption: <input type="text" name="edit_caption" id="edit_caption"></span><br>
			<span>Image credit: <input type="text" name="edit_credit" id="edit_credit"></span><br>
			<span>In Album:</span> <br>
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
				//list all the title in albums
				$sql_album = "SELECT title FROM albums;";
				$albums_result = $mysqli->query($sql_album);
				//if no result, print the error
				if (!$albums_result) {
					print($mysqli->error);
					exit();
				}
				while ( $row = $albums_result->fetch_assoc() ) {
					$each_title = htmlentities($row['title']);
					echo "<input type=\"checkbox\" name=\"edit_title[]\" value=\"$each_title\"> $each_title<br>";
				}
			?>
			<input type="submit" name="submit_edit_image" value="Save" class="submit_edit_button"><br>
			<span id="delete_image_button" class="edit_button">*DELETE*</span>
			<div class="hidden confirmation">
				<input type="submit" name="delete_img" value="Delete">
				<input type="submit" name="cancel" value="Cancel">
			</div>

		</form>
	</div><!-- end of modal_edit_image div -->

<?php
	//get data from the form submitted
	if (isset($_POST["submit_edit_image"])) {
		$old_pic = $_POST['old_pic'];
		$file_input = $_FILES['new_image'];
			$tempName = $file_input[ 'tmp_name' ]; 
			$size_in_bytes = $file_input[ 'size' ]; 
			$type = $file_input[ 'type' ];
			$extension = htmlentities(end(explode('/', $type)));
		$caption_input = htmlentities(filter_input(INPUT_POST, 'edit_caption', FILTER_SANITIZE_STRING));
		$credit_input = htmlentities(filter_input(INPUT_POST, 'edit_credit',FILTER_SANITIZE_STRING));
		if (isset($_POST['edit_title'])) {
			$album_input = $_POST['edit_title'];
		}else{
			$album_input = [];
		}
		//check what's the last imageID in table images
		$sql_id = "SELECT imageID FROM images 
					ORDER BY imageID DESC LIMIT 1";
		$id_result = $mysqli->query($sql_id);
		//if no result, print the error
		if (!$id_result) {
			print($mysqli->error);
			exit();
		}
		//fetch the largest id number
		//in case there's no image in table images
		if (($id_result->num_rows)==0) {
			$last_id=0;
		}else{
			$last_id_fetch = $id_result->fetch_assoc();
			$last_id = $last_id_fetch['imageID'];
		}
		//The new id, must be unique
		$new_id = $last_id+1;
		$new_file_path = "images/img$new_id.$extension";
		//now, different query for different conditions:
		//if there's information needed not filled out
		if (empty($caption_input)||empty($credit_input)||empty($album_input)) {
			echo "<h3 class=\"yellow\">Image failed to be edited. Please make sure that you filled out caption, credit, and which album it's in.</h3>";
		}
		//if the admin replaced the image with a new one
		elseif (!empty($tempName)) {
			//if the image is too large than 1200px*1200px
			if ($size_in_bytes>2880000) {
			echo "<h3 class=\"yellow\">The image file is too large, please resize it or change a different one.</h3>";
			}
			//if the type of the file is not common image type
			elseif (strtolower($extension)!="jpeg"&&strtolower($extension)!="jpg"&&strtolower($extension)!="png") {
			echo "<h3 class=\"yellow\">Sorry the uploading does not support $extension file. Please change extension or change a different image to make sure you upload a jpeg/jpg/png file.</h3>";
			echo $extension;
			}else{//add the new image into table images and saves
				move_uploaded_file($tempName, $new_file_path);
				$sql_insert=[];
				//add the new image to table images
				$sql_insert[]= "
					INSERT INTO images (imageID, file_path, caption, credit) 
					VALUES ($new_id,\"$new_file_path\",\"$caption_input\",\"$credit_input\");";
				//add link between the new image and each selected album
				$sql_update = [];
				foreach ($album_input as $checked) {
					$sql_insert[]="INSERT INTO saves (date_saved, imageID, title)
					VALUES (CURRENT_DATE, $new_id, \"$checked\");";
					$sql_update[] = 
					"UPDATE saves INNER JOIN albums ON saves.title = albums.title
									INNER JOIN images ON saves.imageID = images.imageID
					SET albums.date_modified=CURRENT_DATE
					WHERE saves.imageID=$new_id && saves.title=\"$checked\";";
				}
				//delete old pic from its album
				$sql_delete=[];
				$sql_delete[]="
					DELETE saves FROM saves INNER JOIN images ON saves.imageID=images.imageID
					WHERE images.file_path=\"$old_pic\";";
			}

		}
		//if the admin is changing fields of the existing image
		else {
			//update caption, credit of the image, and update the saves link
			$sql_insert = [];
			$sql_update = [];
			$sql_update[]= "UPDATE images SET caption=\"$caption_input\" WHERE file_path=\"$old_pic\";";
			$sql_update[]= "UPDATE images SET credit=\"$credit_input\" WHERE file_path=\"$old_pic\";";
			foreach ($album_input as $checked) {
				$sql_insert[]="INSERT INTO saves (date_saved, imageID, title) 
					SELECT CURRENT_DATE, images.imageID, \"$checked\"
					FROM images
					WHERE images.file_path=\"$old_pic\";";
				$sql_update[] = 
				"UPDATE saves INNER JOIN albums ON saves.title = albums.title
								INNER JOIN images ON saves.imageID = images.imageID
				SET albums.date_modified=CURRENT_DATE
				WHERE images.file_path=\"$old_pic\" && saves.title=\"$checked\";";
			}
			$sql_delete=[];
			//delete the old saves link of that image
			$sql_delete[]="DELETE saves FROM saves INNER JOIN images ON saves.imageID=images.imageID
					WHERE images.file_path=\"$old_pic\";";
		}
		$count_success=0;
		//see if the saving is successful
		if (!empty($sql_update) && !empty($sql_delete) &&!empty($sql_insert)) {
			foreach ($sql_delete as $delete) {
				if ($mysqli->query($delete)) {
					$count_success++;
				}else{
					echo "<h2>failed to delete old links :(</h2>";
				}
			}
			foreach ($sql_update as $update) {
				if ($mysqli->query($update)) {
					$count_success++;
				}else{
					echo "<h2>failed to update fields :(</h2>";
				}
			}
		}
		if (!empty($sql_insert)) {

			foreach ($sql_insert as $insert) {
				if ($mysqli->query($insert)) {
					$count_success++;
				}else{
					echo "<h2>failed to add the new image :(</h2>";
				}
			}
		}
	}
	

	//if delete image button is clicked
	if (isset($_POST["delete_img"])) {
		$count=0;
		$old_pic = $_POST['old_pic'];
		$delete_image_sql=[];
		$delete_image_sql[] = "DELETE FROM images WHERE file_path=\"$old_pic\";";
		$delete_image_sql[] = "DELETE FROM saves WHERE \"$old_pic\" LIKE CONCAT('%',imageID, '%');";
		foreach ($delete_image_sql as $delete) {
			if ($mysqli->query($delete)) {
				$count++;
			}else{
				echo "<h2>Failed to delete image</h2>";
			}
		}
		//delete the image from the images folder
		if (file_exists($old_pic)) {
			unlink($old_pic);
		}
		if ($count==sizeof($delete_image_sql)) {
			echo "<h2>Image deleted</h2>";
		}
	}

?>
<!--  ................................. end of edit image modal form....................-->



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
	//safe version of current album title
	$query_string = end(explode("title=", $_SERVER['QUERY_STRING'],2));
	$current_album=filter_var($query_string,FILTER_SANITIZE_STRING);

	$sql = "SELECT images.file_path AS file_path, images.caption AS caption, images.credit AS credit, GROUP_CONCAT(albums.title) AS sum_titles
            FROM saves INNER JOIN images ON saves.imageID = images.imageID 
            INNER JOIN albums ON saves.title = albums.title 
            GROUP BY images.imageID
            HAVING GROUP_CONCAT(albums.title) LIKE \"%$current_album%\";";
    $sql2 = "SELECT images.file_path AS file_path, albums.title AS title, albums.style AS style
    		FROM saves INNER JOIN images ON saves.imageID = images.imageID 
            			RIGHT JOIN albums ON saves.title = albums.title 
            WHERE albums.title=\"$current_album\";";
	//get the data
	$result = $mysqli->query($sql);
	$result2 = $mysqli->query($sql2);
	//if no result, print the error
	if (!$result) {
		print($mysqli->error);
		exit();
	}
	if (!$result2) {
		print($mysqli->error);
		exit();
	}
?>

<?php 
	include 'includes/modal_div.php';
?>

<div id="container">

	<div id="images">
	<?php
		$images_in_album_array =[];
		$edit_current_album="";
		$style="";
		while ($row2=$result2->fetch_assoc()) {
			$images_in_album_array[]=$row2[ 'file_path' ];
			$edit_current_album=$row2['title'];
			$style=$row2['style'];
		}
		$images_in_album=implode(" ", $images_in_album_array);

		$safe_current_album = htmlentities($current_album);
		echo "<h2>$safe_current_album Album</h2>";
		if ( isset($_SESSION['logged_user'] ) ) {
			echo "<span class=\"edit_album_button edit_button\" onClick=\"click_edit_album('$edit_current_album','$style','$images_in_album')\">Edit</span>";
		}
		//Loop through the $result rows fetching each one as an associative array
		while ( $row = $result->fetch_assoc() ) {
			$file_path = htmlentities($row[ 'file_path' ]);
			$caption = htmlentities($row[ 'caption' ]); 
			$credit = htmlentities($row[ 'credit' ]);
			$sum_titles = $row[ 'sum_titles' ];
			echo "
			<div class=\"image_cell\">
				<img src=\"$file_path\" alt=\"$caption\" class=\"thumbnail\">
				<div class=\"img_info\">
					<span>Caption: <span class=\"caption_span inline\">$caption</span></span><br>
					<span>Image from: <span class=\"credit_span inline\">$credit</span></span><br>
					<span>Image is in Album: <span class=\"album_span inline\">$sum_titles</span></span>
				</div><!-- end of image_info div -->";
			if ( isset($_SESSION['logged_user'] ) ) {
				echo"
					<span class=\"edit_button\" onClick=\"click_edit('$file_path','$caption','$credit','$sum_titles')\">Edit</span>";
			}
			echo"</div> <!-- end of image_cell div -->
			";
		}

	?>

	</div> <!-- end of images div -->

</div> <!-- end of container div -->




</body>
</html>