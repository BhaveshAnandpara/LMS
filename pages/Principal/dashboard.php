<?php session_start();
//  Creates database connection 
require "../../includes/db.conn.php";
?>


<!-- Include this to use User object -->
<?php

//include class definition
require('../../utils/Principal/Principal.class.php');

//include Config Class
require('../../utils/Config/Config.class.php');
require('../../utils/Utils.class.php');

//start session


//Get the User Object
$user =  unserialize($_SESSION['user']) ;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Principal Dashboard</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- all common CSS link  -->
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/HOD/dashboard.css?v=<?php echo time(); ?>">
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
    include "../../includes/Principal/sidenavbar.php";
    ?>

    <section class="home-section">

        <!--Including header -->

        <?php
        include "../../includes/header.php";
        ?>

        <!-- Below code for dashboard -->
        <div class="container mainDiv">

            <!-- List of Staff on Leave -->
            <div class=" bg-white shadow pl-5 pr-5 pb-5 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">


                <h4 class="pb-3 pt-2  " style="color: #11101D;"> List of Staff on Leave<i id="add-app" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>
                <?php 
                    include '../../utils/HOD/employeesOnLeave.php'
                ?>
            </div>
        </div>
    </section>

</body>

</html>