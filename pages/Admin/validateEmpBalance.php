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
    require('../../utils/Admin/Admin.class.php');

    //start session
    session_start();

    //Get the User Object
    $user =  $_SESSION['user'];
    $leaveID = $_GET['leaveID'];
    $empID = $_GET['empID'];

?>


<?php

try{

    //Check Whether Name and Desc is empty or not
    if ( empty($_POST['amount']) ) {
        echo Utils::alert("Amount cannot be Empty", "ERROR");
        throw new Exception("Amount cannot be Empty");
    }

    else if ( empty($_POST['reason']) ) {
        echo Utils::alert("Reason cannot be Empty", "ERROR");
        throw new Exception("Reason cannot be Empty");
    }


    //Get the Data
    $action =  $_POST['manageBalance'];
    $amount =  $_POST['amount'];
    $reason =  $_POST['reason'];

    $leaveDetails = mysqli_fetch_assoc( Utils::getLeaveDetailsOfEmployee($empID , $leaveID) );

    $balance = 0;

    if( $leaveDetails['balance'] != NULL ){
        $balance += $leaveDetails['balance'];
    }

    if( $action == 'debit' ) $updatdeBalance = $balance - $amount;
    if( $action == 'credit' ) $updatdeBalance = $balance + $amount;


    echo 
    "<script>
        if( confirm('Balance after $action will be $updatdeBalance !') ){
            let confirm = true;
        }
    </script>";

    $confirm = "<script> document.write(confirm); </script>";
    
    if( $confirm ){

            $transaction_PENDING = Config::$_TRANSACTION_STATUS['PENDING'];
            $transaction_FAILED = Config::$_TRANSACTION_STATUS['FAILED'];
            $transaction_SUCCESSFULL = Config::$_TRANSACTION_STATUS['SUCCESSFULL'];
            $time = date( 'Y-m-d H:i:s' , time());

            // Start Transaction
            $sql = "INSERT INTO leavetransactions (`transactionID`, `applicantID`, `leaveID`, `date`, `reason`, `status`, `balance`) VALUES (NULL, $empID , $leaveID , current_timestamp(), '$reason', '$transaction_PENDING', '$amount' );";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            // Get Transaction ID
            $sql = "Select * from leavetransactions where applicantID=$empID and leaveID=$leaveID and status = '$transaction_PENDING'";
            $conn = sql_conn();
            $result =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));
            $transactionID = $result['transactionID'];

            // Update leaveBalance
            $sql = "UPDATE leavebalance SET `balance` = $updatdeBalance , `lastUpdatedOn`=$transactionID where employeeID=$empID and leaveID=$leaveID";
            $conn = sql_conn();
            $updateResult =  mysqli_query( $conn , $sql);

            if( !$updateResult ){

                //Set transaction as Failed
                $sql = "Update leavetransactions set status='$transaction_FAILED' where applicantID=$empID and leaveID=$leaveID and status = '$transaction_PENDING'";
                $conn = sql_conn();
                $result = mysqli_query( $conn , $sql);
                echo "Error Occured during Updating Balance";
                
            }
            else{
                    
                // Set transaction as Successfull
                $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$leaveID and status = '$transaction_PENDING'";
                $conn = sql_conn();
                $result = mysqli_query( $conn , $sql);

                $leaveName = $leaveDetails['leaveType'];
                $notification = "$amount $leaveName " .$action. "ed. Reason : $reason .<a href=./dashboard.php > Check Balance </a>";

                // 7. Send Notification to Employee
                $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', '$notification' , '$time' );";
                $conn = sql_conn();
                $result =  mysqli_query( $conn , $sql);

            }


            echo "<script>
                window.location.href = './viewDetailedEmp.php?empID=$empID'
            </script>";
            

    }


   

    }
        


    
catch(Exception $e){

    echo $e;

    echo "<script>
        window.location.href = './viewDetailedEmp.php?empID=$empID'
    </script>";
    
}




?>