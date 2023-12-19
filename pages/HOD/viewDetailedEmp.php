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
    $user = unserialize($_SESSION['user']) ;
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
     include "../../includes/HOD/sidenavbar.php";
    ?>


    <section class="home-section">

        <!--Including header -->
        <?php
            include "../../includes/header.php";
        ?>

        <?php 
            $empDetails = mysqli_fetch_assoc( Utils::getEmpDetails($empID) );
        ?>

        <div class="container">

            <!------------------------------ Basic Info ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">



                <h4 class="pb-3 pt-2  " style="color: #11101D;">Basic Information <i id="basic-info"
                        class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                <div class="form-row" id="basic-info-container">

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Employee ID</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $empDetails['employeeID'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Employee Name</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $empDetails['fullName'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Department</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $empDetails['deptName'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Status</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $empDetails['status'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Joining Date</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo  date( 'd-m-Y' , strtotime($empDetails['joiningDate']) )  ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Inactivation Date</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php
                                if( empty( $empDetails['deactivationDate'] ) ) echo "NULL";
                                else echo $empDetails['deactivationDate'] ;
                              
                              ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Type</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $empDetails['type'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">Role</h6>
                        </div>
                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $empDetails['role'] ?>
                        </div>
                    </div>

                    <!-- //ROW -->

                    <div class="form-group col-md-6">
                        <div class="col-sm-3 d-inline">
                            <h6 class="mb-0 d-inline ">No. of Leaves Taken this Year</h6>
                        </div>

                        <?php  $leaveCounter = mysqli_fetch_assoc( Utils::getEmpLeaveCounter( $empID ) )  ?>

                        <div class="col-sm-3 d-inline text-secondary">
                            <?php  echo $leaveCounter['count'] ?>
                        </div>
                    </div>


                </div>



            </div>

            <!------------------------------ Leave Balance ------------------------------>
            <div class=" bg-white shadow pl-5 pr-5 pb-3 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">


                <h4 class="pb-3 pt-2  " style="color: #11101D;">Leave Balance <i id="leave-balance"
                        class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                <div class="form-row" id="leave-balance-container">

                    <table class="tablecontent" id="leave-balance-table">

                        <thead>
                            <tr>
                                <th>LEAVE ID</th>
                                <th>LEAVE NAME</th>
                                <th>BALANCE</th>
                                <th>COUNTER</th>
                                <th>LAST UPDATE ON</th>
                                <th>REASON</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                            <?php

                                $leavebalanceDetails =  Utils::getLeaveBalanceOfEmployee($empID); 

                                while($row =  mysqli_fetch_assoc($leavebalanceDetails) ){

                                    echo "<tr>";
                                    echo "<td  >" . $row['leaveID'] . "</td>";
                                    echo "<td  >" . $row['leaveType'] . "</td>";
                                    echo "<td  >" . $row['balance'] . "</td>";
                                    echo "<td  >" . $row['leaveCounter'] . " </td>";
                                    echo "<td  >" . date( 'd-m-Y' , strtotime($row['date']) )  . " </td>";
                                    echo "<td  >" . $row['reason']  . " </td>";
                                }
                            
                            ?>
                        </tbody>
                    </table>


                </div>

            </div>



        </div>


    </section>

    <script>
    // script for filter 
    $(document).ready(function() {

        $('#basic-info-container').hide()
        $('#leave-balance-container').hide()


        $('#basic-info').click(() => {

            $('#basic-info-container').toggle();

        })

        $('#leave-balance').click(() => {

            $('#leave-balance-container').toggle();

        })

        leave - balance

    });
    </script>

</body>

</html>