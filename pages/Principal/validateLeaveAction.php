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
    require('../../utils/Principal/Principal.class.php');

    //start session
    session_start();

    $user =  $_SESSION['user'];
    $applicationID = $_GET['id'];
    $action = $_GET['action'];

    $data =  mysqli_fetch_assoc( $user->viewDetailApplication($applicationID) );

?>

<?php

    try{

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

        // Update the status of Adjustment
        $query = "UPDATE applications SET status='$status', principalApproval = '$principalApproval' WHERE applicationID = $applicationID";

        $conn = sql_conn();
        $result = mysqli_query($conn, $query);

        if( !$result ){
            echo Utils::alert("Errro Occured");
        }

        $empID = $data['employeeID'];
        $time = date( 'Y-m-d H:i:s' , time());
        
        $transaction_PENDING = Config::$_TRANSACTION_STATUS['PENDING'];
        $transaction_FAILED = Config::$_TRANSACTION_STATUS['FAILED'];
        $transaction_SUCCESSFULL = Config::$_TRANSACTION_STATUS['SUCCESSFULL'];
        $amount = $data[ 'totalDays' ];

        
        if( $action === 'APPROVE' || $action === 'DEDUCTFROMEL' ){

            //1. Insert into leavetransaction table
            $amount = 0 - $amount;

            // Start Transaction
            $sql = "INSERT INTO leavetransactions (`transactionID`, `applicantID`, `leaveID`, `date`, `reason`, `status`, `balance`) VALUES (NULL, $empID , $applicationID , current_timestamp(), 'Leave Approved ( Deducting Leaves )', '$transaction_PENDING', '$amount' );";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            //Send Notification
            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Application Approved by Principal', '$time' );";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            $amount = abs( $amount);

            //2. deduct amount of leaves from leavebalance table

                //check if there is sufficient balance

                $leaveQuery = "SELECT leavetype.leaveID , leavetype.totalDays  , leavebalance.employeeID , leavebalance.leaveType ,  leavebalance.leaveCounter , leavebalance.balance from leavetype inner join leavebalance on leavetype.leaveID = leavebalance.leaveID where leavetype.applicationID = $applicationID and leavebalance.employeeID = $empID;";
                $conn = sql_conn();
                $result =  mysqli_query( $conn , $leaveQuery);

                
                //Check if for any leaveType there is insuffcient Balance
                while( $row =  mysqli_fetch_assoc( $result) ){

                    if( $action === 'DEDUCTFROMEL' ){

                        //Get Earned Leave Balance
                        $leaveQuery = "SELECT balance from leavebalance where leavebalance.employeeID = $empID;";
                        $conn = sql_conn();
                        $result = mysqli_fetch_assoc( mysqli_query( $conn , $leaveQuery) );


                        if( ceil($amount) > $result['balance'] ){

                            // End Transaction
                            $sql = "Update leavetransactions set reason='Insuffcient Balance', status='$transaction_FAILED' where applicantID=$empID and leaveID=$applicationID and status = '$transaction_PENDING'";
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);
                            throw new Exception( "Insufficient Balance" );
    
                        }

                        break;

                    }else{

                        if( floatval($row['totalDays']) > floatval($row['balance']) ){

                            // End Transaction
                            $sql = "Update leavetransactions set reason='Insuffcient Balance', status='$transaction_FAILED' where applicantID=$empID and leaveID=$applicationID and status = '$transaction_PENDING'";
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);
                            throw new Exception( "Insufficient Balance" );
    
                        }

                    }




                }

                $leaveResult =  mysqli_query( $conn , $leaveQuery);

                //Deduct the balance
                while( $row =  mysqli_fetch_assoc( $leaveResult) ){
        
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

                            //Get Transaction ID

                            $sql = "Select * from leavetransactions where applicantID=$empID and leaveID=$applicationID and status ='$transaction_PENDING'";
                            $conn = sql_conn();
                            $result =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));
                            $transactionID = $result['transactionID'];

                            //Deduct from leavebalance
                            $sql = " UPDATE leavebalance SET balance='$newBalance' , leaveCounter='$leaveCounter' , lastUpdatedOn='$transactionID' where employeeID='$empID' and leaveType='Earned Leave' ";
                
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);
                            
                            //Send Notification
                            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Deducting $deductingAmount from $leaveName', '$time' );";
                            
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);
                
                            //End Transaction
                            $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$applicationID and status = '$transaction_PENDING'";
                            $conn = sql_conn();
                            $result =  mysqli_query( $conn , $sql);
                
                            if( !$result ){
                                echo "Error Occured During Insertion of ". $empID ."  Notification";
                            }

                            break;

                    }else{

                        $newBalance = $row[ 'balance' ] - $row[ 'totalDays' ] ;
                        $leaveCounter = $row[ 'leaveCounter' ] + 1 ;
                        $leaveID = $row['leaveID'];
                        $deductingAmount = $row['totalDays'];
                        $leaveName = $row['leaveType'];
            
                        //Get Transaction ID

                        $sql = "Select * from leavetransactions where applicantID=$empID and leaveID=$applicationID and status ='$transaction_PENDING'";
                        $conn = sql_conn();
                        $result =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));
                        $transactionID = $result['transactionID'];

                        //Deduct from leavebalance
                        $sql = " UPDATE leavebalance SET balance='$newBalance' , leaveCounter='$leaveCounter' , lastUpdatedOn='$transactionID' where employeeID='$empID' and leaveID='$leaveID' ";
            
                        $conn = sql_conn();
                        $result =  mysqli_query( $conn , $sql);
                        
                        //Send Notification
                        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Deducting $deductingAmount from $leaveName', '$time' );";
                        
                        $conn = sql_conn();
                        $result =  mysqli_query( $conn , $sql);
            
                        //End Transaction
                        $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$empID and leaveID=$applicationID and status = '$transaction_PENDING'";
                        $conn = sql_conn();
                        $result =  mysqli_query( $conn , $sql);
            
                        if( !$result ){
                            echo "Error Occured During Insertion of ". $empID ."  Notification";
                        }

                    }

                }

                
                //ALert the Principal
                Utils::alert( "Leave Approved Successfully" , "SUCCESS" );

        }

        else if( $action === 'REJECT' ) {

                //Send Notification
                $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Leave Application $status', '$time' );";
    
                $conn = sql_conn();
                $result =  mysqli_query( $conn , $sql);

        }


        echo "<script>
            window.location.href = './leave_request.php'
        </script>";


    }catch( Exception $e){
                    
        Utils::alert( "Error Occured" , "ERROR" );

        print_r(e);

        echo "<script>
            window.location.href = './leave_request.php'
        </script>";

    }

?>


