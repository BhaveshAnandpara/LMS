<?php
//including database connectivity
include "../../includes/db.conn.php";
$adjustmentId = $_GET['id']; //get adjustment id
$flag = $_GET['flag'];
// here for 0 and 1 is for lecture adjustment 
// 0 indicate for ACCEPT Condition and 1 is indicate for REJECT contion
if ($flag == 0) {
    $query = "UPDATE lectureadjustments SET status = 'ACCEPT' WHERE lecAdjustmentID = $adjustmentId";
    $conn = sql_conn();
    $result = mysqli_query($conn, $query);
    header("location: http://localhost/LMS/pages/Staff/adjustment_request.php");
} else if ($flag == 1) {
    $query = "UPDATE lectureadjustments SET status = 'REJECT' WHERE lecAdjustmentID =$adjustmentId";
    $conn = sql_conn();
    $result = mysqli_query($conn, $query);
}
// here for 2 and 3 is for Task adjustment 
// 2 indicate for ACCEPT Condition and 3 is indicate for REJECT contion
else if ($flag == 2) {
    $query = "UPDATE taskadjustments SET status = 'ACCEPT' WHERE taskAdjustmentID = $adjustmentId";
    $conn = sql_conn();
    $result = mysqli_query($conn, $query);
    header("location: http://localhost/LMS/pages/Staff/adjustment_request.php");
} else if ($flag == 3) {
    $query = "UPDATE taskadjustments SET status = 'REJECT' WHERE taskAdjustmentID =$adjustmentId";
    $conn = sql_conn();
    $result = mysqli_query($conn, $query);
    header("location: http://localhost/LMS/pages/Staff/adjustment_request.php");
}
