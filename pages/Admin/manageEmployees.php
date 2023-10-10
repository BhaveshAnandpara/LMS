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

<?php

    function empStatusButton( $status ){

        if( $status == Config::$_EMPLOYEE_STATUS['ACTIVE'] ) return Config::$_EMPLOYEE_STATUS['INACTIVE'];
        return Config::$_EMPLOYEE_STATUS['ACTIVE'];

    }

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


        <div class="container">

            <div class=" table-container bg-white rounded-lg shadow-lg mt-3">

                <div class="masterdata-container row p-5 rounded-lg shadow-lg d-flex justify-content-sm-start flex-column "
                    style="transition: all all 0.5s ease; border-right:6px solid #11101D">


                    <div class="col-md-12 col-sm-12 py-3">
                        <h3> Manage Employees </h3>
                    </div>

                    <a href="../../pages/Admin/addEmp.php" class="my-3"><button class="AddBtn"> + </button></a>

                    <table class="tablecontent">

                        <thead>
                            <tr>
                                <th>EMPLOYEE ID</th>
                                <th>EMPLOYEE NAME</th>
                                <th>EMPLOYEE EMAIL</th>
                                <th>DEPARTMENT</th>
                                <th>ROLE</th>

                                <?php
                                //  To print all available Leave Types
                                $leaveTypes = Utils::getLeaveTypes();

                                while( $row = mysqli_fetch_assoc( $leaveTypes ) ){
                                    echo "<th>". strtoupper($row['leaveType']) ."</th>";
                                }

                            ?>

                                <th>STATUS</th>
                                <th>EDIT</th>
                                <th>VIEW</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                            <?php

                            $employees = Utils::getAllEmployees(); // Returns Array of Tuples in Database

                            
                            foreach($employees as $row ){
                                
                                $statusBtnValue = empStatusButton( $row['status'] );

                                $statusStyle = "text-black";
                                $employeeID = $row['employeeID'];
                                if( $row['status'] == Config::$_EMPLOYEE_STATUS['INACTIVE'] ) $statusStyle = "text-black-50";
                                
                                echo "<tr>";
                                echo "<td class='$statusStyle' >" . $row['employeeID'] . "</td>";
                                echo "<td class='$statusStyle' >" . $row['fullName'] . "</td>";
                                echo "<td class='$statusStyle' >" . $row['email'] . "</td>";
                                echo "<td class='$statusStyle' >" . $row['deptName'] . " </td>";
                                echo "<td class='$statusStyle' >" . $row['role'] . " </td>";
                                
                                //  To print all available balance of all Leave Types
                                $leaveBalances = Utils::getLeaveBalanceOfEmployee($row['employeeID']  );
                                
                                
                                while( $leaveRow = mysqli_fetch_assoc( $leaveBalances ) ){
                                    
                                    $leaveRow['balance'] = floatval($leaveRow['balance']);
                                    echo "<td class='$statusStyle' >". ($leaveRow['balance']) ."</td>";
                                }

                                echo "<td class='$statusStyle'  class='font-weight-bold' >" . $row['status'] . " </td>";
                                        
                                if( $row['status'] == Config::$_EMPLOYEE_STATUS['ACTIVE'] ){
                                    echo "<td  ><a href='../../pages/Admin/editEmp.php?empID=$employeeID '><i class='fa-solid fa-pen-to-square edit $statusStyle'></i></a></td>";
                                }else{
                                    echo "<td class='$statusStyle' ></td>";
                                }
                                echo "<td ><a href='../../pages/Admin/viewDetailedEmp.php?empID=$employeeID'><i class='fa-solid fa-eye edit $statusStyle'></i></a></td>";
                                echo "<td><a href='../../pages/Admin/validateEmpStatus.php?empID=$employeeID&status=$statusBtnValue' name='delete'> <button class='submitbtn m-0 w-100' > ". $statusBtnValue ." </button> </a></td>";
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