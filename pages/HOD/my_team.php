<?php     session_start();
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>


<!-- Include this to use User object -->
<?php

    //include class definition
    require('../../utils/Staff/staff.class.php');

    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');

    //start session


    //Get the User Object
    $user =  unserialize($_SESSION['user']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>My Team</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- all common CSS link  -->
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/Admin/manageMasterData.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

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
     include "../../includes/HOD/sidenavbar.php";
    ?>

    <section class="home-section">

        <!--Including header -->

        <?php
            include "../../includes/header.php";
        ?>

        <!-- Below code for dashboard -->
        <div class="container">

            <!-- Recently Applied Leaves -->

            <div class=" row p-4 bg-white  rounded-lg shadow-lg d-flex justify-content-sm-center  pl-5 pr-5 pb-3 pt-4 my-5 ml-2 "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D">

                <div class="col-md-12 col-sm-12 py-3">
                    <h3>My Team</h3>
                </div>

                <?php
                    $data =  $user->fetchMyTeamData();


                    if( mysqli_num_rows($data) == 0){
                        echo "<p style=' width : 100%; text-align : center; ' >No Team Members Yet</p>";
                    }
                    else{
                                
                ?>

                    
                <table class="tablecontent">

                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Employee Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Joining Date</th>
                            <th>View Details</th>
                        </tr>
                    </thead>

                    <tbody id="tbody">

                        <?php
                                $i = 1;
                                
                                
                                while ($row = mysqli_fetch_assoc($data)) { 
                                    
                        ?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['fullName']?></td>
                            <td><?php echo $row['email']?></td>
                            <td><?php echo $row['role']?></td>
                            <td><?php echo $row['joiningDate'] ?></td>
                            <td class="text-end"><a href=<?php echo "./viewDetailedEmp.php?empID=".$row['employeeID'] ?> ><i class="fas fa-eye"></i></a> </td>

                        </tr>
                        <?php } ?>

                    </tbody>
                </table>

                <?php } ?>

            </div>

        </div>

    </section>

</body>

</html>