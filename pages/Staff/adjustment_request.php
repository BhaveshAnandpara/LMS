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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <!-- all common links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/leaveHistory.css">
    <!-- all common Script -->
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables library to implement optimal search functinality ---- light weight library -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>

    <!-- style for collapse  -->
    <style>
        .collapse-btn {
            cursor: pointer;
        }

        .collapse-containt {
            display: block;
            margin-top: 10px;
        }

        .collapse-containt-privious {
            display: none;
            margin-top: 10px;
        }
        .collapse-btn-adjustment {
            cursor: pointer;
        }
        .collapse-containt-adjustment {
            display: block;
            margin-top: 10px;
        }
        .collapse-containt-privious-adjustment {
            display: none;
            margin-top: 10px;
        }
    </style>

</head>

<body>
    <!-- including navbar -->
    <?php
    include "../../includes/Staff/sidenavbar.php";
    ?>
    <!-- Write all code in section with class "home-section"  -->
    <section class="home-section">
        <!-- Including Header file -->
        <?php
        include "../../includes/header.php";
        ?>

        <!-- Div  for Lecture Adjustment Request -->
        <div class="content mt-3 rounded-lg dash_table " style="margin: auto;">
            <div class="container clg-12  bg-white rounded-lg historycontent " style="transition: all all 0.5s ease; border-right:6px solid #11101D">
                <div class="page-title p-4 collapse-btn"> <!--the class  collapse-btn are use for open or close div  -->
                    <h3> Lecture Adjustment Request </h3>
                </div>
                <!-- Table For pending request  -->
                <div class="box ml-2 box-primary collapse-containt "> <!--the class  collapse-content are use for hide div  -->
                    <div class="box-body">
                        <table width="100%" class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Applicant Email</th>
                                    <th>Application Date</th>
                                    <th>Adjustment Date</th>
                                    <th>Start Time </th>
                                    <th>End Time</th>
                                    <th>Semister</th>
                                    <th>Subject</th>
                                    <th>Action</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                // calling  lectureAdjustmentRequst function and store value in data variable
                                $data =  $user->lectureAdjustmentRequst();

                                while ($row = mysqli_fetch_assoc($data)) { ?>
                                    <tr>
                                        <!-- get employee details using findEmployeeDetailsUsingId and store in $employeedata -->
                                        <?php $employeedata = $user->findEmployeeDetailsUsingId($row['applicantID']);
                                        $employeeRow = mysqli_fetch_assoc($employeedata) ?>
                                        <td> <?php echo $employeeRow["fullName"] ?> </td>
                                        <td> <?php echo $employeeRow["email"] ?></td>
                                        <td><?php echo $row["dateTime"]; ?></td>
                                        <td><?php echo $row["date"]; ?></td>
                                        <td><?php echo $row["startTime"]; ?></td>
                                        <td><?php echo $row["endTime"]; ?></td>
                                        <td><?php echo $row["semester"]; ?></td>
                                        <td><?php echo $row["subject"]; ?></td>
                                        <td class="text-end center">
                                            <a href="../../utils/Staff/AdjustmentApproval.php?id=<?php echo $row["lecAdjustmentID"] ?>&flag=<?php echo 0 ?>" class=" center btn btn-outline-success btn-rounded">Accept</a>
                                            <a href="../../utils/Staff/AdjustmentApproval.php?id=<?php echo $row["lecAdjustmentID"] ?>&flag=<?php echo 1 ?>" class=" center btn btn-outline-danger btn-rounded">Reject</a>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- below div for Previous Request button  -->
                <div class="text-center mt-1 p-2">
                    <button class="btn btn-primary collapse-btn-previous">Previous Request</button>
                </div>
                <!-- below div shows Previous Request in table form  -->
                <div class="box ml-2 box-primary collapse-containt-privious "> <!--the class  collapse-content are use for hide div  -->
                    <div class="box-body">
                        <table width="100%" class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Applicant Email</th>
                                    <th>Application Date</th>
                                    <th>Adjustment Date</th>
                                    <th>Start Time </th>
                                    <th>End Time</th>
                                    <th>Semister</th>
                                    <th>Subject</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // calling  PreviouslectureAdjustmentRequst function and store value in data variable

                                $data =  $user->PreviouslectureAdjustmentRequst();
                                while ($row = mysqli_fetch_assoc($data)) {
                                ?>
                                    <tr>
                                        <!-- get employee details using findEmployeeDetailsUsingId and store in $employeedata -->

                                        <?php $employeedata = $user->findEmployeeDetailsUsingId($row['applicantID']);
                                        $employeeRow = mysqli_fetch_assoc($employeedata) ?>
                                        <td> <?php echo $employeeRow["fullName"] ?> </td>
                                        <td> <?php echo $employeeRow["email"] ?></td>
                                        <td><?php echo $row["dateTime"]; ?></td>
                                        <td><?php echo $row["date"]; ?></td>
                                        <td><?php echo $row["startTime"]; ?></td>
                                        <td><?php echo $row["endTime"]; ?></td>
                                        <td><?php echo $row["semester"]; ?></td>
                                        <td><?php echo $row["subject"]; ?></td>
                                        <td class="text-end center">
                                            <?php if ($row["status"] == "REJECT") { ?>
                                                <button class=" center btn btn-danger btn-rounded">Rejected</button>
                                            <?php } else { ?>
                                                <button class=" center btn btn-success btn-rounded">Accepted</button>
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

        <!-- Div  for Task  Adjustment Request -->
        <div class="content mt-3 rounded-lg dash_table " style="margin: auto;">
            <div class="container clg-12  bg-white rounded-lg historycontent " style="transition: all all 0.5s ease; border-right:6px solid #11101D">
                <div class="page-title p-4 collapse-btn-adjustment"> <!--the class  collapse-btn are use for open or close div  -->
                    <h3> Task Adjustment Request </h3>
                </div>
                <!-- Table For pending request  -->
                <div class="box ml-2 box-primary collapse-containt-adjustment "> <!--the class  collapse-content are use for hide div  -->
                    <div class="box-body">
                        <table width="100%" class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Applicant Email</th>
                                    <th>Application Date</th>
                                    <th>Start Date</th>
                                    <th>End Time </th>
                                    <th>Task</th>
                                    <th>Action</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                // calling  taskAdjustmentRequst function and store value in data variable
                                $data =  $user->taskAdjustmentRequst();

                                while ($row = mysqli_fetch_assoc($data)) { ?>
                                    <tr>
                                        <!-- get employee details using findEmployeeDetailsUsingId and store in $employeedata -->
                                        <?php $employeedata = $user->findEmployeeDetailsUsingId($row['applicantID']);
                                        $employeeRow = mysqli_fetch_assoc($employeedata) ?>
                                        <td> <?php echo $employeeRow["fullName"] ?> </td>
                                        <td> <?php echo $employeeRow["email"] ?></td>
                                        <td><?php echo $row["dateTime"]; ?></td>
                                        <td><?php echo $row["startDate"]; ?></td>
                                        <td><?php echo $row["endDate"]; ?></td>
                                        <td><?php echo $row["task"]; ?></td>
                                        <td class="text-end center">
                                            <a href="../../utils/Staff/AdjustmentApproval.php?id=<?php echo $row["taskAdjustmentID"] ?>&flag=<?php echo 2 ?>" class=" center btn btn-outline-success btn-rounded">Accept</a>
                                            <a href="../../utils/Staff/AdjustmentApproval.php?id=<?php echo $row["taskAdjustmentID"] ?>&flag=<?php echo 3 ?>" class=" center btn btn-outline-danger btn-rounded">Reject</a>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- below div for Previous Request button  -->
                <div class="text-center mt-1 p-2">
                    <button class="btn btn-primary collapse-btn-previous-adjustment">Previous Request</button>
                </div>
                <!-- below div shows Previous Request in table form  -->
                <div class="box ml-2 box-primary collapse-containt-privious-adjustment "> <!--the class  collapse-content are use for hide div  -->
                    <div class="box-body">
                        <table width="100%" class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Applicant Email</th>
                                    <th>Application Date</th>
                                    <th>Start Date</th>
                                    <th>End Time </th>
                                    <th>Task</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // calling  PreviousTaskAdjustmentRequst function and store value in data variable

                                $data =  $user->PreviousTaskAdjustmentRequst();
                                while ($row = mysqli_fetch_assoc($data)) {
                                ?>
                                    <tr>
                                        <!-- get employee details using findEmployeeDetailsUsingId and store in $employeedata -->

                                        <?php $employeedata = $user->findEmployeeDetailsUsingId($row['applicantID']);
                                        $employeeRow = mysqli_fetch_assoc($employeedata) ?>
                                        <td> <?php echo $employeeRow["fullName"] ?> </td>
                                        <td> <?php echo $employeeRow["email"] ?></td>
                                        <td><?php echo $row["dateTime"]; ?></td>
                                        <td><?php echo $row["startDate"]; ?></td>
                                        <td><?php echo $row["endDate"]; ?></td>
                                        <td><?php echo $row["task"]; ?></td>
                                        
                                        <td class="text-end center">
                                            <?php if ($row["status"] == "REJECT") { ?>
                                                <button class=" center btn btn-danger btn-rounded">Rejected</button>
                                            <?php } else { ?>
                                                <button class=" center btn btn-success btn-rounded">Accepted</button>
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

        <script>
            // script for search functionality 
            $(document).ready(function() {
                // Initialize DataTable
                var table = $('#dataTables-example').DataTable();

                // Add event listener for the search input
                $('#searchInput').on('keyup', function() {
                    table.search(this.value).draw();
                });
            });

            // script for toggle privious lecture adjustment div when click on previous Adjustment Button
            $(document).ready(function() {
                $('.collapse-btn').click(function() {
                    $('.collapse-containt').slideToggle();
                });
                $('.collapse-btn-previous').click(function() {
                    $('.collapse-containt-privious').slideToggle();
                });
            });

            // script for toggle privious task adjustment div when click on previous Adjustment Button
            $(document).ready(function() {
                $('.collapse-btn-adjustment').click(function() {
                    $('.collapse-containt-adjustment').slideToggle();
                });
                $('.collapse-btn-previous-adjustment').click(function() {
                    $('.collapse-containt-privious-adjustment').slideToggle();
                });
            });
        </script>
</body>

</html>