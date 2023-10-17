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
    $appID = $_GET['appID'];
    $applicantID = $_GET['empID'];
    $appStatus = $_GET['status'];

?>


<?php

try{


    $withdraw_status = Config::$_APPLICATION_STATUS['WITHDRAWN'];
    
    //Update Applilcation Status
    
    $sql = "UPDATE `applications` SET `status`='$withdraw_status' WHERE `applicationID` = $appID";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);

    if( !$result ){

        echo Utils::alert("Errro Occured During Updating Status", "ERROR");
        throw new Exception("Errro Occured During Updating Status");
        exit(0);
        
    }


    $adj_withdraw_status = Config::$_ADJUSTMENT_STATUS['WITHDRAWN'];

    //Update for Task Adjustments

    $task_sql = "Select adjustedWith , startDate , fullName from taskadjustments inner join employees on taskadjustments.adjustedWith = employees.employeeID WHERE `applicationID` = '$appID'";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $task_sql);
    
    if( !$result ){

        echo "Errro Occured Getting Task details";
        exit(0);

    }

    while( $row = mysqli_fetch_assoc( $result ) ){


        //Update Task Adjustments Status
        $sql = "UPDATE `taskadjustments` SET `status`='$adj_withdraw_status' WHERE `applicationID` = $appID";
        $conn = sql_conn();
        $adj_result =  mysqli_query( $conn , $sql);     


        if( !$adj_result ){     
            echo Utils::alert("Errro Occured During Updating Adjustment Status", "ERROR");
            throw new Exception("Errro Occured During Updating Adjustment Status");
            exit(0);

        }

        $empID = $row['adjustedWith'];
        $time = date( 'Y-m-d H:i:s' , time()); 
        $notification = $row['fullName']." has withdrawn Task Adjustment dated on" . date( 'd-m-Y' , strtotime($row['startDate']) );

        // Send Notification to Employees
        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', '$notification' , '$time' );";
        $conn = sql_conn();
        $notification_result =  mysqli_query( $conn , $sql);   


        if( !$notification_result ){

            echo "Errro Occured During sending Notification to ". $row['fullName'];
            exit(0);

        }

    }
    
    
    //Update for Lecture Adjustments

    $sql = "Select adjustedWith , date , fullName from lectureadjustments inner join employees on lectureadjustments.adjustedWith = employees.employeeID WHERE `applicationID` = $appID";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);

    if( !$result ){

        echo "Errro Occured Getting Lec details";
        exit(0);

    }

    while( $row = mysqli_fetch_assoc( $result ) ){

        //Update Lec Adjustments Status
        $sql = "UPDATE `lectureadjustments` SET `status`='$adj_withdraw_status' WHERE `applicationID` = $appID";
        $conn = sql_conn();
        $adj_result =  mysqli_query( $conn , $sql);

        if( !$adj_result ){

            echo Utils::alert("Errro Occured During Updating Adjustment Status", "ERROR");
            throw new Exception("Errro Occured During Updating Adjustment Status");
            exit(0);

        }


        $empID = $row['adjustedWith'];
        $time = date( 'Y-m-d H:i:s' , time()); 
        $notification = $row['fullName']." has withdrawn Lecture Adjustment dated on" . date( 'd-m-Y' , strtotime($row['date']) );

        // Send Notification to Employees
        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', '$notification' , '$time' );";
        $conn = sql_conn();
        $notification_result =  mysqli_query( $conn , $sql);   

        if( !$notification_result ){

            echo "Errro Occured During sending Notification to ". $row['fullName'];
            exit(0);

        }


    }

    $time = date( 'Y-m-d H:i:s' , time()); 
    $notification = "Your Application has been successfully withdrawn";

    
     // Send Notification to Applicant
     $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$applicantID', '$notification' , '$time' );";
     $conn = sql_conn();
     $notification_result =  mysqli_query( $conn , $sql);       

     if( !$notification_result ){

        echo "Errro Occured During sending Notification to ". $row['fullName'];
        exit(0);

    }

    //Credit Balance


    if ( $status == Config::$_APPLICATION_STATUS['APPROVED_BY_PRINCIPAL'] || $status == Config::$_APPLICATION_STATUS['SANCTIONED'] || $status == Config::$_APPLICATION_STATUS['LEAVE_WITHOUT_PAY'] || $status == Config::$_APPLICATION_STATUS['DEDUCTED_FROM_EL']){


        //Get the Leaves Types connected to leaves

        
        // get Leave Types
        $sql = "SELECT * from leavetypes";
        $conn = sql_conn();
        $result =  mysqli_query( $conn , $sql);


        $transaction_PENDING = Config::$_TRANSACTION_STATUS['PENDING'];
        $transaction_FAILED = Config::$_TRANSACTION_STATUS['FAILED'];
        $transaction_SUCCESSFULL = Config::$_TRANSACTION_STATUS['SUCCESSFULL'];

        $reason = " Leave Withdrawn ( Crediting Leaves Again )";

        $time = date( 'Y-m-d H:i:s' , time());

        // Start Transaction
        $sql = "INSERT INTO leavetransactions (`transactionID`, `applicantID`, `leaveID`, `date`, `reason`, `status`, `balance`) VALUES (NULL, $applicantID , $appID , current_timestamp(), '$reason', '$transaction_PENDING', '$amount' );";
        $conn = sql_conn();
        $result =  mysqli_query( $conn , $sql);

        
    }

}
        


    
catch(Exception $e){

    echo $e;

    // echo "<script>
    //     window.location.href = './viewDetailedEmp.php?empID=$empID'
    // </script>";
    
}




?>