<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
    
        //include Config Class
    require('../../utils/Utils.class.php');
    require('../../utils/Config/Config.class.php');

    //include class definition
    require('../../utils/Admin/admin.class.php');

    //start session
    session_start();
    
?>



<!-- Include this to use User object -->
<?php



    //Get the User Object
    $user =  $_SESSION['user'];

    ?>


<?php

    $status = $_GET['status'];
    $empID = $_GET['empID'];


    $emp_ACTIVE = Config::$_EMPLOYEE_STATUS['ACTIVE'];
    $emp_INACTIVE = Config::$_EMPLOYEE_STATUS['INACTIVE'];

    $FACULTY = Config::$_EMPLOYEE_ROLE['FACULTY'];
    $HOD = Config::$_EMPLOYEE_ROLE['HOD'];

    $conn = sql_conn();


    if( $status == $emp_ACTIVE ){

        $sql = "UPDATE employees SET `status` = '$emp_ACTIVE', `deactivationDate` = NULL , `joiningDate`= current_timestamp(), `role`= '$FACULTY'  where employeeID=$empID";
        $result =  mysqli_query( $conn , $sql);

        
        if( !$result ){
            $_SESSION['response_message'] = serialize([ "Error Occured during Updating Employee" , "ERROR"]);

            // Redirect back to addLeave.php
            header("Location: manageEmployees.php");
            exit();
        }

    }


    else if( $status == $emp_INACTIVE ){


        $leaveTypes = Utils::getLeaveTypes();

        while( $row = mysqli_fetch_assoc($leaveTypes) ){

            $leaveID = $row['leaveID'];

            $sql = "UPDATE leavebalance SET `balance` = 0  where employeeID=$empID and leaveID=$leaveID ";
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                $_SESSION['response_message'] = serialize([ "Error Occured during Deducting Balance" , "ERROR"]);

                // Redirect back to addLeave.php
                header("Location: manageEmployees.php");
                exit();
            }

            $sql = "UPDATE departments SET `deptHod` = NULL  where `deptId`= ( SELECT deptID from employees where `employeeID`=$empID and `role`='$HOD' )  ";
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                $_SESSION['response_message'] = serialize([ "Error Occured during Setting Department HOD as NULL" , "ERROR"]);

                // Redirect back to addLeave.php
                header("Location: manageEmployees.php");
                exit();
            }

        
        }

        $sql = "UPDATE employees SET `status` ='$emp_INACTIVE', `deactivationDate` = current_timestamp()  where employeeID=$empID";

        $result =  mysqli_query( $conn , $sql);

        if( !$result ){
            $_SESSION['response_message'] = serialize([ "Error Occured during Updating Employee" , "ERROR"]);

            // Redirect back to addLeave.php
            header("Location: manageEmployees.php");
            exit();
            
        }

    }


    $_SESSION['response_message'] = serialize([ "Employee Set to $status Successfully" , "ERROR"]);
    // Redirect back to addLeave.php
    header("Location: manageEmployees.php");
    exit();

?>