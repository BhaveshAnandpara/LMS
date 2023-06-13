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
    require('../../utils/Utils.class.php');

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


        <div class="container" >

        <div class=" table-container bg-white rounded-lg shadow-lg mt-3">

            <div class="masterdata-container row p-5 rounded-lg shadow-lg d-flex justify-content-sm-start flex-column "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D">


                <div class="col-md-12 col-sm-12 py-3">
                    <h3> Manage Master Data </h3>
                </div>

                <a href="../../pages/Admin/addLeave.php" class="my-3" ><button class="AddBtn"> + </button></a>

                <table class="tablecontent">

                    <thead>
                        <tr>
                            <th>LEAVE ID</th>
                            <th>LEAVE NAME</th>
                            <th>LEAVE DESCRIPTION</th>
                            <th>CYCLE DATE</th>
                            <th>INTERVAL</th>
                            <th>INCREMENT</th>
                            <th>CARRY FORWARD INTO</th>
                            <th>BALANCE LIMIT</th>
                            <th>APPLY LIMIT</th>
                            <th>WAITING TIME</th>
                            <th>STATUS</th>
                            <th>EDIT</th>
                            <th>INACTIVE</th>
                        </tr>
                    </thead>

                    <tbody id="tbody">

                        <?php

                            $null_cycle_date = "No Date";
                            $masterData = $user->getMasterData(); // Returns Array of Tuples in Database

                            foreach($masterData as $row ){

                                if ($row['cycleDate'] == '0000-00-00') $cycle_date = $null_cycle_date;
                                else $cycle_date = date( 'd-m-Y' , strtotime($row['cycleDate']) );

                                if (  empty( $row['leaveInterval'] ) ) $row['leaveInterval'] = 0;
                                if (  empty( $row['increment'] ) ) $row['increment'] = 0;
                                if (  empty( $row['carryForwardInto'] ) ) $row['carryForwardInto'] = "No Carry Forwards";
                                if (  empty( $row['balanceLimit'] ) ) $row['balanceLimit'] = "No Limit";
                                else{ $row['balanceLimit'] = $row['balanceLimit']. " Leaves" ;}
                                if (  empty( $row['applyLimit'] ) ) $row['applyLimit'] = "No Limit ";
                                else{ $row['applyLimit'] = $row['applyLimit']. " Leaves" ;}
                                if (  empty( $row['waitingTime'] ) ) $row['waitingTime'] = 0;

                                echo "<tr>";
                                echo "<td>" . $row['leaveID'] . "</td>";
                                echo "<td>" . $row['leaveType'] . "</td>";
                                echo "<td>" . $row['leaveDesc'] . "</td>";
                                echo "<td>" . $cycle_date . "</td>";
                                echo "<td>" . $row['leaveInterval'] . " Month</td>";
                                echo "<td>" . $row['increment'] . " Leaves</td>";
                                echo "<td>" . $row['carryForwardInto'] . "</td>";
                                echo "<td>" . $row['balanceLimit'] . " </td>";
                                echo "<td>" . $row['applyLimit'] . " </td>";
                                echo "<td>" . $row['waitingTime'] . " Days </td>";
                                echo "<td>" . $row['status'] . " </td>";
                                echo "<td><a href='../../pages/Admin/editLeave.php?leaveId=$row[leaveID]' name='edit'><i class='fa-solid fa-pen-to-square edit'></i></a></td>";
                                echo "<td><a href='../../utils/deleteLeaveId.php?leaveId=$row[leaveID]' name='delete'><i class='fa-solid fa-trash delete'></i></a></td>";
                                echo "</tr>";
                            }

                        ?>
                    </tbody>
                </table>

            </div>

        </div>

        </div>


    </section>
</body>

</html>