<?php
//including database connectivity
include "../../includes/db.conn.php";
$applicationID = $_GET['id']; //get applicationID id
$query = "UPDATE applications SET status = 'WITHDROWN' WHERE applicationID = $applicationID";
$conn = sql_conn();
$result = mysqli_query($conn, $query);

// query to update lectureadjustments status to withdrown 
$query = "UPDATE lectureadjustments SET status = 'WITHDROWN' WHERE applicationID = $applicationID";
$conn = sql_conn();
$result = mysqli_query($conn, $query);

// query to update taskadjustments status to withdrown 
$query = "UPDATE taskadjustments SET status = 'WITHDROWN' WHERE applicationID = $applicationID";
$conn = sql_conn();
$result = mysqli_query($conn, $query);
header("location: http://localhost/LMS/pages/Staff/leave_history.php");

?>