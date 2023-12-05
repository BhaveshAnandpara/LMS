<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
    
        //include class definition
    require('../../utils/Admin/admin.class.php');

    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');

    //start session
    session_start();
?>



<!-- Include this to use User object -->
<?php



    //Get the User Object
    $user =  $_SESSION['user'];

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- all common Script  -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>

</head>

<body>


    <!--Including sidenavbar -->
    <?php
     include "../../includes/Admin/sidenavbar.php";
    ?>


    <section class="home-section">

        <!--Including header -->
        <?php
            include "../../includes/header.php";
        ?>


        <div class="container">

            <div class=" table-container bg-white rounded-lg shadow-lg mt-3">

                <div class=" row p-5 rounded-lg shadow-lg d-flex justify-content-sm-start flex-column "
                    style="transition: all all 0.5s ease; border-right:6px solid #11101D">


                    <div class="col-md-12 col-sm-12 py-3">
                        <h3> Manage Holidays </h3>
                    </div>

                    <a href="../../pages/Admin/addHoliday.php" class="my-3"><button class="AddBtn"> + </button></a>

                    <table class="tablecontent mb-4 ">

                        <thead>
                            <tr>
                                <th>HOLIDAY DATE</th>
                                <th>HOLIDAY NAME</th>
                                <th>EDIT</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                            <?php

                            $holidays = Utils::getUpcomingHolidays(); // Returns Array of Tuples in Database

                            while($row = mysqli_fetch_assoc( $holidays) ){

                                echo "<tr>";
                                echo "<td  >" . date( 'd-m-Y' , strtotime($row['date']) ) . "</td>";
                                echo "<td  >" . $row['holidayName'] . "</td>";
                                echo "<td><a href='../../pages/Admin/editHoliday.php?date=$row[date]&name=$row[holidayName]'><i class='fa-solid fa-pen-to-square edit'></i></a></td>";
                                echo "</tr>";
                            }

                        ?>
                        </tbody>
                    </table>

                    <!-- Elapsed Holidays -->

                    <p id="filter-btn">Elapsed Holidays <i class="fa-solid fa-caret-down"></i> </p>

                    <table class="tablecontent" id="elapsed-holidays-table">

                        <thead>
                            <tr>
                                <th>HOLIDAY DATE</th>
                                <th>HOLIDAY NAME</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                            <?php

                                $holidays = Utils::getElapsedHolidays(); // Returns Array of Tuples in Database

                                while($row = mysqli_fetch_assoc( $holidays) ){
                                
                                    echo "<tr>";
                                    echo "<td  >" . date( 'd-m-Y' , strtotime($row['date']) ) . "</td>";
                                    echo "<td  >" . $row['holidayName'] . "</td>";
                                    echo "</tr>";
                                }
                            
                            ?>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>


    </section>

    <script>
    // script for filter 
    $(document).ready(function() {

        $('#elapsed-holidays-table').hide()

        //Dropdown Toggle
        $('#filter-btn').click(() => {

            $('#elapsed-holidays-table').toggle()

        })

        // Initialize DataTable
        var table = $('#employeeTable').DataTable({
            paging: false,
            ordering: false,
            info: false
        });


    })
    </script>

</body>

</html>