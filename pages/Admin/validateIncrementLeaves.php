<?php ob_start();
    //  Creates database connection 
    require "../../includes/db.conn.php";
    
    //include Config Class
    require('../../utils/Utils.class.php');
    require('../../utils/Config/Config.class.php');

    //include class definition
    require('../../utils/Admin/admin.class.php');

    //start session
    session_start();
    
    $leaveID = $_GET['leaveID'];
    $newDate = $_GET['newDate'];
    $carryforward = $_GET['carryforward'];

?>


<?php

    try{

        //credit increments to all employees
        $emps = Utils::getAllEmployees();

        $leaveDetails = mysqli_fetch_assoc(Utils::getLeaveDetails($leaveID));

        // Get Leave Details
        $transaction_PENDING = Config::$_TRANSACTION_STATUS['PENDING'];
        $transaction_FAILED = Config::$_TRANSACTION_STATUS['FAILED'];
        $transaction_SUCCESSFULL = Config::$_TRANSACTION_STATUS['SUCCESSFULL'];
        $time = date( 'Y-m-d H:i:s' , time());


        if( empty($carryforward) ){

            // update the new cycleDate
            $sql = "UPDATE masterdata SET cycleDate='$newDate'  where leaveID=$leaveID";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            while( $emp = mysqli_fetch_assoc($emps)  ){
                
                $reason = "Cyclic Increment";
                $empID = $emp['employeeID'];
                $amount = (float)$leaveDetails['increment'];
                
                if( $emp['role'] == Config::$_EMPLOYEE_ROLE['FACULTY'] ){
                    
                    $balance = mysqli_fetch_assoc(Utils::getLeaveBalanceOfEmployeeForSpecificLeave($empID , $leaveID));
                    $updatdeBalance = (float)($balance['balance']) + $amount;

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
                                
                    }else{
                        
                        // Set transaction as Successfull
                        $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$leaveID and status = '$transaction_PENDING'";
                        $conn = sql_conn();
                        $result = mysqli_query( $conn , $sql);
                
                        $leaveName = $leaveDetails['leaveType'];
                        $notification = "$amount $leaveName Credited Reason : $reason .<a href=./dashboard.php > Check Balance </a>";
                
                        // 7. Send Notification to Employee
                        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', '$notification' , '$time' );";
                        $conn = sql_conn();
                        $result =  mysqli_query( $conn , $sql);
                
                    }

                }

            }

            // Set a session variable with the response message
            $_SESSION['response_message'] = serialize(["All Employees has been Credited $amount $leaveName Successfully", "SUCCESS"]);

        }
        else{

            // Update employees balance

            while( $emp = mysqli_fetch_assoc($emps)  ){
                
                $reason = "".$leaveDetails['leaveType']."kCarryForwarded";
                $empID = $emp['employeeID'];

                //get the amount of leaves that is being carryforwarded into

                $sql = "SELECT * from leavebalance where employeeID=$empID and leaveID=$leaveDetails[carryForwardInto]";
                $conn = sql_conn();
                $carryforwardIntoDetails = mysqli_fetch_assoc(mysqli_query( $conn , $sql));
                
                
                if( $emp['role'] == Config::$_EMPLOYEE_ROLE['FACULTY'] ){
                    
                    $balance = mysqli_fetch_assoc(Utils::getLeaveBalanceOfEmployeeForSpecificLeave($empID , $leaveID));
                    $amount = (float)($balance['balance']);

                    $updatdeBalance = (float)($balance['balance']) + (float)($carryforwardIntoDetails['balance']) ;

                    // Start Transaction
                    $sql = "INSERT INTO leavetransactions (`transactionID`, `applicantID`, `leaveID`, `date`, `reason`, `status`, `balance`) VALUES (NULL, $empID , $carryforwardIntoDetails[leaveID] , current_timestamp(), '$reason', '$transaction_PENDING', '$amount' );";
                    $conn = sql_conn();
                    $result =  mysqli_query( $conn , $sql);

                    // Get Transaction ID
                    $sql = "Select * from leavetransactions where applicantID=$empID and leaveID=$carryforwardIntoDetails[leaveID] and status = '$transaction_PENDING'";
                    $conn = sql_conn();
                    $result =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));
                    $transactionID = $result['transactionID'];

                    // Update leaveBalance
                    $sql = "UPDATE leavebalance SET `balance` = $updatdeBalance , `lastUpdatedOn`=$transactionID where employeeID=$empID and leaveID=$carryforwardIntoDetails[leaveID]";
                    $conn = sql_conn();
                    $updateResult =  mysqli_query( $conn , $sql);

                    if( !$updateResult ){

                        //Set transaction as Failed
                        $sql = "Update leavetransactions set status='$transaction_FAILED' where applicantID=$empID and leaveID=$carryforwardIntoDetails[leaveID] and status = '$transaction_PENDING'";
                        $conn = sql_conn();
                        $result = mysqli_query( $conn , $sql);
                        throw new Exception("Error Occured during Updating Balance");
                                
                    }else{
                        
                        // Set transaction as Successfull
                        $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$carryforwardIntoDetails[leaveID] and status = '$transaction_PENDING'";
                        $conn = sql_conn();
                        $result = mysqli_query( $conn , $sql);
                
                        $leaveName = $carryforwardIntoDetails['leaveType'];
                        $notification = "$amount $leaveName Credited Reason : $reason .<a href=./dashboard.php > Check Balance </a>";
                
                        // 7. Send Notification to Employee
                        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', '$notification' , '$time' );";
                        $conn = sql_conn();
                        $result =  mysqli_query( $conn , $sql);

                        //------------------------------- update the current leave balance to zero ---------------------------

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
                        $sql = "UPDATE leavebalance SET `balance` = 0 , `lastUpdatedOn`=$transactionID where employeeID=$empID and leaveID=$leaveID";
                        $conn = sql_conn();
                        $updateResult =  mysqli_query( $conn , $sql);

                        if( !$updateResult ){

                            //Set transaction as Failed
                            $sql = "Update leavetransactions set status='$transaction_FAILED' where applicantID=$empID and leaveID=$leaveID and status = '$transaction_PENDING'";
                            $conn = sql_conn();
                            $result = mysqli_query( $conn , $sql);
                            throw new Exception("Error Occured during Updating Balance");
                                    
                        }else{

                            // Set transaction as Successfull
                            $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$leaveID and status = '$transaction_PENDING'";
                            $conn = sql_conn();
                            $result = mysqli_query( $conn , $sql);
                    
                            $leaveName = $leaveDetails['leaveType'];
                            $notification = "$leaveName updated to zero : $reason .<a href=./dashboard.php > Check Balance </a>";
                    
                            // 7. Send Notification to Employee
                            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', '$notification' , '$time' );";
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);


                        }
                
                    }

                }

            }

            // Set a session variable with the response message
            $_SESSION['response_message'] = serialize(["The $leaveName has been Carryforwarded", "SUCCESS"]);


        }


        // Redirect back to addLeave.php
        header("Location: creditLeaves.php");
        exit();

                
        
    }
    catch(Exception $e){

        $errorMessage = $e->getMessage();
        echo $errorMessage;
    
        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);
    
        header("Location: creditLeaves.php");
        exit();
        
    }

?>