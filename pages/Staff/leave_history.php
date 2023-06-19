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
    <!-- all common Script -->
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <!--CSS LINK -->
    <link rel="stylesheet" href="../../css/leaveHistory.css">

    <!-- DataTables library to implement optimal search functinality ---- light weight library -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
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

        <!-- Table  for Leave History -->
        <div class="content mt-3 rounded-lg dash_table " style="margin: auto;">
            <div class="container clg-12  bg-white rounded-lg historycontent " style="transition: all all 0.5s ease; border-right:6px solid #11101D">
                <div class="page-title p-4">
                    <h3> Leave Application </h3>
                </div>
                <!-- <input type="text" id="searchInput" placeholder="Search..."> -->
                <div class="box ml-2 box-primary">
                    <div class="box-body">
                        <table width="100%" class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Application Date</th>
                                    <th>Adjust With</th>
                                    <th>Subject</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Total Days</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                    <th>Hod Approval</th>
                                    <th>Principal Approval</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $data =  $user->appliedLeaveHistory();

                                while ($row = mysqli_fetch_assoc($data)) { ?>
                                    <tr>
                                        <?php $employeedata = $user->findEmployeeDetailsUsingId($row['applicantID']);
                                        $employeeRow = mysqli_fetch_assoc($employeedata) ?>
                                        <td> <?php echo $row["leaveType"]; ?></td>
                                        <td><?php echo $row["dateTime"]; ?></td>
                                        <td> <?php echo $employeeRow["fullName"] ?></td>
                                        <td><?php echo $row["subject"]; ?></td>
                                        <td><?php echo $row["startDate"]; ?></td>
                                        <td><?php echo $row["endDate"]; ?></td>
                                        <td><?php echo $row["totalDays"]; ?></td>
                                        <td><?php echo $row["reason"]; ?></td>
                                        <td class="text-end">
                                            <a href="" class="btn btn-outline-info btn-rounded"><i class="fas fa-pen"></i></a>
                                            <a href="" class="btn btn-outline-danger btn-rounded"><i class="fas fa-trash"></i></a>
                                        </td>
                                        <td><?php echo $row["hodApproval"]; ?></td>
                                        <td><?php echo $row["principalApproval"]; ?></td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // script for filter 
            $(document).ready(function() {
                // Initialize DataTable
                var table = $('#dataTables-example').DataTable();

                // Add event listener for the search input
                $('#searchInput').on('keyup', function() {
                    table.search(this.value).draw();
                });
            });
        </script>
</body>

</html>