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
$reason = $_GET['reason'];;  // Declare the variable 
$data =  $user->viewDetailApplication($applicationId);
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
    include "../../includes/Staff/sidenavbar.php";
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
                            <h6 class="mb-0 d-inline ">Employee ID</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <!-- <?php echo $empDetails['employeeID'] ?> -->
                            <?php echo $user->email  ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Employee Name</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $user->fullName ?>
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
                            <h6 class="mb-0 d-inline ">Leave Reason</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php echo $reason ?>
                        </div>
                    </div>
                </div>
            </div>

            <!------------------------------ Leave Types ------------------------------>
            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl ?>' method="POST">
                <h4 class="pb-3 pt-2  " style="color: #11101D;">Leave Types <i id="leave-balance" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>
                <div class="form-row" id="leave-balance-container">
                    <table class="tablecontent" id="leave-balance-table">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>Day Type</th>
                                <th>End Date</th>
                                <th>Day Type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <?php
                           // to get a application details of perticuler application
                            while ($row = mysqli_fetch_assoc($data)) {
                                echo "<tr>";
                                echo "<td  >" . $row['leaveType'] . "</td>";
                                echo "<td  >" . $row['startDate'] . "</td>";
                                echo "<td  >" . $row['startDateType'] . "</td>";
                                echo "<td  >" . $row['endDate'] . " </td>";

                                echo "<td  >" . $row['endDateType']  . " </td>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!------------------------------ Leave Totals Days ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" method="POST">
                <h4 class="pb-3 pt-2  " style="color: #11101D;">total Days <i id="leave-total" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>
                <div class="form-row" id="leave-total-container">
                        <?php
                          $total = 0;
                        $data =  $user->viewDetailApplication($applicationId);
                        while ($row = mysqli_fetch_assoc($data)) { 
                          
                            ?>
                        <p class="form-control border-0  h-100 bg-white mb-0" id="totalDays" name="totalDays">
                            <?php echo "Total Leaves  deducted from " . $row['leaveType'] . "  :  " . $row['totalDays']; ?>
                            </p> <?php $total =number_format($total) + number_format($row['totalDays']); } ?> 
                            <p class="form-control border-0  h-100 bg-white mb-0" id="totalDays" name="totalDays">
                            <?php echo "Overall Leaves deducted from Your Account <strong>" . number_format($total) . "</strong>"; ?>
                            </p>
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
                                $data =  $user->getlectureAdjustment($applicationId);
                                while ($row = mysqli_fetch_assoc($data)) {
                                    // to get name of adjusted employee 
                                    $userData =  $user->findEmployeeDetailsUsingId($row['adjustedWith']);
                                    $rowemp = mysqli_fetch_assoc($userData);
                                    echo "<tr>";
                                    echo "<td  >" . $rowemp['fullName'] . "</td>";
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
                                 // to get task adjustment details 
                                $data =  $user->getTaskAdjustment($applicationId);
                                while ($row = mysqli_fetch_assoc($data)) {
                                    // to get name of task adjusted employee 
                                    $userData =  $user->findEmployeeDetailsUsingId($row['adjustedWith']);
                                    $rowemp = mysqli_fetch_assoc($userData);
                                    echo "<tr>";
                                    echo "<td  >" . $rowemp['fullName'] . "</td>";
                                    echo "<td  >" . $row['startDate'] . "</td>";
                                    echo "<td  >" . $row['endDate'] . " </td>";
                                    echo "<td  >" . $row['task'] . " </td>";
                                    if ($row['status'] == "ACCEPTED") {
                                        echo "<td><p class='text-success'>" . $row['status'] . "</p></td>";
                                    } elseif ($row['status'] == "REJECTED") {
                                        echo "<td>" . $row['status'] . "</td>";
                                    } else {
                                        echo "<td>" . $row['status'] . "</td>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!------------------------------ Aditional Approval details ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" method="POST">
                <h4 class="pb-3 pt-2  " style="color: #11101D;">Aditional Approval <i id="additional-approval" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>
                <div class="form-row" id="additional-approval-container">
                    <table class="tablecontent">
                        <thead>
                            <tr>
                                <th>Approver Details</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <?php
                            // to get additional informatin details 
                            $data =  $user->getAdditioinalApproval($applicationId);
                            while ($row = mysqli_fetch_assoc($data)) {
                                // to get employee details
                                $userData =  $user->findEmployeeDetailsUsingId($row['approverId']);
                                $rowemp = mysqli_fetch_assoc($userData);
                                echo "<tr>";
                                echo "<td  >" . $rowemp['fullName'] . "</td>";

                                echo "<td  >" . $row['status'] . " </td>";
                            }  ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!------------------------------ file details ------------------------------>
            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" method="POST">
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
                                <td> medical recipt </td>
                                <td>
                                <a href="<?php echo $row['file']; ?>" class="btn btn-success">View Document</a>
                                </td>
                                <?php  } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        

    </section>

    <script>
        // script for filter 
        $(document).ready(function() {
        // to hide container 
            $('#basic-info-container').hide()
            $('#leave-balance-container').hide()
            $('#leave-total-container').hide()
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
            $('#leave-total').click(() => {

                $('#leave-total-container').toggle();
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

            leave - balance

        });
    </script>

</body>

</html>