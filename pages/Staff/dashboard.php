<?php     

    session_start();    

    //  Creates database connection 
    require "../../includes/db.conn.php";
    

    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');
    
    //include class definition
    require('../../utils/Staff/staff.class.php');

    

?>


<!-- Include this to use User object -->
<?php

    //Get the User Object
    $user =  unserialize($_SESSION['user']);


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
     include "../../includes/Staff/sidenavbar.php";
    ?>

    <section class="home-section">

        <!--Including header -->

        
        <?php
            include "../../includes/header.php";
        ?>


        <!-- Below code for dashboard -->
        <div class="container">

            <!-- Current Balance -->


            <div class="row p-4 bg-white rounded-lg shadow-lg d-flex justify-content-sm-center  pl-5 pr-5 pb-3 pt-4 my-5 ml-2 "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D">

                <div class="col-md-12 col-sm-12 py-3">
                    <h3> Current Balance </h3>
                </div>

                <?php
                        $data =  $user->getCurrentBalance() ;
                        

                        while( $row = mysqli_fetch_assoc($data) ){

                        // $time = Utils::getTimeDiff( $row['date'] );


                            echo

                                "<div class='col-md-3 col-sm-12 py-4   rounded-lg m-4 bg-white shadow-lg fit-content'
                                style='border-right:6px solid #11101D '>
            
                                <!-- Div for leave type and Leave Balance      -->
                                <div class='row p-2 pr-0 '>
                                    <div class='col-12  '>
                                        <div class='row pb-1 pl-2 d-flex justify-content-sm-center'>
                                            <!-- PHP CODE HERE -->
                                            <h5>" .$row['leaveType']. "</h5>
                                        </div>
                                        <div class='row d-flex justify-content-sm-center '>
                                            <!-- PHP CODE HERE -->
                                            <h3> " .$row['balance']. " </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                            
                        } 
                    ?>

            </div>


            <!-- Recently Applied Leaves -->

            <div class=" row p-4 bg-white  rounded-lg shadow-lg d-flex justify-content-sm-center  pl-5 pr-5 pb-3 pt-4 my-5 ml-2 "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D">

                <div class="col-md-12 col-sm-12 py-3">
                    <h3> Recently Applied Leaves</h3>
                </div>

                
                <?php
                
                    $data =  $user->recentlyAppliedLeave();

                    if( mysqli_num_rows($data) == 0){
                        echo "<p style=' width : 100%; text-align : center; ' > No Recently Applied Leaves </p>";
                    }
                    else{
                        
                ?>

                <table class="tablecontent">

                    <thead>
                        <tr>
                            <th>LEAVE TYPE</th>
                            <th>APPLICATION DATE</th>
                            <th>EXTENDED FROM</th>
                            <th>FROM</th>
                            <th>TO</th>
                            <th>TOTAL DAYS</th>
                            <th>STATUS</th>
                            <th>VIEW</th>
                            <th>EDIT</th>
                            <th>WITHDRAW</th>
                        </tr>
                    </thead>

                    <tbody id="tbody">

                        <?php
                               
                                
                                while ($row = mysqli_fetch_assoc($data)) { 
                                
                                    $leaveTypes = $user->getAppLeaveTypes( $row['applicationID'] );
                                    
                        ?>
                        <tr>

                            <td><?php echo $leaveTypes ?></td>
                            <td><?php echo date( 'd-m-Y' ,strtotime($row["dateTime"]) ) ?></td>
                            <?php 
                                if( empty($row["extension"]) ){
                                    echo "<td class='text-end'> <i class='fas fa-link' style='color: #ccc;' ></i> </td>";
                                }
                                else{
                                    echo "<td class='text-end'> <a href='./viewDetails.php?id=$row[extension]'><i class='fas fa-link'></i></a> </td>";
                                }
                            ?>
                            <td><?php echo date( 'd-m-Y' ,strtotime($row["startDate"]) ) ?></td>
                            <td><?php echo date( 'd-m-Y' ,strtotime($row["endDate"]) ) ?></td>
                            <td><?php echo $row["totalDays"] ?></td>
                            
                            <td class= <?php echo $row['status']." font-weight-bold " ?>  ><?php echo $row['status'] ?></td>

                            <td class="text-end"> <a href="./viewDetails.php?id=<?php echo $row['applicationID'] ?>" ><i class="fas fa-eye"></i></a> </td>
                            <?php
                            
                            if( $row['status'] == Config::$_APPLICATION_STATUS['PENDING'] || $row['status'] == Config::$_APPLICATION_STATUS['REJECTED_BY_HOD'] || $row['status'] == Config::$_APPLICATION_STATUS['APPROVED_BY_HOD'] || $row['status'] == Config::$_APPLICATION_STATUS['REJECTED_BY_PRINCIPAL'] ){

                                echo "<td class='text-end'> <a href='./editApplication.php?id=".$row['applicationID']."&extend=false' ><i class='fas fa-pen-to-square'></i></a> </td>";

                            }
                            else if( $row['status'] == Config::$_APPLICATION_STATUS['APPROVED_BY_PRINCIPAL'] || $row['status'] == Config::$_APPLICATION_STATUS['DEDUCTED_FROM_EL'] || $row['status'] == Config::$_APPLICATION_STATUS['LEAVE_WITHOUT_PAY']  ){
                                echo "<td class='text-end'> <a href='./editApplication.php?id=".$row['applicationID']."&extend=true' ><i class='fas fa-pen-to-square'></i></a> </td>";
                            }
                            else{
                                echo "<td class='text-end'> <i class='fas fa-pen-to-square' style='color: #ccc;' ></i> </td>";
                            }
                            ?>
                            <td class='text-end'> <a href="./withdrawApplication.php?id=<?php echo $row['applicationID'] ?>&empID=<?php echo $user->employeeId ?>" > <i class='fas fa-trash' ></i> </a> </td>

                        </tr>
                        <?php } ?>

                    </tbody>
                </table>

                <?php
                
                    }
                ?>

            </div>

        </div>

    </section>

</body>

</html>