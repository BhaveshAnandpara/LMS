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


<?php

echo Utils::alert( "Are you Sure ! You want to change the Status ", "ALERT" );

$status = $_GET['status'];
$empID = $_GET['empID'];


    $emp_ACTIVE = Config::$_EMPLOYEE_STATUS['ACTIVE'];
    $emp_INACTIVE = Config::$_EMPLOYEE_STATUS['INACTIVE'];


    $FACULTY = Config::$_EMPLOYEE_ROLE['FACULTY'];

    $conn = sql_conn();

    if( $status == $emp_ACTIVE ){

        $sql = "UPDATE employees SET `status` = '$emp_ACTIVE', `deactivationDate` = NULL , `joiningDate`= current_timestamp(), `role`= '$FACULTY'  where employeeID=$empID";
        $result =  mysqli_query( $conn , $sql);

        
        if( !$result ){
            echo "Error Occured during Updating Employee";
        }

    }

    else if( $status == $emp_INACTIVE ){


        $leaveTypes = Utils::getLeaveTypes();

        while( $row = mysqli_fetch_assoc($leaveTypes) ){

            $leaveID = $row['leaveID'];

            $sql = "UPDATE leavebalance SET `balance` = 0  where employeeID=$empID and leaveID=$leaveID ";
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                echo "Error Occured during Deducting Balance";
            }
        
        }

        $sql = "UPDATE employees SET `status` ='$emp_INACTIVE', `deactivationDate` = current_timestamp()  where employeeID=$empID";

        $result =  mysqli_query( $conn , $sql);

        if( !$result ){
            echo "Error Occured during Updating Employee";
        }

    }


    echo 
    "<script>
        window.location.href='./manageEmployees.php'
    </script>";

?>