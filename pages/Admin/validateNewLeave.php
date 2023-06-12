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
        
            $time = date( 'Y-m-d H:i:s' , time());

            echo $time;

            //Send Notification to Admin
            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$user->employeeId', 'New Leave Type Added Sucessfully.<a href=./manageMasterData.php > View Details </a>', '$time' );";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                echo "Error Occured During Insertion of Notification";
            }else{

                header('location : ./addLeave.php');
                exit(0);

            }


    }



    
}
catch(Exception $e){

    echo $e;

    echo "<script>
        window.location.href = './addLeave.php'
    </script>";
    
}




?>