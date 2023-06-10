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

    <section class="home-section">

        <div class="container bg-white rounded-lg shadow-lg mt-3">
            <div class="row p-3 rounded-lg shadow-lg d-flex justify-content-sm-center "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D; ">
                <div class="col-md-3 col-sm-12  col-sm-12 rounded-lg m-3 bg-white shadow-lg"
                    style="border-right:6px solid #11101D ">

                    <div class="row p-2 pr-0">
                        <div class="col-12  ">
                            <div class=" row pb-1 pl-2 d-flex justify-content-sm-center">
                                <h5>Total Employees</h5>
                            </div>
                            <div class="row d-flex justify-content-sm-center">
                                <h5> <?php echo  "<p class='stats' >" . $user->getTotalEmployee() . "</p>" ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12  col-sm-12 rounded-lg m-3 bg-white shadow-lg"
                    style="border-right:6px solid #11101D ">
                    <div class="row p-2 pr-0">

                        <div class="col-12  ">
                            <div class=" row pb-1 pl-2 d-flex justify-content-sm-center">
                                <h5> Total Department</h5>
                            </div>
                            <div class="row d-flex justify-content-sm-center">
                                <h5> <?php echo  "<p class='stats' >" . $user->getTotalDepartment() . "</p>" ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top p-3">
                        hello
                    </div>
                </div>
                <div class="col-md-3 col-sm-12  col-sm-12 rounded-lg m-3 bg-white shadow-lg"
                    style="border-right:6px solid #11101D ">
                    <div class="row p-2 pr-0">

                        <div class="col-12  ">
                            <div class=" row pb-1 pl-2 d-flex justify-content-sm-center">
                                <h5> Total Leave Type</h5>
                            </div>
                            <div class="row d-flex justify-content-sm-center">
                                <h5> <?php echo  "<p class='stats' >" . $user->getTotalLeaveTypes() . "</p>" ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top p-3">
                        hello
                    </div>
                </div>

                <div class="col-md-3 col-sm-12  col-sm-12 rounded-lg m-3 bg-white shadow-lg"
                    style="border-right:6px solid #11101D ">
                    <div class="row p-2 pr-0">

                        <div class="col-12  ">
                            <div class=" row pb-1 pl-2 d-flex justify-content-sm-center">
                                <h5> Teaching Staff</h5>
                            </div>
                            <div class="row d-flex justify-content-sm-center">
                                <h5> <?php echo  "<p class='stats' >" . $user->getTotalTeachingStaff() . "</p>" ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top p-3">
                        hello
                    </div>
                </div>

                <div class="col-md-3 col-sm-12  col-sm-12 rounded-lg m-3 bg-white shadow-lg"
                    style="border-right:6px solid #11101D ">
                    <div class="row p-2 pr-0">

                        <div class="col-12  ">
                            <div class=" row pb-1 pl-2 d-flex justify-content-sm-center">
                                <h5> Non-Teaching Staff</h5>
                            </div>
                            <div class="row d-flex justify-content-sm-center">
                                <h5> <?php echo  "<p class='stats' >" . $user->getTotalNonTeachingStaff() . "</p>" ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top p-3">
                        hello
                    </div>
                </div>

                <div class="col-md-3 col-sm-12  col-sm-12 rounded-lg m-3 bg-white shadow-lg"
                    style="border-right:6px solid #11101D ">
                    <div class="row p-2 pr-0">

                        <div class="col-12  ">
                            <div class=" row pb-1 pl-2 d-flex justify-content-sm-center">
                                <h5> Deactivated Staff</h5>
                            </div>
                            <div class="row d-flex justify-content-sm-center">
                                <h5> <?php echo  "<p class='stats' >" . $user->getTotalInactiveStaff() . "</p>" ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top p-3">
                        hello
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