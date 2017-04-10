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

  $sql = "SELECT images.file_path, images.caption, images.credit, GROUP_CONCAT(albums.title) AS sum_titles
          FROM saves INNER JOIN images ON saves.imageID = images.imageID 
          INNER JOIN albums ON saves.title = albums.title 
          GROUP BY images.imageID";

  //get the data
  $result = $mysqli->query($sql);
  //if no result, print the error
  if (!$result) {
    print($mysqli->error);
    exit();
  }

  while($row = $result->fetch_assoc()){
    $table_data[]= array("file_path"=>$row['file_path'],"caption"=>$row['caption'],"credit"=>$row['credit'],"title"=>$row['sum_titles']);
  }

  echo json_encode($table_data,JSON_PRETTY_PRINT);

?>
