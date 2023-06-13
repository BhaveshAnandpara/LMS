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

?>


<?php

    //Check Whether Name and Desc is empty or not
    if ( empty($_POST['leaveName']) ) echo Utils::alert("Leave Name cannot be Empty");
    else if ( empty($_POST['leaveDesc']) ) echo Utils::alert("Leave Description cannot be Empty");

    //Get the Data
    $leaveName =  $_POST['leaveName'] ;
    $leaveDesc =  $_POST['leaveDesc'] ;
    $cycleDate =  $_POST['cycleDate'] ;
    $leaveInterval =  $_POST['leaveInterval'] ;
    $leaveIncrement =  $_POST['leaveIncrement'] ;
    $balanceLimit =  $_POST['balanceLimit'] ;
    $applyLimit =  $_POST['applyLimit'] ;
    $waitingTime =  $_POST['waitingTime'];  
    $carryForwardInto =  $_POST['carryForwardInto'];

    //If Null Assign NULL Values
    if( empty($cycleDate) ) $cycleDate = "NULL";
    if( empty($leaveInterval) ) $leaveInterval = "NULL";
    if( empty($leaveIncrement) ) $leaveIncrement = "NULL";
    if( empty($balanceLimit) ) $balanceLimit = "NULL";
    if( empty($applyLimit) ) $applyLimit = "NULL";
    if( empty($waitingTime) ) $waitingTime = "NULL";
    if( empty($carryForwardInto) ) $carryForwardInto = "NULL";

    try{

    // ----------------------------------- Validate Leave Name ----------------------------------- //
    
    //Capitalized

    $leaveName = trim(ucwords(strtolower($leaveName))); // lowercase -> captitalized -> trim spaces

    //Check if leave Exists
    $sql = "Select leaveType from masterdata";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);
    
    while( $row = mysqli_fetch_assoc($result) ){
        
        if( $row['leaveType'] == $leaveName ){
            echo Utils::alert("Leave Name Already Exits");
            throw new Exception("Leave Name Already Exits");
        }
        
        
    }
    
    
    //Query to insert data
    $sql = "INSERT INTO masterdata (`leaveID`, `leaveType`, `leaveDesc`, `cycleDate`, `leaveInterval`, `increment`, `carryForwardInto`, `balanceLimit`, `applyLimit`, `waitingTime`, `status`) VALUES (NULL, '$leaveName', '$leaveDesc', '$cycleDate', $leaveInterval, $leaveIncrement, $carryForwardInto , $balanceLimit , $applyLimit , $waitingTime,'ACTIVE')";    
    
    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {
        Utils::alert("Error Occured");
        throw new Exception("Error Occured During Query Insertion");
    }
    else{

        echo Utils::alert("Leave Added Successfully");



            //------------------------------Start Leave transactions To add new Leave type for employees------------------------------//
            
            // 1. Get LeaveID
            $sql = "Select * from masterdata where leaveType='$leaveName'";
            $conn = sql_conn();
            $result =  mysqli_fetch_assoc( mysqli_query( $conn , $sql) );
            
            $leaveID = $result['leaveID'];
            

            // 2. Get Every Employee
            $sql = "Select * from employees where status='ACTIVE'";
            $conn = sql_conn();
            $employeeResult =  mysqli_query( $conn , $sql);

            if( !$employeeResult ) echo "Error Occured";

            //Insert Balance for all Employees

            while( $row = mysqli_fetch_assoc($employeeResult) ){

                $employeeID = $row['employeeID'];

                // 3. Start Transaction
                $sql = "INSERT INTO leavetransactions (`transactionID`, `applicantID`, `leaveID`, `date`, `reason`, `status`, `balance`) VALUES (NULL, $employeeID , $leaveID , current_timestamp(), '$leaveName Added', 'PENDING', '0' );";
                $conn = sql_conn();
                $result =  mysqli_query( $conn , $sql);

                if( !$result ){

                    echo Utils::alert($row['fullName']." Error Occured during Transaction ");

                }

                 // 4. Get Transaction ID
                $sql = "Select * from leavetransactions where applicantID=$employeeID and leaveID=$leaveID and status = 'PENDING'";
                $conn = sql_conn();
                $result =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));

                $transactionID = $result['transactionID'];
                
                // 5. Insert Balance
                $sql = "INSERT INTO leavebalance (`employeeID`, `leaveID`, `leaveType`, `balance`, `leaveCounter`, `lastUpdatedOn`) VALUES ( $employeeID , $leaveID , '$leaveName' , '0', '0', $transactionID )";

                $conn = sql_conn();
                $insertBalance = mysqli_query( $conn , $sql);

                //Error Handling
                if( !$insertBalance ) {

                    //Set transaction as Failed
                    $sql = "Update leavetransactions set status='FAILED' where applicantID=$employeeID and leaveID=$leaveID and status = 'PENDING'";
                    $conn = sql_conn();
                    $result = mysqli_query( $conn , $sql);
                    echo "Error Occured during Adding New Balances";
                    
                }
                else{
                    
                    // 6.Set transaction as Successfull
                    $sql = "Update leavetransactions set status='SUCCESSFULL' where applicantID=$employeeID and leaveID=$leaveID and status = 'PENDING'";
                    $conn = sql_conn();
                    $result = mysqli_query( $conn , $sql);

                    // 7. Send Notification to Admin
                    $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$employeeID', '$leaveName Added.<a href=./dashboard.php > Check Balance </a>', '$time' );";
                    $conn = sql_conn();
                    $result =  mysqli_query( $conn , $sql);

                    if( !$result ){
                        echo "Error Occured During Insertion of Notification";
                    }

                }


            }

        

            $time = date( 'Y-m-d H:i:s' , time());

            echo $time;

            //Send Notification to Admin
            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$user->employeeId', '$leaveName Added Sucessfully.<a href=./manageMasterData.php > View Details </a>', '$time' );";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                echo "Error Occured During Insertion of Notification";
            }else{

                // echo "<script>
                //         window.location.href = './addLeave.php'
                //     </script>";
                exit(0);

            }


    }



    
}
catch(Exception $e){

    echo $e;

    // echo "<script>
    //     window.location.href = './addLeave.php'
    // </script>";
    
}




?>