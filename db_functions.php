<?php
require "db_credentials.php";

// This function connects to database
function connect_db(){
  global $host, $user, $pass, $dbname;
  $conn = mysqli_connect($host, $user, $pass, $dbname);

  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  return($conn);
}

// This function disconnects the database
function disconnect_db($conn){
  mysqli_close($conn);
}
?>
