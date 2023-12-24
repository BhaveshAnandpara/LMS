<?php  session_start();
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
    $user = unserialize($_SESSION['user']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Leave History</title>

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
     include "../../includes/Principal/sidenavbar.php";
    ?>

    <section class="home-section">

        <!--Including header -->

        <?php
            include "../../includes/header.php";
        ?>

        <!-- Below code for dashboard -->
        <div class="container overflow-scroll">

            <!-- Recently Applied Leaves -->
            <div class=" row p-4 bg-white rounded-lg shadow-lg d-flex justify-content-sm-center  pl-5 pr-5 pb-3 pt-4 my-5 ml-2 "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D; width:max-content">
                <div class="col-md-12 col-sm-12 py-3">
                    <h3>Leave Request</h3>
                </div>

                <table class="tablecontent">

                    <thead>
                        <tr>
                            <th>APPLICANT NAME</th>
                            <th>APPLICANT EMAIL</th>
                            <th>DEPARTMENT</th>
                            <th>LEAVE TYPE</th>
                            <th>FROM</th>
                            <th>TO</th>
                            <th>TOTAL DAYS</th>
                            <th>STATUS</th>
                            <th>VIEW</th>
                        </tr>
                    </thead>

                    <tbody id="tbody">

                        <?php
                                $data =  $user->getLeaveRequests();
                                
                                while ($row = mysqli_fetch_assoc($data)) { 
                                
                                    $leaveTypes = $user->getAppLeaveTypes( $row['applicationID'] );
                                    $applicantInfo = mysqli_fetch_assoc( $user->findEmployeeDetailsUsingId( $row['employeeID'] ) );
                                    
                        ?>
                        <tr>

                            <td><?php echo $applicantInfo["fullName"] ?></td>
                            <td><?php echo $applicantInfo["email"] ?></td>
                            <td><?php echo $row["deptName"] ?></td>
                            <td><?php echo $leaveTypes ?></td>
                            <td><?php echo date( 'd-m-Y' ,strtotime($row["startDate"]) ) ?></td>
                            <td><?php echo date( 'd-m-Y' ,strtotime($row["endDate"]) ) ?></td>
                            <td><?php echo $row["totalDays"] ?></td>
                            <td class= <?php echo $row['status']." font-weight-bold " ?>  ><?php echo $row['status'] ?></td>
                            <td class="text-end"> <a href="../Staff/viewDetails.php?id=<?php echo $row['applicationID'] ?>" ><i class="fas fa-eye"></i></a> </td>

                        </tr>
                        <?php } ?>

                    </tbody>
                </table>

            </div>

        </div>

    </section>

</body>

</html>