
<?php
  include "../../utils/Staff/staff.class.php";
    //start session
    session_start();
    //Get the User Object
    $user =  $_SESSION['user'];
?>

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Staff Dashboard</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- all common CSS link  -->
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
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
     include "../../includes/Staff/sidenavbar.php";
    ?>

    <section class="home-section">

        <!--Including header -->

        <?php
            include "../../includes/header.php";
        ?>

        <!-- Below code for dashboard -->
        <div class="container bg-white rounded-lg shadow-lg mt-3 dash_table">



            <div class="row p-4 rounded-lg shadow-lg d-flex justify-content-sm-center  "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D">

                <div class="col-md-12 col-sm-12 py-3">
                    <h3> Current Balance </h3>
                </div>

                <!----------------------------------- Balance cards ----------------------------------->



                <?php
                
                    $data =  $user->getCurrentBalance() ;

                    while( $row = mysqli_fetch_assoc($data) ){

                    $time = Utils::getTimeDiff( $row['date'] );

                    echo
                        "<div class='col-md-3 col-sm-12 py-2   rounded-lg m-4 bg-white shadow-lg fit-content'
                            style='border-right:6px solid #11101D '>
                            <div class='row p-2 pr-0 '>
                                <div class='col-12  '>
                                    <div class='row pb-1 pl-2 d-flex justify-content-sm-center'>
                                        <h5>" . $row['leaveType'] . "</h5>
                                    </div>
                                    <div class='row d-flex justify-content-sm-center '>
                                        <h3>" .$row['balance']. "</h3>
                                    </div>
                                </div>

                            </div>
                            <div class='row border-top p-2'>
                                <small class='text-muted' style='font-size: smaller;'>Updated on " . $time . "</small>
                            </div>
                        </div>";


                    } 
                ?>

                

            </div>






        </div>

    </section>

</body>

</html>