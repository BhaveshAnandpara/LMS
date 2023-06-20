<!-- Include this to use User object -->
<?php
//  Creates database connection 
require "../../includes/db.conn.php";
//include class definition
require('../../utils/Staff/Staff.class.php');

//include Config Class
require('../../utils/Config/Config.class.php');
require('../../utils/Utils.class.php');
//start session
session_start();
//Get the User Object
$user =  $_SESSION['user'];
$applicationID = $_GET['applicationID'];
$employeeID = $_GET['employeeID'];


?>

<!DOCTYPE html>
<?php error_reporting(0); ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <!-- all common links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/adjustmentPages.css">
    <link rel="stylesheet" href="../../css/leaveHistory.css">

    <!-- all common Script -->
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/collapseDiv.js"></script>
</head>

<body>
    <?php

    include "../../includes/Staff/sidenavbar.php";
    ?>
    <section class="home-section">
        <?php
        include "../../includes/header.php"
        ?>
        <div class="container mt-2 mb-1 bg-white p-2 ">
            <?php $employeedata = $user->findEmployeeDetailsUsingId($employeeID);
            $employeeRow = mysqli_fetch_assoc($employeedata);

            $employeeDeprtment = $user->getDepartmet($employeeRow['deptID']);
            $employeeDeprtmentRow = mysqli_fetch_assoc($employeeDeprtment);
            ?>
            <!-- Div for applicant details  -->
            <h5 class=" m-3 ">Applicant Details</h5>
            <div class="box-body">
                <table width="100%" class="table table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Name </th>
                            <th>Department</th>
                            <th>Role </th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // calling  findEmployeeDetailsUsingId function and store value in data variable
                        $employeedata = $user->findEmployeeDetailsUsingId($employeeID);
                        $employeeRow = mysqli_fetch_assoc($employeedata);
                        // to find department 
                        $employeeDeprtment = $user->getDepartmet($employeeRow['deptID']);
                        $employeeDeprtmentRow = mysqli_fetch_assoc($employeeDeprtment); ?>
                        <tr>
                            <!-- get LeaveTypedata details using findLeaveTypeUsingId function and store in $LeaveTypedata -->
                            <td> <?php echo $employeeRow['fullName'] ?> </td>
                            <td><?php echo $employeeDeprtmentRow["deptName"]; ?></td>
                            <td><?php echo $employeeRow['role'] ?></td>
                            <!-- to get last updated date using getCurrentBalance and store in $leaveTransactionData -->
                            <td><?php if ($employeeRow['status'] == 'ACTIVE') { ?> <span class="text-success">Active </span> <?php } else { ?> <span class="text-danger">Inactive </span> <?php } ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- div for  Application Details -->
            <div class="container mt-2 mb-2  bg-white">
                <h5> Application Details </h5>
                <!-- <input type="text" id="searchInput" placeholder="Search..."> -->
                <div class="box ml-2 mt-3 historycontent">
                    <div class="box-body">
                        <table width="100%" class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <!-- <th>Leave Type</th> -->
                                    <th>Application Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Total Days</th>
                                    <th>Reason</th>
                                    
                                    <th>Hod Approval</th>
                                    <th>Principal Approval</th>
                                    <th>Leave Status</th>


                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $data =  $user->applicationDetailsUsingId($applicationID);

                                $row = mysqli_fetch_assoc($data) ?>
                                <tr>


                                    <td><?php echo $row["dateTime"]; ?></td>
                                    <td><?php echo $row["startDate"]; ?></td>
                                    <td><?php echo $row["endDate"]; ?></td>
                                    <td><?php echo $row["totalDays"]; ?></td>
                                    <td><?php echo $row["reason"]; ?></td>
                                    <td><?php echo $row["hodApproval"]; ?></td>
                                    <td><?php echo $row["principalApproval"]; ?></td>
                                    <td><?php echo $row["status"]; ?></td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!--  div for leave type and courrent balance  -->
        <div class="container mt-2 mb-1 bg-white  ">
            <div class="collapse-btn-leaveTypeandBalance m-3  mt-3">
                <h5>Leave Type And Balance</h5>
                <div class="box ml-2 mt-3  collapse-containt-leaveTypeandBalance "> <!--the class  collapse-content are use for hide div  -->
                    <!-- div for leave type and courrent balance -->
                    <div class="box-body historycontent">
                        <table width="100%" class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>LeaveType</th>
                                    <th>Balance</th>
                                    <th>Leave Count</th>
                                    <th> Last Update </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // calling  getLeaveTypeBalance function and store value in data variable
                                $data =  $user->getLeaveTypeBalance($employeeID);
                                while ($row = mysqli_fetch_assoc($data)) { ?>
                                    <tr>
                                        <!-- get LeaveTypedata details using findLeaveTypeUsingId function and store in $LeaveTypedata -->
                                        <?php $LeaveTypedata = $user->findLeaveTypeUsingId($row['leaveID']);
                                        $LeaveTypeRow = mysqli_fetch_assoc($LeaveTypedata) ?>
                                        <td> <?php echo $LeaveTypeRow["leaveType"] ?> </td>
                                        <td><?php echo $row["balance"]; ?></td>
                                        <td><?php echo $row["leaveCounter"]; ?></td>
                                        <!-- to get last updated date using getCurrentBalance and store in $leaveTransactionData -->
                                        <?php $leaveTransactionData = $user->getCurrentBalance();
                                        $leaveTransactionRow = mysqli_fetch_assoc($leaveTransactionData) ?>
                                        <td><?php echo $leaveTransactionRow["date"]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- div for Leave Type That You Had Taken -->
        <div class="container mt-1  mb-2 bg-white  ">
            <div class="collapse-btn-leaveType m-3 mt-4"> <!--  collapse-btn-leaveType class for collapse divmon click-->
                <h5>Leave Type That You Had Taken</h5>
                <div class="m-3  mt-3">
                    <div class="box ml-2  collapse-containt-leaveType "> <!--the class  collapse-content-leaveType are use for hide div  -->
                        <!-- div for table  -->
                        <div class="box-body historycontent">
                            <table width="100%" class="table table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>LeaveType</th>
                                        <th>Start Date</th>
                                        <th>Start Date Type</th>
                                        <th>End Date </th>
                                        <th>End Date Type</th>
                                        <th>Total Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // calling  leaveTypeApplied function and returen data of leave type that user appply and store in data variable
                                    $data =  $user->leaveTypeApplied($applicationID);
                                    while ($row = mysqli_fetch_assoc($data)) { ?>
                                        <tr>
                                            <!--to get leave type details using function findLeaveTypeUsingId and store in $LeaveTypedata -->
                                            <?php $LeaveTypedata = $user->findLeaveTypeUsingId($row['leaveID']);
                                            $LeaveTypeRow = mysqli_fetch_assoc($LeaveTypedata) ?>
                                            <td> <?php echo $LeaveTypeRow["leaveType"] ?> </td>
                                            <td><?php echo $row["startDate"]; ?></td>
                                            <td><?php echo $row["startDateType"]; ?></td>
                                            <td><?php echo $row["endDate"]; ?></td>
                                            <td><?php echo $row["endDateType"]; ?></td>
                                            <td><?php echo $row["totalDays"]; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-1 mb-2 bg-white  ">
            <div class="collapse-btn m-3 mt-4"><!--the class  collapse-btn are use for collasable  div  -->
                <h5>Lecture Adjustment</h5>
                <div class="m-3n  mt-3">
                    <div class="box ml-2  collapse-containt "> <!--the class  collapse-content are use for hide div  -->
                        <!-- div for table -->
                        <div class="box-body historycontent">
                            <table width="100%" class="table table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Adjusted With</th>
                                        <th>Email</th>
                                        <th>Adjustment Date</th>
                                        <th>Start Time </th>
                                        <th>End Time</th>
                                        <th>Semister</th>
                                        <th>Subject</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // calling  lectureAdjustmentData function and get adjsutment data of perticuler application and store value in data variable
                                    $data =  $user->lectureAdjustmentData($applicationID);
                                    while ($row = mysqli_fetch_assoc($data)) { ?>
                                        <tr>
                                            <!-- get employee details using findEmployeeDetailsUsingId and store in $employeedata -->
                                            <?php $employeedata = $user->findEmployeeDetailsUsingId($row['adjustedWith']);
                                            $employeeRow = mysqli_fetch_assoc($employeedata) ?>

                                            <td> <?php echo $employeeRow["fullName"] ?> </td>
                                            <td> <?php echo $employeeRow["email"] ?></td>
                                            <td><?php echo $row["date"]; ?></td>
                                            <td><?php echo $row["startTime"]; ?></td>
                                            <td><?php echo $row["endTime"]; ?></td>
                                            <td><?php echo $row["semester"]; ?></td>
                                            <td><?php echo $row["subject"]; ?></td>
                                            <td class="text-end center">
                                        <?php if ($row["status"] == "REJECT") { ?>
                                                <p class=" center text-danger btn-rounded " style="font-weight: bold;">Rejected</p>
                                            <?php } else if($row["status"] == "ACCEPT") { ?>
                                                <p class=" center text-success btn-rounded bold " style="font-weight: bold;">Accepted</p>
                                            <?php } else{ ?>
                                                <p class=" center text-info btn-rounded bold " style="font-weight: bold;">Withdrown</p>
                                            <?php } ?> 
                                        </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-1  mb-2 bg-white  ">
            <div class="collapse-btn-adjustment m-3 mt-4">
                <h5>Task Adjustment</h5>
                <div class="m-3  mt-3">
                    <div class="box ml-2  collapse-containt-adjustment "> <!--the class  collapse-content are use for hide div  -->
                        <div class="box-body historycontent">
                            <table width="100%" class="table table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Adjusted With</th>
                                        <th>Email</th>
                                        <th>Adjustment Date</th>
                                        <th>Start Time </th>
                                        <th>End Time</th>
                                        <th>Semister</th>
                                        <th>Subject</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // calling  lectureAdjustmentData function for perticuler leave application and store value in data variable
                                    $data =  $user->lectureAdjustmentData($applicationID);

                                    while ($row = mysqli_fetch_assoc($data)) { ?>
                                        <tr>
                                            <!-- get employee details using findEmployeeDetailsUsingId and store in $employeedata -->
                                            <?php $employeedata = $user->findEmployeeDetailsUsingId($row['adjustedWith']);
                                            $employeeRow = mysqli_fetch_assoc($employeedata) ?>
                                            <td> <?php echo $employeeRow["fullName"] ?> </td>
                                            <td> <?php echo $employeeRow["email"] ?></td>
                                            <td><?php echo $row["date"]; ?></td>
                                            <td><?php echo $row["startTime"]; ?></td>
                                            <td><?php echo $row["endTime"]; ?></td>
                                            <td><?php echo $row["semester"]; ?></td>
                                            <td><?php echo $row["subject"]; ?></td>
                                            <td class="text-end center">
                                        <?php if ($row["status"] == "REJECT") { ?>
                                                <p class=" center text-danger btn-rounded " style="font-weight: bold;">Rejected</p>
                                            <?php } else if($row["status"] == "ACCEPT") { ?>
                                                <p class=" center text-success btn-rounded bold " style="font-weight: bold;">Accepted</p>
                                            <?php } else{ ?>
                                                <p class=" center text-info btn-rounded bold " style="font-weight: bold;">Withdrown</p>
                                            <?php } ?> 
                                        </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




</body>

</html>