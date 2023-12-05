<?php
function sql_conn(){

$servername = "localhost";
$username = "bitwadke_lms";
$password = "Lms@4649";
$db = "lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
  echo "Connection Failed";
  die("Connection failed: " . $conn->connect_error);
}

return $conn;

}

?>