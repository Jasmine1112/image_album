<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Image Album - Add Image</title>
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
	<div id="upload_form">
		<form method="post" enctype="multipart/form-data"> 
			New image upload: <input type="file" name="new_image"><br>
			<span>Image caption: <input type="text" name="image_caption"></span><br>
			<span>Image credit: <input type="text" name="image_credit"></span><br>
			<span>Added into Album (optional):</span> <br>
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
					echo "<input type=\"checkbox\" name=\"checked_title[]\" value=\"$each_title\"> $each_title<br>";
				}
			?>
			<input type="submit" name="submit_upload" value="Upload">
		</form>
	</div><!-- end of upload_form div-->

	<?php
		
		//get data from the form submitted
		if (isset($_POST["submit_upload"])) {
			$file_input = $_FILES['new_image'];
				//$originalName = $file_input[ 'name' ]; 
				$tempName = $file_input[ 'tmp_name' ]; 
				$size_in_bytes = $file_input[ 'size' ]; 
				$type = $file_input[ 'type' ];
				$extension = htmlentities(end(explode('/', $type)));
			$caption_input = filter_input(INPUT_POST, 'image_caption', FILTER_SANITIZE_STRING);
			$credit_input = filter_input(INPUT_POST, 'image_credit',FILTER_SANITIZE_STRING);
			if (isset($_POST['checked_title'])) {
				$album_input = $_POST['checked_title'];
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
			if (empty($tempName)||empty($caption_input)||empty($credit_input)) {
				echo "<h3 class=\"yellow\">New image failed to be uploaded. Please make sure that you upload a file, fill out caption and credit.</h3>";
			}
			//if the image is too large than 1200px*1200px
			elseif ($size_in_bytes>2880000) {
				echo "<h3 class=\"yellow\">The image file is too large, please resize it or change a different one.</h3>";
			}
			//if the type of the file is not common image type
			elseif (strtolower($extension)!="jpeg"&&strtolower($extension)!="jpg"&&strtolower($extension)!="png") {
				echo "<h3 class=\"yellow\">Sorry the uploading does not support $extension file. Please change extension or change a different image to make sure you upload a jpeg/jpg/png file.</h3>";
				echo $extension;
			}
			//if album is not filled out, then simply add new image to table images
			elseif (empty($album_input)) {
				move_uploaded_file($tempName, $new_file_path);
				$sql_insert1 = "INSERT INTO images (imageID, file_path, caption, credit) 
					VALUES ($new_id,\"$new_file_path\",\"$caption_input\",\"$credit_input\");";
			}
			//if all filled out, all is ready to insert and upload to an album
			else {
				// $date=getdate();
				// $CURRENT_DATE=$date["year"]."-".$date["mon"]."-".$date["mday"];
				move_uploaded_file($tempName, $new_file_path);
				//add the new image to table images
				$sql_insert1 = "
					INSERT INTO images (imageID, file_path, caption, credit) 
					VALUES ($new_id,\"$new_file_path\",\"$caption_input\",\"$credit_input\");";
				//add link between the new image and each selected album
				$sql_insert2 = [];
				$sql_update = [];
				foreach ($album_input as $checked) {
					$sql_insert2["$checked"]="INSERT INTO saves (date_saved, imageID, title)
					VALUES (CURRENT_DATE, $new_id, \"$checked\");";
					$sql_update["$checked"] = 
					"UPDATE saves INNER JOIN albums ON saves.title = albums.title
									INNER JOIN images ON saves.imageID = images.imageID
					SET albums.date_modified=CURRENT_DATE
					WHERE saves.imageID=$new_id && saves.title=\"$checked\";";
				}

			}

			//see if the saving is successful
			if( ! empty($sql_insert1) ) {
				if( $mysqli->query($sql_insert1) ) {
					echo "<h3 class=\"yellow\">New image uploaded!</h3>";
					if (!empty($sql_update)) {
						//perform each query to insert saves to different albums
						foreach ($album_input as $checked) {
							$saves_each=$sql_insert2["$checked"];
							$update_each=$sql_update["$checked"];
							if ($mysqli->query($saves_each)) {
								echo "<h3 class=\"yellow\">Added into album $checked!</h3>";
							}
							else {
								echo "<h3 class=\"yellow\">But the image could not be added into the album(s) :(</h3>";
							}
							$temp=$mysqli->query($update_each);
						}
					}
				} else {
					echo "<h3 class=\"yellow\">The new image couldn't be uploaded :(</h3>";
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