<?php
    //start session
    session_start();
    
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>

<!-- Include this to use User object -->
<?php
    //include class definition
    require('../../utils/Staff/staff.class.php');
    require('../../utils/Principal/Principal.class.php');

    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');
    
    //Get the User Object
    $user = unserialize($_SESSION['user']) ;
    // to get application idand reason
    $applicationId = $_GET['id'];
    $data =  mysqli_fetch_assoc( $user->viewDetailApplication($applicationId) );

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bajaj Institute of Technology, Wardha</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- all common CSS link  -->
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/Admin/manageMasterData.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- all common Script  -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>
</head>
<body>
    <!--Including sidenavbar -->
    <?php

        if( $user->role === Config::$_PRINCIPAL_ ) include "../../includes/Principal/sidenavbar.php";
        if( $user->role === Config::$_HOD_ ) include "../../includes/HOD/sidenavbar.php";
        if( $user->role === Config::$_FACULTY_ ) include "../../includes/Staff/sidenavbar.php";
    ?>
    <section class="home-section">
        <!--Including header -->
        <?php
        include "../../includes/header.php";
        ?>
        <div class="container mb-5">

            <!------------------------------ Basic Info ------------------------------>
            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" method="POST">

                <h4 class="pb-3 pt-2  " style="color: #11101D;">Basic Information <i id="basic-info" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>
                <div class="form-row" id="basic-info-container">

                    <!-- //ROW -->
                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Applicant ID</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $data['employeeID'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Applicant Name</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $data['fullName'] ?>
                        </div>
                    </div>


                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Applicant Email</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $data['email'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Department</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php
                            $deptInfo = mysqli_fetch_assoc(Utils::getDeptDetails($user->deptID));
                            echo $deptInfo['deptName']
                            ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Application Date</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo date( 'd-m-Y' , strtotime($data['startDate']) )  ?> - <?php echo date( 'd-m-Y' , strtotime($data['endDate']) ) ?>
                        </div>
                    </div>
                </div>
            </div>


            <!------------------------------ Leave Balance ------------------------------>
            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">


                <h4 class="pb-3 pt-2  " style="color: #11101D;">Leave Balance <i id="leave-balance"
                        class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                <div class="form-row" id="leave-balance-container">

                    <table class="tablecontent" id="leave-balance-table">

                        <thead>
                            <tr>
                                <th>LEAVE ID</th>
                                <th>LEAVE NAME</th>
                                <th>BALANCE</th>
                                <th>COUNTER</th>
                                <th>LAST UPDATE ON</th>
                                <th>REASON</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                            <?php

                                $leavebalanceDetails =  Utils::getLeaveBalanceOfEmployee($data['employeeID']); 

                                while($row =  mysqli_fetch_assoc($leavebalanceDetails) ){

                                    echo "<tr>";
                                    echo "<td  >" . $row['leaveID'] . "</td>";
                                    echo "<td  >" . $row['leaveType'] . "</td>";
                                    echo "<td  >" . $row['balance'] . "</td>";
                                    echo "<td  >" . $row['leaveCounter'] . " </td>";
                                    echo "<td  >" . date( 'd-m-Y' , strtotime($row['date']) )  . " </td>";
                                    echo "<td  >" . $row['reason']  . " </td>";
                                }
                            
                            ?>
                        </tbody>
                    </table>


                </div>

            </div>


            <!------------------------------ Application Details ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" method="POST">
                <h4 class="pb-3 pt-2  " style="color: #11101D;">Application Details <i id="app-detail" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                <div class="form-row" id="app-detail-container">

                    <!-- //ROW -->
                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Start Date</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo  date( 'd-m-Y' , strtotime($data['startDate']) ) ?>
                        </div>
                    </div>

                    <!-- //ROW -->
                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Start Date Type</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $data['startDateType'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->
                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">End Date</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo  date( 'd-m-Y' , strtotime($data['endDate']) ) ?>
                        </div>
                    </div>

                    <!-- //ROW -->
                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">End Date Type</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $data['endDateType'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->
                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Total Days</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $data['totalDays'] ?> Days
                        </div>
                    </div>

                    <!-- //ROW -->
                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Leave Type</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $leaveType = $user->getAppLeaveTypes( $data['applicationID'] ) ?>
                        </div>
                    </div>

                    <!-- //ROW -->
                    <div class="form-group col-md-12">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Reason</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $data['reason'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->
                    <div class="form-group col-md-12">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Application Status</h6>
                        </div>
                        <div class="col-sm-3 bold d-inline text-secondary">
                            <span class=<?php echo $data['applicationStatus'] ?> >  <?php echo $data['applicationStatus'] ?> </span>
                        </div>
                    </div>

                    <!-- //Approver table -->
                    <div class="form-group col-md-12">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-3 d-inline ">Additional Approvals</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            
                    <?php
                    
                        $additionalApp =  $user->getApprovalRequst($applicationId); 

                    
                        if( mysqli_num_rows($additionalApp) == 0){
                            echo " <span> No Additional Approvals </span>";
                        }
                        else{

                    
                    ?>

                    <!-- table here -->
                    <table class="tablecontent" id="add-app-table">

                        <thead>
                            <tr>
                                <th>APPROVER ID</th>
                                <th>APPROVER EMAIL</th>
                                <th>APPROVER NAME</th>
                                <th>APPROVER DEPARTMENT</th>
                                <th>APPROVER ROLE</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                            <?php


                                while($row =  mysqli_fetch_assoc($additionalApp) ){

                                    $approverInfo = mysqli_fetch_assoc (Utils::getEmpDetails( $row['approverId'] ) );

                                    echo "<tr>";
                                    echo "<td  >" . $row['approverId'] . "</td>";
                                    echo "<td  >" . $approverInfo['email'] . "</td>";
                                    echo "<td  >" . $approverInfo['fullName'] . "</td>";
                                    echo "<td  >" . $approverInfo['deptName'] . "</td>";
                                    echo "<td  >" . $approverInfo['role'] . " </td>";
                                    echo "<td class=" .$row['status']. " >" . $row['status']  . " </td>";
                                }
                            
                            ?>
                        </tbody>
                    </table>

                    <?php }?>


                        </div>
                    </div>

                </div>
            </div>

            <!------------------------------ Adjustment Details ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" method="POST">

                <h4 class="pb-3 pt-2  " style="color: #11101D;">Adjustment Details<i id="leave-adjustment" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>
                <!-- main div for adjustment  -->

                <div id="leave-adjustment-container">

                    <!-- Lecture Adjustment Details -->

                    <div class="form-row">
                        <h5 class="pb-3 pt-2  " style="color: #11101D;">Lecture Adjustment Details </h5>

                    <?php
                    
                        $lecData =  $user->getLectureAdjustments($applicationId);

                    
                        if( mysqli_num_rows($lecData) == 0){
                            echo "<p style=' width : 100%; text-align : center; ' > No Lecture Adjustments </p>";
                        }
                        else{

                    
                    ?>

                        <table class="tablecontent" id="leave-balance-table">
                            <thead>
                                <tr>
                                    <th>Adjusted With</th>
                                    <th>Semester</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php

                                // get lecture adjustment details of that perticuler application 

                                $lecData =  $user->getLectureAdjustments($applicationId);

                                while ($row = mysqli_fetch_assoc($lecData)) {

                                    echo "<tr>";
                                    echo "<td  >" . $row['fullName'] . "</td>";
                                    echo "<td  >" . $row['semester'] . "</td>";
                                    echo "<td  >" . $row['subject']  . " </td>";
                                    echo "<td  >" . $row['date']  . " </td>";
                                    echo "<td  >" . $row['startTime'] . "</td>";
                                    echo "<td  >" . $row['endTime'] . " </td>";
                                    echo "<td class=" .$row['status']. " >" . $row['status']  . " </td>";
                                }
                                ?>
                            </tbody>
                        </table>


                    <?php }?>
                    </div>

                    <!-- Task Adjustment Details  -->

                    <div class="form-row mt-2">

                        <h5 class="pb-3 pt-2 mt-2 " style="color: #11101D;">Task Adjustment Details </h5>

                        <?php
                    
                            $lecData =  $user->getTaskAdjustments($applicationId);

                        
                            if( mysqli_num_rows($lecData) == 0){
                                echo "<p style=' width : 100%; text-align : center; ' > No Task Adjustments </p>";
                            }
                            else{
                        
                        ?>

                        <table class="tablecontent" id="leave-balance-table">
                            <thead>
                                <tr>
                                    <th>Adjusted With</th>
                                    <th>Form Date</th>
                                    <th>To Date</th>
                                    <th>Task</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php

                                // get lecture adjustment details of that perticuler application 

                                while ($row = mysqli_fetch_assoc($lecData)) {

                                    echo "<tr>";
                                    echo "<td  >" . $row['fullName'] . "</td>";
                                    echo "<td  >" . $row['startDate'] . "</td>";
                                    echo "<td  >" . $row['endDate'] . " </td>";
                                    echo "<td  >" . $row['task']  . " </td>";
                                    echo "<td class=" .$row['status']. " >" . $row['status']  . " </td>";
                                }
                                ?>
                            </tbody>
                        </table>

                        <?php } ?>

                    </div>
                </div>
            </div>


            <!------------------------------ file details ------------------------------>
            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg mb-5" method="POST">
                <h4 class="pb-3 pt-2  " style="color: #11101D;">Uploaded Documents <i id="file-details" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>
                <div class="form-row" id="file-details-container">

                <?php
                    
                    $fileData =  $user->getFileDetails($applicationId);

                
                    if( mysqli_num_rows($fileData) == 0){
                        echo "<p style=' width : 100%; text-align : center; ' > No Files Attached </p>";
                    }
                    else{
                
                ?>

                    <table class="tablecontent" id="leave-balance-table">
                        <thead>
                            <tr>
                                <th>Document Name </th>
                                <th>Uploaded File</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                        <?php
                            // to get additional informatin details 

                            while ($row = mysqli_fetch_assoc($fileData)) { ?>
                            <tr>
                                <td> <?php echo  substr( $row['file'] , 18 , strlen($row['file']) )  ?> </td>
                                <td>
                                    <a href="<?php echo $row['file']; ?>" class="btn btn-success">View Document</a>
                                </td>
                                <?php  } ?>
                        </tbody>
                    </table>

                    <?php }?>
                </div>
            </div>

            <!------------------------------ Calendar ------------------------------>
            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg mb-5" method="POST">

                <h4 class="pb-3 pt-2  " style="color: #11101D;"> Leave Calendar  <i id="file-details" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                <div id="calendar-container"  >

                    <div class="form-row" >

                        <?php
                        
                            $calendar = Utils::getEmployeeLeaveCalendar($data['employeeID']);
                            $leaveCalendarArr = array();
                                
                            while( $row = mysqli_fetch_assoc($calendar) ){

                                for( $i = 0; $i < round( $row['totalDays'] ) ; $i++ ){
                                    
                                    $date = date('Y-d-m' , strtotime("+{$i} days",  strtotime($row['startDate']) ) );
                                    $leaveCalendarArr[] = $date;
                                    
                                }
                                
                            }


                            $count = 0;

                            for ($i = -15; $i < 15; $i++) {

                                if( $i < 0  ){
                                    $currentDayDisplay = date("d", strtotime("{$i} days"));
                                    $currentDate = date("Y-d-m", strtotime("{$i} days"));
                                }else{
                                    $currentDayDisplay = date("d", strtotime("+{$i} days"));
                                    $currentDate = date("Y-d-m", strtotime("+{$i} days"));
                                }


                                $status = '';
                                
                                if( in_array( $currentDate , $leaveCalendarArr ) ){
                                    $status = 'leave-box-calendar';
                                    $count++;
                                }
                                
                                if( $currentDate == date("Y-d-m" ) ){
                                    $status = 'current-date-box-calendar';
                                }
 
                                if(  $currentDate  >= date( 'Y-d-m' , strtotime($data['startDate']) )  && $currentDate <= date( 'Y-d-m' , strtotime($data['endDate']) ) ){
                                    $status = 'apply-date-box-calendar';
                                }

                                echo " <div class='calendarDate ". $status ."' > $currentDayDisplay </div> ";
                                
                            }
                            
                            ?>


                    </div>

                    <div class='rules' > 

                        <div>
                            <div class='calendarDate leave-box-calendar ' > </div> <p>On Leave</p>
                            <div class='calendarDate apply-date-box-calendar ' > </div> <p>Applied dates</p>
                            <div class='calendarDate current-date-box-calendar ' > </div> <p>Current Day</p>
                        </div>


                    </div>;   

                </div>


            </div>


            <?php
            
                if( !empty($data['extension']) ){

                    echo 
                    "<div class='mb-5' >

                        <a href='../../pages/Staff/viewDetails.php?id=$data[extension]' > <button class='submitbtn approveBtn clickable my-0 mx-2' style=' background-color : #11101D; ' > <i class='fas fa-link mr-2' style=' color : #fff; ' ></i> Check the Linked Application </button> </a>
        
                    </div>";   

                }

            
            ?>


            <!-- Approve or Reject Button should only be visible when either user is hod and application has not elapsed -->
            <?php
            
                

                $curr = date('Y-m-d'); // Current date in 'Y-m-d' format
                
                if(  ( $user->role === Config::$_HOD_ ) && $data['applicationStatus'] == Config::$_APPLICATION_STATUS['PENDING'] ){

                    
                    if(( date( 'Y-m-d' , strtotime($data['startDate'])) >=  $curr)){


                        echo 
                        "<div>
    
                            <a href='../../pages/HOD/validateLeaveAction.php?id=$applicationId&action=APPROVE' > <button class='submitbtn approveBtn clickable my-0 mx-2' > <i class='fa-regular fa-circle-check mr-2'></i> Approve Request </button> </a>
    
                            <a href='../../pages/HOD/validateLeaveAction.php?id=$applicationId&action=REJECT' > <button class='submitbtn rejectBtn clickable my-0 mx-2' > <i class='fa-regular fa-circle-xmark mr-2'></i> Reject Request</button> </a>
            
                        </div>";
    

                    }else{

                        echo 
                        "<div>

                            <p> <b>Application Expired</b> as Start Date has Already been elasped ! </p>
            
                        </div>";


                    }




                }

                if( ( $user->role === Config::$_PRINCIPAL_ ) && $data['applicationStatus'] == Config::$_APPLICATION_STATUS['APPROVED_BY_HOD'] ){

                    if( ( date( 'Y-m-d' , strtotime($data['startDate'])) >=  $curr) ){

                    $extension = empty($data['extension']) ? false : true ;

                    echo 
                    "<div>

                        <a href='../../pages/Principal/validateLeaveAction.php?id=$applicationId&action=APPROVE&extension=$extension' > <button class='submitbtn approveBtn clickable my-0 mx-2' > <i class='fa-regular fa-circle-check mr-2'></i> Approve Request </button> </a>

                        
                        <a href='../../pages/Principal/validateLeaveAction.php?id=$applicationId&action=DEDUCTFROMEL&extension=$extension' > <button class='submitbtn approveBtn clickable my-0 mx-2' > <i class='fa-regular fa-circle-check mr-2'></i> Deduct from EL </button> </a>
                        
                        <a href='../../pages/Principal/validateLeaveAction.php?id=$applicationId&action=LWP&extension=$extension' > <button class='submitbtn approveBtn clickable my-0 mx-2' > <i class='fa-regular fa-circle-check mr-2'></i> Leave Without Pay </button> </a>

                        <a href='../../pages/Principal/validateLeaveAction.php?id=$applicationId&action=REJECT&extension=$extension' > <button class='submitbtn rejectBtn clickable my-0 mx-2' > <i class='fa-regular fa-circle-xmark mr-2'></i> Reject Request </button> </a>
        
                    </div>";

                }else{

                    echo 
                    "<div>

                        <p> <b>Application Expired</b> as Start Date has Already been elasped ! </p>
        
                    </div>";


                }



                }

            
            ?>

        </div>
        

    </section>

    <?php
    
        require('../../includes/model.php'); 
        
        
            // Check for response message from validateNewLeave.php
        if (isset($_SESSION['response_message'])) {
            $res = unserialize($_SESSION['response_message']);
            unset($_SESSION['response_message']); // Clear the message to prevent displaying it again

            if( $res[1] === "SUCCESS" ){   
                if( $user->role === Config::$_PRINCIPAL_ ) echo Utils::alert(htmlspecialchars($res[0]), htmlspecialchars($res[1]) , "../Principal/leave_request.php");
                if( $user->role === Config::$_HOD_ ) echo Utils::alert(htmlspecialchars($res[0]), htmlspecialchars($res[1]) , "../HOD/leave_request.php");
            }else{
                echo Utils::alert($res[0] , $res[1], "../Staff/viewDetails.php?id=$applicationId");
            }

    }

    ?>


    <script>
        // script for filter 
        $(document).ready(function() {

        // to hide container 
            $('#basic-info-container').hide()
            $('#leave-balance-container').hide()
            $('#app-detail-container').hide()
            $('#leave-adjustment-container').hide()
            $('#additional-approval-container').hide()
            $('#file-details-container').hide()

            // function to toggle basic information div
            $('#basic-info').click(() => {

                $('#basic-info-container').toggle();

            })

            // function to toggle leave balance div
            $('#leave-balance').click(() => {

                $('#leave-balance-container').toggle();

            })

            // function to toggle total days div
            $('#app-detail').click(() => {

                $('#app-detail-container').toggle();
            })

            // function to toggle adjustment details div
            $('#leave-adjustment').click(() => {

                $('#leave-adjustment-container').toggle();

            })

            // function to toggle Additinol approval details div
            $('#additional-approval').click(() => {

                $('#additional-approval-container').toggle();

            })

            // function to toggle file details div
            $('#file-details').click(() => {

                $('#file-details-container').toggle();
            })

        });
    </script>

</body>

</html>