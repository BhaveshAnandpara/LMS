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

            <!-- List of Staff on Leave -->
            <div class=" bg-white shadow pl-5 pr-5 pb-5 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">


                <h4 class="pb-3 pt-2  " style="color: #11101D;"> List of Staff on Leave<i id="add-app" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                        <div class="form-row" id="add-app-container">

                            <table class="tablecontent" id="add-app-table">

                                <thead>
                                    <tr>
                                        <th>APPLICANT EMAIL</th>
                                        <th>START DATE</th>
                                        <th>END DATE</th>
                                        <th>REASON</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="tbody">

                                    <?php

                                        $addApp =  $user->approvalRequst(); 
                                        while($row =  mysqli_fetch_assoc($addApp) ){
                                        
                                            
                                            echo "<tr>";
                                            echo "<td  >" . $row['email'] . "</td>";
                                            echo "<td  >" . $row['startDate'] . "</td>";
                                            echo "<td  >" . $row['endDate'] . " </td>";
                                            echo "<td  >" . $row['reason']  . " </td>";
                                            if( $row['status']  === Config::$_ADJUSTMENT_STATUS['PENDING'] ){
                                                echo "<td  > <a href='../../pages/Staff/validateAdjAction.php?applicationID=" .$row['applicationID']. "&approverId=" .$row['approverId']. "&type=approval&action=" .Config::$_ADJUSTMENT_STATUS['ACCEPTED']. "'> <button class='submitbtn clickable my-0 mx-2' > Accept </button> </a></td>";
                                                echo "<td  > <a href='../../pages/Staff/validateAdjAction.php?applicationID=" .$row['applicationID']. "&approverId=" .$row['approverId']. "&type=approval&action=" .Config::$_ADJUSTMENT_STATUS['REJECTED']. "'> <button class='submitbtn clickable my-0 mx-2' > Reject </button> </a></td>";
                                            }else{
                                                echo "<td class=" .$row['status']. " >" . $row['status']  . " </td>";
                                            }
                                        }
                                    
                                    ?>
                                </tbody>

                            </table>
                                    
                                    
                        </div>

            </div>


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

                        // <!-- Card -->
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


                <table class="tablecontent">

                    <thead>
                        <tr>
                            <th>LEAVE TYPE</th>
                            <th>APPLICATION DATE</th>
                            <th>FROM</th>
                            <th>TO</th>
                            <th>TOTAL DAYS</th>
                            <th>STATUS</th>
                            <th>VIEW</th>
                        </tr>
                    </thead>

                    <tbody id="tbody">

                        <?php
                                $data =  $user->recentlyAppliedLeave();
                                
                                while ($row = mysqli_fetch_assoc($data)) { 
                                
                                    $leaveTypes = $user->getAppLeaveTypes( $row['applicationID'] );
                                    
                        ?>
                        <tr>

                            <td><?php echo $leaveTypes ?></td>
                            <td><?php echo date( 'd-m-Y' ,strtotime($row["dateTime"]) ) ?></td>
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

        <script>

            $(document).ready(function() {
                
            }


        </script>

    </section>

</body>

</html>