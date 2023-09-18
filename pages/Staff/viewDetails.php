<?php
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>

<!-- Include this to use User object -->
<?php
    //include class definition
    require('../../utils/Staff/Staff.class.php');
    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');
    //start session
    session_start();
    //Get the User Object
    $user =  $_SESSION['user'];
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
                            <span class=<?php echo $data['status'] ?> >  <?php echo $data['status'] ?> </span>
                        </div>
                    </div>

                    <!-- //Approver table -->
                    <div class="form-group col-md-12">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-3 d-inline ">Additional Approvals</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            
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

                                $additionalApp =  $user->getApprovalRequst($applicationId); 

                                while($row =  mysqli_fetch_assoc($additionalApp) ){

                                    $approverInfo = mysqli_fetch_assoc (Utils::getEmpDetails( $row['approverId'] ) );

                                    echo "<tr>";
                                    echo "<td  >" . $row['approverId'] . "</td>";
                                    echo "<td  >" . $approverInfo['email'] . "</td>";
                                    echo "<td  >" . $approverInfo['fullName'] . "</td>";
                                    echo "<td  >" . $approverInfo['deptName'] . "</td>";
                                    echo "<td  >" . $approverInfo['role'] . " </td>";
                                    echo "<td  >" . $row['status'] . " </td>";
                                }
                            
                            ?>
                        </tbody>
                    </table>


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
                                    echo "<td  >" . $row['status']  . " </td>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Task Adjustment Details  -->

                    <div class="form-row mt-2">

                        <h5 class="pb-3 pt-2 mt-2 " style="color: #11101D;">Task Adjustment Details </h5>

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

                                $lecData =  $user->getTaskAdjustments($applicationId);

                                while ($row = mysqli_fetch_assoc($lecData)) {

                                    echo "<tr>";
                                    echo "<td  >" . $row['fullName'] . "</td>";
                                    echo "<td  >" . $row['startDate'] . "</td>";
                                    echo "<td  >" . $row['endDate'] . " </td>";
                                    echo "<td  >" . $row['task']  . " </td>";
                                    echo "<td  >" . $row['status']  . " </td>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!------------------------------ file details ------------------------------>
            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg mb-5" method="POST">
                <h4 class="pb-3 pt-2  " style="color: #11101D;">Uploaded Documents <i id="file-details" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>
                <div class="form-row" id="file-details-container">
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
                            $data =  $user->getFileDetails($applicationId);
                            while ($row = mysqli_fetch_assoc($data)) { ?>
                            <tr>
                                <td> <?php echo  substr( $row['file'] , 18 , strlen($row['file']) )  ?> </td>
                                <td>
                                <a href="<?php echo $row['file']; ?>" class="btn btn-success">View Document</a>
                                </td>
                                <?php  } ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <?php
            
                if(  $user->role === Config::$_HOD_ ){
                    
                    echo 
                    "<div>

                        <a href='../../pages/HOD/validateLeaveAction.php?id=$applicationId&action=APPROVE' > <button class='submitbtn approveBtn clickable my-0 mx-2' > Approve </button> </a>

                        <a href='../../pages/HOD/validateLeaveAction.php?id=$applicationId&action=REJECT' > <button class='submitbtn rejectBtn clickable my-0 mx-2' > Reject </button> </a>
        
                    </div>";


                }

            
            ?>



        </div>
        

    </section>

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