<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
    $conn = sql_conn();
?>

<?php

    //include Config Class
    require('../../utils/Utils.class.php');
    require('../../utils/Config/Config.class.php');

    //include class definition
    require('../../utils/Admin/Admin.class.php');

    //start session
    session_start();


?>

<?php
    
    $total = 0; //Total files
    
    if( isset($_FILES['files']) ){
   
        // Count # of uploaded files in array
        $total = count($_FILES['files']['name']);
        $files =  $_FILES ;

    }

    $user = json_decode( $_POST['user'] , true);
    $applicationDate = json_decode( $_POST['applicationDate'] , true);
    $leaveTypes = json_decode( $_POST['leaveTypes'] , true);
    $reason = json_decode( $_POST['reason'] , true);
    $lecAdjs = json_decode( $_POST['lecAdjs'] , true);
    $taskAdjs = json_decode( $_POST['taskAdjs'] , true);
    $AddApp = json_decode( $_POST['AddApp'] , true);
    

    try{

    $userID = $user['employeeId'];
    $applicationDate = date( 'Y-m-d H:i:s' );
    $startDate =  date( 'Y-m-d' , strtotime($leaveTypes['final']['startDate'] )  ) ;
    $startDateType = $leaveTypes['final']['startDateType'];
    $endDate =  date( 'Y-m-d' , strtotime($leaveTypes['final']['endDate'] )  ) ;
    $endDateType = $leaveTypes['final']['endDateType'];
    $totalDays = $leaveTypes['final']['totalDays'];

    $time = date( 'Y-m-d H:i:s' , time());


    // 1.Add data to appplication table

    $status = Config::$_APPLICATION_STATUS['PENDING'];
    $hodApproval = Config::$_HOD_STATUS['PENDING'];

    if( $user['role'] === Config::$_HOD_ ){

         $hodApproval = Config::$_HOD_STATUS['APPROVED'];
         $status = Config::$_APPLICATION_STATUS['APPROVED_BY_HOD'];
        
    }

    $sql = "INSERT INTO `applications` (`applicationID`, `employeeID`, `status`, `dateTime`, `startDate`, `startDateType`, `endDate`, `endDateType`, `totalDays`, `reason`, `extension`, `hodApproval`, `principalApproval`) VALUES (NULL, $userID, '$status', '$applicationDate' , '$startDate' , '$startDateType', '$endDate', '$endDateType', $totalDays , '$reason' , NULL, '$hodApproval', 'PENDING')";

    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {

        Utils::alert("Some Error Occured , Application was not Submitted", "ERROR");
        throw new Exception("Error Occured During Query Insertion");
        return;
    }

    // 2. Get Application ID 
    $getAppID = "SELECT applicationID from applications where employeeID=$userID and dateTime = '$applicationDate' and reason='$reason' and totalDays='$totalDays' ";

    $result =  mysqli_query( $conn , $getAppID);
    
    if( !$result ) {

        Utils::alert("Some Error Occured , Application was not Submitted", "ERROR");
        throw new Exception("Error Occured During Query Insertion");
        return;
    }

    $result = mysqli_fetch_assoc($result);
    $applicationID = $result['applicationID'];


    // 3. Add data to leaveType

    foreach ($leaveTypes as $key => $value) {
        
        if( $key == 'final' ) continue;

        $leaveID = $leaveTypes[$key]['leaveID'];
        $leaveType = $leaveTypes[$key]['leaveType'];
        $fromDate =  date( 'Y-m-d' , strtotime( $leaveTypes[$key]['fromDate'] )  ) ;
        $fromDateType = $leaveTypes[$key]['fromDateType'];
        $toDate =  date( 'Y-m-d' , strtotime($leaveTypes[$key]['toDate'] )  ) ;
        $toDateType = $leaveTypes[$key]['toDateType'];
        $totalDays = $leaveTypes[$key]['totalDays'];

        $addLeaveType =  "INSERT INTO `leavetype` (`applicationID`, `leaveID`, `leaveType`, `startDate`, `startDateType`, `endDate`, `endDateType`, `totalDays`) VALUES ('$applicationID', '$key', '$leaveType', '$fromDate', '$fromDateType', '$toDate', '$toDateType', '$totalDays')";

        $result =  mysqli_query( $conn , $addLeaveType);
    
        if( !$result ) {
    
            throw new Exception("Error Occured During Leave Type Query Insertion");
            return;
        }
    }
    

    //4. Add approvals

    foreach ($AddApp as $key => $value) {
        
        $addApprovals =  "INSERT INTO `approvals` (`applicationID`, `approverId`, `status`) VALUES ('$applicationID', '$value', 'PENDING');";

        $result =  mysqli_query( $conn , $addApprovals);
    
        if( !$result ) {
    
            throw new Exception("Error Occured During Additional Approvals Query Insertion");
            return;
        }

        // Add Notifications

        $username = $user['fullName'];

        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$value', '$username is waiting for your approval. <a href=`./manageAdjustments.php` >View Details</a> ', '$time' );";
        $conn = sql_conn();
        $result =  mysqli_query( $conn , $sql);
        if( !$result ){
            echo "Error Occured During Insertion of ". $userID ."  Notification";
        }
    

    }

    //5. Add Lecture Adjustments

    foreach ($lecAdjs as $key => $value) {
        
        $adjustWith = $lecAdjs[$key]['lecAdjEmail'];

        $sem = $lecAdjs[$key]['lecAdjSem'];
        $sub = $lecAdjs[$key]['sub'];
        $lecDate =  date( 'Y-m-d' , strtotime($lecAdjs[$key]['lecDate'] )  ) ;
        $lecStartTime =  date( 'H:i:s' , strtotime($lecAdjs[$key]['lecStartTime'] )  ) ;
        $lecEndTime =  date( 'H:i:s' , strtotime($lecAdjs[$key]['lecEndTime'] )  ) ;

        $lecAdjsSql =  "INSERT INTO `lectureadjustments` (`lecAdjustmentID`, `applicationID`, `applicantID`, `adjustedWith`, `status`, `date`, `startTime`, `endTime`, `semester`, `subject`) VALUES (NULL, '$applicationID', '$userID', '$adjustWith', 'PENDING', '$lecDate', '$lecStartTime', '$lecEndTime', '$sem', '$sub');";

        $result =  mysqli_query( $conn , $lecAdjsSql);
    
        if( !$result ) {
    
            throw new Exception("Error Occured During Lecture Adjustments Query Insertion");
            return;
        }


        // Add Notifications

        $username = $user['fullName'];

        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$adjustWith', '$username has requested to adjust Lecture. <a href=`manageAdjustments.php` >View Details</a> ', '$time' );";
        $conn = sql_conn();
        $result =  mysqli_query( $conn , $sql);
        if( !$result ){
            echo "Error Occured During Insertion of ". $userID ."  Notification";
        }

    }
    
    //6. Add Tasks Adjustments

    foreach ($taskAdjs as $key => $value) {
        
        
        $adjustWith = $taskAdjs[$key]['taskAdjEmail'];


        $task = $taskAdjs[$key]['task'];
        $taskFromDate =  date( 'Y-m-d' , strtotime($taskAdjs[$key]['taskFromDate'] )  ) ;
        $taskToDate =  date( 'Y-m-d' , strtotime($taskAdjs[$key]['taskToDate'] )  ) ;

        $taskAdjsSql =  "INSERT INTO `taskadjustments` (`taskAdjustmentID`, `applicationID`, `applicantID`, `adjustedWith`, `status`, `startDate`, `endDate`, `task`) VALUES (NULL, '$applicationID', '$userID', '$adjustWith', 'PENDING', '$taskFromDate', '$taskToDate', '$task');";

        $result =  mysqli_query( $conn , $taskAdjsSql);
    
        if( !$result ) {
    
            throw new Exception("Error Occured During Task Adjustments Query Insertion");
            return;
        }

        // Add Notifications

        $username = $user['fullName'];

        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$adjustWith', '$username has requested to adjust Task. <a href=`manageAdjustments.php` >View Details</a> ', '$time' );";
        $conn = sql_conn();
        $result =  mysqli_query( $conn , $sql);
        if( !$result ){
            echo "Error Occured During Insertion of ". $userID ."  Notification";
        }


    }


    //7. Add Files

    if( $total > 0  ){

        // Loop through each file
        for( $i=0 ; $i < $total ; $i++ ) {

            //Get the temp file path
            $tmpFilePath = $files['files']['tmp_name'][$i];

            //Make sure we have a file path
            if ($tmpFilePath != ""){
                //Setup our new file path
                $newFilePath = "../../uploadFiles/" . $files['files']['name'][$i];


                //Upload the file into the temp dir
                if( move_uploaded_file($tmpFilePath, $newFilePath) ){

                    $name = $files['files']['name'][$i];


                try{
                    
                    $uploadFile =  "INSERT INTO `files` (`applicationID`, `file`) VALUES ( $applicationID, '$newFilePath' )";
                    
                    $result =  mysqli_query( $conn , $uploadFile);
                
                    if( !$result ) {
                
                        throw new Exception("Error Occured During Saving File URL ");
                        return;
                    }

                }catch(Exception $e){

                    // print_r($e);
                    echo "Error";


                }


                }
            }
        }

    }

    
    // Add Notifications

    $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$userID', 'Your Application has been succesfully submitted', '$time' );";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);

    if( !$result ){
        echo "Error Occured During Insertion of ". $userID ."  Notification";
    }

    echo "Application submitted successfully !!";


    }catch(Exception $e){

        print_r( $e);

    }


        

?>