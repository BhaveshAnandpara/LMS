<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>



<!-- Include this to use User object -->
<?php

    //include Config Class
    require('../../utils/Utils.class.php');
    require('../../utils/Config/Config.class.php');

    //include class definition
    require('../../utils/Admin/Admin.class.php');

    //start session
    session_start();

    //Get the User Object
    $user =  $_SESSION['user'];
    $leaveID = $_GET['leaveID'];
    $empID = $_GET['empID'];

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

        <?php 
            $leaveDetails = mysqli_fetch_assoc( Utils::getLeaveDetailsOfEmployee($empID , $leaveID) );
        ?>

        <div class=" manageLeaveContainer container ">

            <?php $actionUrl = "validateEmpBalance.php?leaveID=$leaveID&empID=$empID" ?>

            <form class=" bg-white shadow pl-5 pr-5 pb-3 pt-2 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">

                <h4 class="pb-3 pt-2 mt-3" style="color: #11101D;">Manage Leave Balance</h4>

                <div class="form-row">


                    <!-- //ROW -->

                    <div class="form-group mt-4 col-md-12">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Employee Name</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $leaveDetails['fullName'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-12">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Leave Type</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $leaveDetails['leaveType'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-12">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Current Balance</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $leaveDetails['balance'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-12">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Last Updated On</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo date( 'd-m-Y' , strtotime( $leaveDetails['date'] ) )  ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-12 manage-leaves-reason ">
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $leaveDetails['reason']  ?>
                        </div>
                    </div>

                    <!-- Credit / Debit-->
                    <div class="form-group mt-4 col-md-12 d-flex justify-content-flex-start alilgn-items-center">
                        <input class=" ml-3 mr-1" type="radio" checked id="credit-radio" name="manageBalance"
                            value="credit">
                        <label class=" mb-0 d-flex align-items-center" for="credit-radio"> Credit </label>
                        <input class=" ml-3 mr-1" type="radio" id="debit-radio" name="manageBalance" value="debit">
                        <label class=" mb-0 d-flex align-items-center" for="debit-radio"> Debit </label>
                    </div>


                    <!-- Amount -->
                    <div class="form-group ml-2 col-md-6">
                        <input type="number"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Amount" name="amount">
                    </div>

                    <!-- Reason -->
                    <div class="form-group ml-2 col-md-12  ">
                        <input type="text"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Reason" name="reason">
                    </div>

                    <div class="form-group col-md-6"> <input type="submit" value="Update" name="addLeaveSubmit"
                            class="submitbtn"> </div>

            </form>

        </div>


    </section>
</body>

</html>