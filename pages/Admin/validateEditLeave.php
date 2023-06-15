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

try{

    //Check Whether Name and Desc is empty or not
    if ( empty($_POST['leaveName']) ) {
        echo Utils::alert("Leave Name cannot be Empty");
        throw new Exception("Leave Name cannot be Empty");
    }

    else if ( empty($_POST['leaveDesc']) ) {
        echo Utils::alert("Leave Description cannot be Empty");
        throw new Exception("Leave Description cannot be Empty");
    }


    //Get the Data
    $leaveID =  $_GET['leaveID'] ;
    $leaveName =  $_POST['leaveName'] ;
    $leaveDesc =  $_POST['leaveDesc'] ;
    $cycleDate =  $_POST['cycleDate'] ;
    $leaveInterval =  $_POST['leaveInterval'] ;
    $leaveIncrement =  $_POST['leaveIncrement'] ;
    $balanceLimit =  $_POST['balanceLimit'] ;
    $applyLimit =  $_POST['applyLimit'] ;
    $waitingTime =  $_POST['waitingTime'];  
    $carryForwardInto =  $_POST['carryForwardInto']; //Gives undefined if not selected but whatever

    //If Null Assign NULL Values
    if( empty($cycleDate) ) $cycleDate = "NULL";
    if( empty($leaveInterval) ) $leaveInterval = "NULL";
    if( empty($leaveIncrement) ) $leaveIncrement = "NULL";
    if( empty($balanceLimit) ) $balanceLimit = "NULL";
    if( empty($applyLimit) ) $applyLimit = "NULL";
    if( empty($waitingTime) ) $waitingTime = "NULL";
    if( empty($carryForwardInto) ) $carryForwardInto = "NULL";


    // ----------------------------------- Validate Leave Name ----------------------------------- //
    
    //Capitalized

    $leaveName = trim(ucwords(strtolower($leaveName))); // lowercase -> captitalized -> trim spaces


    //Check if leave Exists
    $sql = "Select leaveType from masterdata EXCEPT ( Select leaveType from masterdata where leaveID=$leaveID )";
    $conn = sql_conn();
    $leaveTypeResult =  mysqli_query( $conn , $sql);
    

    while( $row = mysqli_fetch_assoc($leaveTypeResult) ){
        

        if( $row['leaveType'] == $leaveName ){
            echo Utils::alert("Leave Name Already Exits");
            throw new Exception("Leave Name Already Exits");
        }
        
        
    }
    
    $MasterData_ACTIVE = Config::$_MASTERADTA_STATUS['ACTIVE'];
    
    //Query to insert leave data into masterdata
    $sql = "UPDATE masterdata SET `leaveType`='$leaveName', `leaveDesc` = '$leaveDesc', `cycleDate` = '$cycleDate', `leaveInterval` = $leaveInterval, `increment`= $leaveIncrement, `carryForwardInto`= $carryForwardInto, `balanceLimit`=$balanceLimit, `applyLimit`=$applyLimit, `waitingTime`=$waitingTime, `status`='$MasterData_ACTIVE' where leaveID=$leaveID";    

    
    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {
        Utils::alert("Opertaion Failed");
        throw new Exception("Error Occured During Query Updation");
    }
    else{

        echo Utils::alert("Leave Updated Successfully");
        echo "<script>
            window.location.href = './manageMasterData.php'
        </script>";

        }


    }
        


    
catch(Exception $e){

    echo $e;

    echo "<script>
        window.location.href = './manageMasterData.php'
    </script>";
    
}




?>