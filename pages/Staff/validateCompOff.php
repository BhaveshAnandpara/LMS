<?php ob_start();
    //  Creates database connection 
    require "../../includes/db.conn.php";
    $conn = sql_conn();
?>

<?php

    //include Config Class
    require('../../utils/Utils.class.php');
    require('../../utils/Config/Config.class.php');

    //include class definition
    require('../../utils/Admin/Admin.class.php');

    //start session
    session_start();

    //Get the User Object
    $user =  $_SESSION['user'];
    $id =  $_GET['id'];
    $date = $_POST['date'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $day = $_POST['day'];
    $reason = $_POST['reason'];

?>


<?php 


    try{
    
        //check validations

        if( $day === null || $day === "" || empty($day) ){
            echo Utils::alert("date not selected" , "ERROR");
            throw new Exception("date not selected");
        }

        if( $endTime < $startTime ){
            echo Utils::alert("endTime cannot be less than startTime" , "ERROR");
            throw new Exception("endTime cannot be less than startTime");
        }
        
        if( $reason === null || $reason === "" || empty($reason) ){
            echo Utils::alert("Reason cannot be empty", "ERROR");
            throw new Exception("Reason cannot be empty");
        }


        $startTime = date( 'H:i:s' , strtotime($startTime) );
        $endTime = date( 'H:i:s' , strtotime($endTime) );
        $date = date( 'd-m-Y' , strtotime($date) );

        if( $day == Config::$_COMPOFF_TYPES['Half Day'] ) $day = 0.5;
        if( $day == Config::$_COMPOFF_TYPES['Full Day'] ) $day = 1;

        //Add Details to compoff table
        $sql = "INSERT INTO `compoffrequests` (`compoffrequestID`, `applicantID`, `status`, `inTime`, `outTime`, `date`, `totalDays`, `reason`) VALUES (NULL , '$id' , 'PENDING', '$startTime', '$endTime', $date , $day, '$reason')";

        $result =  mysqli_query( $conn , $sql);

        if( !$result ){
            throw new Exception('Error Occured');
        }

        //Add notification

        $time = date( 'Y-m-d H:i:s' , time());

        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$id', 'You Request for Compensatory Leave Submitted Successfully', '$time' );";
        
        $result =  mysqli_query( $conn , $sql);

        if( !$result ){
            echo "Error Occured During Insertion of ". $id ."  Notification";
        }

        echo Utils::alert( "Request Submitted Successfully", "SUCCESS" );

        echo "<script>
            window.location.href = './applyCompoff.php'
        </script>";        

    }catch( Exception $e ){

        echo $e;

        echo "<script>
            window.location.href = './applyCompoff.php'
        </script>";


    }
    ob_end_flush();
?>
