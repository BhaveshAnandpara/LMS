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
        <!-- Table  for  Lecture Adjustment -->
        <div class="content mt-3 rounded-lg dash_table" style="margin: auto;">
            <div class="container clg-12  bg-white rounded-lg  " style="transition: all all 0.5s ease; border-right:6px solid #11101D">
                <div class="page-title p-4">
                    <h3> Lecture Adjustment Request </h3>
                </div>
                <div class="box box-primary">
                    <div class="box-body">
                        <table width="100%" class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Application Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Sem</th>
                                    <th>Lecture Name</th>
                                    <th>Start Time </th>
                                    <th>End Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>Ganesh Golhar</td>
                                    <td>29/06/2023</td>
                                    <td>30/06/2023</td>
                                    <td>31/06/2023</td>
                                    <td>6</td>
                                    <td>cd</td>
                                    <td>12:00</td>
                                    <td>01:00</td>
                                    <td class="text-end">
                                        <button class="btn btn-success">Approve</button>
                                        <button class="btn btn-danger">Reject</button>
                                    </td>
                                </tr>
                            </tbody>
                            <!-- for  Approve button  -->
                            <!-- write code Here -->

                            <!-- for  Reject button  -->
                            <!-- write code Here -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>