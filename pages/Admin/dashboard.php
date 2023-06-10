<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>


<!-- Include this to use User object -->
<?php

    //include class definition
    require('../../utils/Admin/Admin.class.php');

    //include Config Class
    require('../../utils/Config/Config.class.php');

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="../../css/dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>

    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>

    <title>Bajaj Institute of Technology, Wardha</title>

</head>

<body>

    <?php include "../../includes/Admin/sidenavbar.php"  ?>

    <section class="home-section">

        <div class="cards-container container-fluid bg-white row p-3 rounded-lg shadow-lg d-flex justify-content-sm-center  " style="transition: all all 0.5s ease; border-right:6px solid #11101D" >

            <div class="header-body">
                <div class="row">

                    <!-- Card -->
                    <div class=" card-width  col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-3">Total Employees</h5>
                                        <span class="h2 font-weight-bold mb-0"> <?php echo $user->getTotalEmployee() ?>
                                        </span>
                                    </div>

                                </div>
                                <p class=" no-desc mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card -->
                    <div class=" card-width  col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-3">Total Employees</h5>
                                        <span class="h2 font-weight-bold mb-0"> <?php echo $user->getTotalEmployee() ?>
                                        </span>
                                    </div>

                                </div>
                                <p class=" no-desc mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card -->
                    <div class=" card-width  col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-3">Total Employees</h5>
                                        <span class="h2 font-weight-bold mb-0"> <?php echo $user->getTotalEmployee() ?>
                                        </span>
                                    </div>

                                </div>
                                <p class=" no-desc mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card -->
                    <div class=" card-width  col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-3">Total Employees</h5>
                                        <span class="h2 font-weight-bold mb-0"> <?php echo $user->getTotalEmployee() ?>
                                        </span>
                                    </div>

                                </div>
                                <p class=" no-desc mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card -->
                    <div class=" card-width  col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-3">Total Employees</h5>
                                        <span class="h2 font-weight-bold mb-0"> <?php echo $user->getTotalEmployee() ?>
                                        </span>
                                    </div>

                                </div>
                                <p class=" no-desc mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card -->
                    <div class=" card-width  col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-3">Total Employees</h5>
                                        <span class="h2 font-weight-bold mb-0"> <?php echo $user->getTotalEmployee() ?>
                                        </span>
                                    </div>

                                </div>
                                <p class=" no-desc mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <p>
            <?php
                $arr =   $user->getNotifications();

                print_r($arr);

                foreach ($arr as $key => $value) {   
                  
            ?>

        <p> <?php echo $value ?> </p>

        <?php } ?>
        </p>

    </section>

</body>

</html>