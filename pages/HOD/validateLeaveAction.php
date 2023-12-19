<?php session_start();
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

    $user = unserialize($_SESSION['user']);
    $applicationID = $_GET['id'];
    $action = $_GET['action'];

    $data =  mysqli_fetch_assoc( $user->viewDetailApplication($applicationID) );

?>

<?php

        try{

            $status = Config::$_APPLICATION_STATUS['PENDING'];
            $hodApproval = Config::$_HOD_STATUS['PENDING'];
            
            if( $action === 'APPROVE' ){
                
                $status = Config::$_APPLICATION_STATUS['APPROVED_BY_HOD'];
                $hodApproval = Config::$_HOD_STATUS['APPROVED'];

            }

            else if( $action === 'REJECT' ){
                
                $status = Config::$_APPLICATION_STATUS['REJECTED_BY_HOD'];
                $hodApproval = Config::$_HOD_STATUS['REJECTED'];

            }

            //Update the status of Adjustment
            $query = "UPDATE applications SET status='$status', hodApproval = '$hodApproval' WHERE applicationID = $applicationID";

            $conn = sql_conn();
            $result = mysqli_query($conn, $query);

            if( !$result ){
                throw new Exception("Error Occured");
            }

            // Insert instance into notification table for applicant id

            $empID = $data['employeeID'];

            $time = date( 'Y-m-d H:i:s' , time());

            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Your Application has been $hodApproval by department HOD ', '$time' );";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                // echo "Error Occured During Insertion of ". $empID ."  Notification";
            }


            // Set a session variable with the response message
            $_SESSION['response_message'] = serialize(["Request $action Successfully" , "SUCCESS"]);

            // Redirect back to addLeave.php
            header("Location: ../Staff/viewDetails.php?id=$applicationID");
            exit();


    }catch( Exception $e){
                
    $errorMessage = $e->getMessage();
    echo $errorMessage;

    // Set a session variable with the response message
    $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);

    header("Location: ../Staff/viewDetails.php?id=$applicationID");
    exit();

    }

?>


