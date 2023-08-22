<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>



<!-- Include this to use User object -->
<?php

    //include Config Class
    require('../../utils/Utils.class.php');
    require('../../utils/Config/Config.class.php');

    //include class definition
    require('../../utils/Staff/staff.class.php');

    //start session
    session_start();

    //Get the User Object
    $user =  $_SESSION['user'];
    $type = $_GET['type'];
    $action = $_GET['action'];
    $adjID = $_GET['adjID'];
    $applicantID = $_GET['applicantID'];

?>

<?php

    if( $type === 'lec' ){

        //Update the status of Adjustment
        $query = "UPDATE lectureadjustments SET status='$action' WHERE lecAdjustmentID = $adjID";
        $conn = sql_conn();
        $result = mysqli_query($conn, $query);

        if( !$result ){
            echo Utils::alert("Errro Occured");
        }

        // Insert instance into notification table for applicant id

        $time = date( 'Y-m-d H:i:s' , time());

        if( $action === Config::$_ADJUSTMENT_STATUS['ACCEPTED'] ){

            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$applicantID', 'Your Lecture Adjustment has been accepted by $user->fullName ', '$time' );";

       }else{

            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$applicantID', 'Your Lecture Adjustment has been rejected by $user->fullName ', '$time' );";

        }

        $conn = sql_conn();
        $result =  mysqli_query( $conn , $sql);

        if( !$result ){
            echo "Error Occured During Insertion of ". $applicantID ."  Notification";
        }
        

    }

    else if( $type === 'task' ){

         //Update the status of Adjustment
         $query = "UPDATE taskadjustments SET status='$action' WHERE taskAdjustmentID = $adjID";
         $conn = sql_conn();
         $result = mysqli_query($conn, $query);

         if( !$result ){
            echo Utils::alert("Errro Occured");
         }
     
         // Insert instance into notification table for applicant id
     
         $time = date( 'Y-m-d H:i:s' , time());
     
         if( $action === Config::$_ADJUSTMENT_STATUS['ACCEPTED'] ){

             $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$applicantID', 'Your Task Adjustment has been accepted by $user->fullName ', '$time' );";

        }else{

             $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$applicantID', 'Your Task Adjustment has been rejected by $user->fullName ', '$time' );";

         }

     
         $conn = sql_conn();
         $result =  mysqli_query( $conn , $sql);
     
         if( !$result ){
            echo "Error Occured During Insertion of ". $applicantID ."  Notification";
         }
 

    }


?>


