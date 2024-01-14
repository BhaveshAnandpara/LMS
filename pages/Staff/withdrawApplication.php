<?php ob_start();
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
    require('../../utils/Staff/Staff.class.php');

    //start session
    $applicationID = $_GET['id'];
    $empID = $_GET['empID'];

    $data =  mysqli_fetch_assoc( Utils::viewDetailApplication($applicationID) );

    $conn = sql_conn();
?>

<?php

    try{

        // return all the leaves if approved

        
        if( $data['applicationStatus'] == Config::$_APPLICATION_STATUS['APPROVED_BY_PRINCIPAL'] || $data['applicationStatus'] == Config::$_APPLICATION_STATUS['SANCTIONED']  || $data['applicationStatus'] == Config::$_APPLICATION_STATUS['DEDUCTED_FROM_EL']  || $data['applicationStatus'] == Config::$_APPLICATION_STATUS['LEAVE_WITHOUT_PAY'] ){

            $leaveData = Utils::getApplicationLeaveData($applicationID);
            
            while( $row = mysqli_fetch_assoc($leaveData) ){
                
                //Get the Data
                $action =  'credit';
                $amount =  $row['totalDays'];
                $reason =  'Leave Withdrawn';
                $leaveID =  $row['leaveID'];

                $leaveDetails = mysqli_fetch_assoc( Utils::getLeaveDetailsOfEmployee($empID , $row['leaveID']) );

                $balance = 0;

                if( $leaveDetails['balance'] != NULL || !empty( $leaveDetails['balance'] ) ){
                    $balance += $leaveDetails['balance'];
                }

                $updatdeBalance = $balance + $amount;
                

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
                    throw new Exception("Error Occured during Updating Balance");
                            
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

            }

        }

            //update the application data

                $status = Config::$_APPLICATION_STATUS['WITHDRAWN'];

                // Update the status of Adjustment
                $query = "UPDATE applications SET status='$status' WHERE applicationID = $applicationID";
                $result = mysqli_query($conn, $query);

                if( !$result ){

                    throw new Exception("Error Occured during Updating Application Status");
                            
                }

                // Set a session variable with the response message
                $_SESSION['response_message'] = serialize(["Leave Withdrawn Successfully" , "SUCCESS"]);


                if( !empty($_GET['return'])  ){

                    echo true ;
                    exit();
                }

                // Redirect back to addLeave.php
                header("Location: ../Staff/dashboard.php");
                exit();



    }catch( Exception $e){
                
        $errorMessage = $e->getMessage();
        echo $errorMessage;
    
        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);
    

        if( !empty($_GET['return'])  ){

            echo false ;
            exit();
            
        }


        header("Location: ../Staff/dashboard.php");
        exit();
    
    }
    ob_end_flush();

?>


