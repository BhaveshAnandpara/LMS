

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


        <div class="container manageHolidaysContainer">

            <form class=" bg-white shadow pl-5 pr-5 pb-3 pt-2 mt-5 rounded-lg" action="validateNewHoliday.php" method="POST">

                <h4 class="pb-3 pt-2 mt-4" style="color: #11101D;">Add New Holiday</h4>

                <div class="form-row">

                    <!--  Date -->
                    <div class="form-group col-md-6">
                        <input type="text" onfocus="(this.type='date')" class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Date" name="holidayDate">
                    </div>

                    <!-- Holiday Name -->
                    <div class="form-group col-md-12">
                        <input type="text" maxLength="100"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder=" Holiday Name " name="holidayName">
                    </div>


                    <div class="form-group col-md-6"> <input type="submit" name="addDeptSubmit" class="submitbtn"> </div>

            </form>

        </div>


    </section>
</body>

</html>