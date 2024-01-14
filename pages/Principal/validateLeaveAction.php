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
    require('../../utils/Principal/Principal.class.php');

    //start session
    

    $user = unserialize($_SESSION['user']) ;
    $applicationID = $_GET['id'];
    $action = $_GET['action'];
    $extension = $_GET['extension'];

    $data =  mysqli_fetch_assoc( $user->viewDetailApplication($applicationID) );


?>

<?php

    try{


        //------------------------------- Withdraw the previous Application -------------------------------

        //If this application is extension of some another application then withdraw prev application
        if( !empty( $data['extension'] ) ){
            
            //Call withdrawApplication page
            
            $url = "http://localhost/LMS1/pages/Staff/withdrawApplication.php?id=$data[extension]&empID=$data[employeeID]&return=true";

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ),
            );
            $context  = stream_context_create($options);
            $html = file_get_contents($url, false, $context);

            // If the return response is not true that means there is an error

            if( !$html ){

                throw new Exception("Error Occured");

            }

        }

        // ------------------------------ Perform Action --------------------------------------


        //Intial Status
        $status = Config::$_APPLICATION_STATUS['APPROVED_BY_HOD'];
        $principalApproval = Config::$_PRINCIPAL_STATUS['PENDING'];
        
        if( $action === 'APPROVE' ){
            
            $status = Config::$_APPLICATION_STATUS['APPROVED_BY_PRINCIPAL'];
            $principalApproval = Config::$_PRINCIPAL_STATUS['APPROVED'];

        }

        else if( $action === 'REJECT' ){
            
            $status = Config::$_APPLICATION_STATUS['REJECTED_BY_PRINCIPAL'];
            $principalApproval = Config::$_PRINCIPAL_STATUS['REJECTED'];

        }

        else if( $action === 'DEDUCTFROMEL' ){
            
            $status = Config::$_APPLICATION_STATUS['DEDUCTED_FROM_EL'];
            $principalApproval = Config::$_PRINCIPAL_STATUS['APPROVED'];

        }

        else if( $action === 'LWP' ){
            
            $status = Config::$_APPLICATION_STATUS['LEAVE_WITHOUT_PAY'];
            $principalApproval = Config::$_PRINCIPAL_STATUS['APPROVED'];

        }
        

        $empID = $data['employeeID'];
        $time = date( 'Y-m-d H:i:s' , time());
        
        $transaction_PENDING = Config::$_TRANSACTION_STATUS['PENDING'];
        $transaction_FAILED = Config::$_TRANSACTION_STATUS['FAILED'];
        $transaction_SUCCESSFULL = Config::$_TRANSACTION_STATUS['SUCCESSFULL'];
        $amount = $data[ 'totalDays' ]; //total Amount

        
        if( $action === 'APPROVE' || $action === 'DEDUCTFROMEL' ){

            $amount = 0 - $amount;

            $amount = abs( $amount);

            //1. deduct amount of leaves from leavebalance table

                //check if there is sufficient balance

                //get the current balance and amount of leave taken for each leave type
                $leaveQuery = "SELECT leavetype.leaveID , leavetype.totalDays  , leavebalance.employeeID , leavebalance.leaveType ,  leavebalance.leaveCounter , leavebalance.balance from leavetype inner join leavebalance on leavetype.leaveID = leavebalance.leaveID where leavetype.applicationID = $applicationID and leavebalance.employeeID = $empID;";
                $conn = sql_conn();
                $result =  mysqli_query( $conn , $leaveQuery);

                
                //Check if for any leaveType there is insuffcient Balance
                while( $row =  mysqli_fetch_assoc( $result) ){

                    //If action is DEDUCTFROMEL then check if we can deduct total days from EL balance
                    if( $action === 'DEDUCTFROMEL' ){

                        //Get Earned Leave Balance
                        $leaveQuery = "SELECT balance from leavebalance where leavebalance.employeeID = $empID;";
                        $conn = sql_conn();
                        $result = mysqli_fetch_assoc( mysqli_query( $conn , $leaveQuery) );

                        if( ceil($amount) > $result['balance'] ){

                            throw new Exception( "Insufficient Balance" );
    
                        }

                        break;

                    }else{

                        if( floatval($row['totalDays']) > floatval($row['balance']) ){
                            throw new Exception( "Insufficient Balance" );
    
                        }

                    }

                }

                $leaveResult =  mysqli_query( $conn , $leaveQuery);

                //Deduct the balance
                while( $row =  mysqli_fetch_assoc( $leaveResult) ){
    
                    
                    // Start Transaction
                    $sql = "INSERT INTO leavetransactions (`transactionID`, `applicantID`, `leaveID`, `date`, `reason`, `status`, `balance`) VALUES (NULL, $empID , $row[leaveID] , current_timestamp(), 'Leave Approved ( Deducting Leaves )', '$transaction_PENDING', '$amount' );";
                    $conn = sql_conn();
                    $result =  mysqli_query( $conn , $sql);

                    //Get Transaction ID

                    $sql = "Select * from leavetransactions where applicantID=$empID and leaveID=$row[leaveID] and status ='$transaction_PENDING'";
                    $conn = sql_conn();
                    $result =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));
                    $transactionID = $result['transactionID'];

                    if( $action === 'DEDUCTFROMEL' ){

                            //Get Earned Leave Balance
                            $ELQuery = "SELECT * from leavebalance where leavebalance.employeeID = $empID;";
                            $conn = sql_conn();
                            $ELBalance = mysqli_fetch_assoc( mysqli_query( $conn , $ELQuery) );

                            $newBalance = $ELBalance[ 'balance' ] - ceil( $amount ) ;
                            $leaveCounter = $ELBalance[ 'leaveCounter' ] + 1 ;
                            $leaveID = $ELBalance['leaveID'];
                            $deductingAmount = ceil($amount);
                            $leaveName = $ELBalance['leaveType'];

                            //Deduct from leavebalance
                            $sql = " UPDATE leavebalance SET balance='$newBalance' , leaveCounter='$leaveCounter' , lastUpdatedOn='$transactionID' where employeeID='$empID' and leaveType='Earned Leave' ";
                
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);
                            
                            //Send Notification
                            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Deducting $deductingAmount from $leaveName', '$time' );";
                            
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);
                
                            //End Transaction
                            $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$row[leaveID] and status = '$transaction_PENDING'";
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);

                            break;

                    }else{

                        $newBalance = $row[ 'balance' ] - $row[ 'totalDays' ] ;
                        $leaveCounter = $row[ 'leaveCounter' ] + 1 ;
                        $leaveID = $row['leaveID'];
                        $deductingAmount = $row['totalDays'];
                        $leaveName = $row['leaveType'];

                        //Deduct from leavebalance
                        $sql = " UPDATE leavebalance SET balance='$newBalance' , leaveCounter='$leaveCounter' , lastUpdatedOn='$transactionID' where employeeID='$empID' and leaveID='$leaveID' ";
            
                        $conn = sql_conn();
                        $result =  mysqli_query( $conn , $sql);
                        
                        //Send Notification
                        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Deducting $deductingAmount from $leaveName', '$time' );";
                        
                        $conn = sql_conn();
                        $result =  mysqli_query( $conn , $sql);
            
                        //End Transaction
                        $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$row[leaveID] and status = '$transaction_PENDING'";
                        $conn = sql_conn();
                        $result =  mysqli_query( $conn , $sql);
            
                        if( !$result ){
                            // throw new Exception("Errro Occured");
                        }

                    }

                }

        }

        else if( $action === 'REJECT' ) {

                //Send Notification
                $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Leave Application $status', '$time' );";
    
                $conn = sql_conn();
                $result =  mysqli_query( $conn , $sql);

        }

        else if( $action === 'LWP' ) {


            //get Leave Details
            $sql = "SELECT * FROM `leavebalance` WHERE leaveType= 'Leave Without Pay' and employeeID = '$empID'";
            $conn = sql_conn();
            $leaveDetails =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));

            $counter = $leaveDetails['leaveCounter']+1;
            $total = $leaveDetails['balance'] + $amount;
            $leaveID = $leaveDetails['leaveID'];

            // Start Transaction
            $sql = "INSERT INTO leavetransactions (`transactionID`, `applicantID`, `leaveID`, `date`, `reason`, `status`, `balance`) VALUES (NULL, $empID , $leaveID , current_timestamp(), 'Leave Approved ( Adding Leaves Leave Without Pay )', '$transaction_PENDING', '$amount' );";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);


            //Get Transaction ID
            $sql = "Select * from leavetransactions where applicantID=$empID and leaveID=$leaveID and status ='$transaction_PENDING'";
            $conn = sql_conn();
            $result =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));
            $transactionID = $result['transactionID'];



            //Adding amount in LWP 
            $sql = "UPDATE leavebalance  SET `balance`='$total' , `leaveCounter`='$counter' , `lastUpdatedOn` = '$transactionID' where `employeeID`='$empID' and `leaveID`='$leaveID' ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                throw new Exception( "Amount not Updated" );
            }

            //Send Notification
            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Leave Application APPROVED by Principal with $status', '$time' );";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            //End Transaction
            $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$leaveID and status = '$transaction_PENDING'";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                // throw new Exception("Errro Occured");
                // echo "Error Occured During Insertion of ". $empID ."  Notification";
            }

        }

                // Update the status of Adjustment
                $query = "UPDATE applications SET status='$status', principalApproval = '$principalApproval' WHERE applicationID = $applicationID";

                $conn = sql_conn();
                $result = mysqli_query($conn, $query);

                //Send Notification
                $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Application Approved by Principal', '$time' );";

                $conn = sql_conn();
                $result =  mysqli_query( $conn , $sql);


                if( !$result ){
                    throw new Exception("Errro Occured");
                }


            // Set a session variable with the response message
            $_SESSION['response_message'] = serialize(["Leave $action Successfully" , "SUCCESS"]);

            // Redirect back to addLeave.php
            header("Location: ../Staff/viewDetails.php?id=$applicationID");
            exit();


    }
    catch( Exception $e){
                
        $errorMessage = $e->getMessage();
        echo $errorMessage;
    
        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);

        header("Location: ../Staff/viewDetails.php?id=$applicationID");
        exit();
    
    }
    ob_end_flush();

?>


