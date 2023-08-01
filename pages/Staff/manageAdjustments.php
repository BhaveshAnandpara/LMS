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
    <link rel="stylesheet" href="../../css/Admin/manageMasterData.css?v=<?php echo time(); ?>">
    <!-- <link rel="stylesheet" href="../../css/leaveHistory.css"> -->


    <!-- all common Script  -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>

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

        <!--Including header -->
        <?php
            include "../../includes/header.php";
        ?>

        <div class="container">

            <!------------------------------ Lecture Adjustments ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">

                <h4 class="pb-3 pt-2  " style="color: #11101D;"> Lecture Adjustments Requests<i id="lec-adj"
                        class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                        <div class="form-row" id="lec-adj-container">

                            <table class="tablecontent" id="lec-adj-table">


                                <thead>
                                    <tr>
                                        <th>APPLICANT NAME</th>
                                        <th>APPLICANT EMAIL</th>
                                        <th>START TIME</th>
                                        <th>END TIME</th>
                                        <th>SEMESTER</th>
                                        <th>SUBJECT</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="tbody">

                                    <?php

                                        $lecAdjData =  $user->lectureAdjustmentRequst(); 
                                        while($row =  mysqli_fetch_assoc($lecAdjData) ){
                                        
                                            echo "<tr>";
                                            echo "<td  >" . $row['fullName'] . "</td>";
                                            echo "<td  >" . $row['email'] . "</td>";
                                            echo "<td  >" . $row['startTime'] . "</td>";
                                            echo "<td  >" . $row['endTime'] . " </td>";
                                            echo "<td  >" . $row['semester']  . " </td>";
                                            echo "<td  >" . $row['subject']  . " </td>";
                                            echo "<td  > <a href='../../pages/'> <button class='submitbtn clickable my-0 mx-2' > Accept </button> </a></td>";
                                            echo "<td  > <a href='../../pages/'> <button class='submitbtn clickable my-0 mx-2' > Reject </button> </a></td>";
                                        }
                                    
                                    ?>
                                </tbody>
                            </table>
                                    
                                    
                            </div>


            </div>

            <!------------------------------ Task Adjustments ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">

                <h4 class="pb-3 pt-2  " style="color: #11101D;"> Task Adjustments Requests<i id="task-adj"
                        class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                        <div class="form-row" id="task-adj-container">

                            <table class="tablecontent" id="task-adj-table">


                                <thead>
                                    <tr>
                                        <th>APPLICANT NAME</th>
                                        <th>APPLICANT EMAIL</th>
                                        <th>START DATE</th>
                                        <th>END DATE</th>
                                        <th>TASK</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="tbody">

                                    <?php

                                        $lecAdjData =  $user->taskAdjustmentRequst(); 
                                        while($row =  mysqli_fetch_assoc($lecAdjData) ){
                                        
                                            echo "<tr>";
                                            echo "<td  >" . $row['fullName'] . "</td>";
                                            echo "<td  >" . $row['email'] . "</td>";
                                            echo "<td  >" . $row['startDate'] . "</td>";
                                            echo "<td  >" . $row['endDate'] . " </td>";
                                            echo "<td  >" . $row['task']  . " </td>";
                                            echo "<td  > <a href='../../pages/'> <button class='submitbtn clickable my-0 mx-2' > Accept </button> </a></td>";
                                            echo "<td  > <a href='../../pages/'> <button class='submitbtn clickable my-0 mx-2' > Reject </button> </a></td>";
                                        }
                                    
                                    ?>
                                </tbody>
                            </table>
                                    
                                    
                            </div>


            </div>


        </div>


    </section>


        <script>
            // script for search functionality 
            $(document).ready(function() {


                $('#lec-adj-container').hide()
                $('#task-adj-container').hide()


                $('#lec-adj').click(() => {
                
                    $('#lec-adj-container').toggle();
                
                })
            
                $('#task-adj').click(() => {
                
                    $('#task-adj-container').toggle();
                
                })

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