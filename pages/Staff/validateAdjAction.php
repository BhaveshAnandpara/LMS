<?php    ob_start();
    session_start();
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


    $user = unserialize($_SESSION['user']) ;
    $type = $_GET['type'];
    $action = $_GET['action'];
    
?>

<?php

    try{




    if( $type === 'lec' ){

        //Get the User Object
        $adjID = $_GET['adjID'];
        $applicantID = $_GET['applicantID'];

        //Update the status of Adjustment
        $query = "UPDATE lectureadjustments SET status='$action' WHERE lecAdjustmentID = $adjID";
        $conn = sql_conn();
        $result = mysqli_query($conn, $query);

        if( !$result ){
            throw new Exception("Employee Email cannot be empty ");
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
            // throw new Exception("Error Occured During Insertion of ". $applicantID ."  Notification");
        }
        
        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize(["Lecture Adjustment $action " , "SUCCESS"]);

    }

    else if( $type === 'task' ){

        //Get the User Object
        $adjID = $_GET['adjID'];
        $applicantID = $_GET['applicantID'];

         //Update the status of Adjustment
         $query = "UPDATE taskadjustments SET status='$action' WHERE taskAdjustmentID = $adjID";
         $conn = sql_conn();
         $result = mysqli_query($conn, $query);

         if( !$result ){
            throw new Exception("Error Occured");
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
            // echo "Error Occured During Insertion of ". $applicantID ."  Notification";
         }

        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize(["Task Adjustment $action" , "SUCCESS"]);



    }

    else if( $type === 'approval' ){

        //Get the User Object
        $applicationID = $_GET['applicationID'];
        $approverId = $_GET['approverId'];

        //Update the status of Adjustment
        $query = "UPDATE approvals SET status='$action' WHERE applicationID = $applicationID and approverId = $approverId ";
        $conn = sql_conn();
        $result = mysqli_query($conn, $query);

        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize(["Approval $action " , "SUCCESS"]);


    }

    header("Location: manageAdjustments.php");
    exit();

    }catch(Exception $e){   

        $errorMessage = $e->getMessage();
        // echo $errorMessage;
    
        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);
    
        // Redirect back to addLeave.php
        header("Location: manageAdjustments.php");
        exit();
        
    }
ob_end_flush();
?>


