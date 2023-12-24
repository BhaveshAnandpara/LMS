<?php 
    ob_start();
    
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

try{

    //Check Whether Name and Desc is empty or not
    if ( empty($_POST['empName']) ) {
        throw new Exception("Employee Name cannot be Empty");
    }

    //Get the Data
    $employeeID =  $_GET['empID'] ;
    $empName =  $_POST['empName'] ;
    $deptID =  $_POST['dept'] ;
    $type =  $_POST['type'] ;
    $role =  $_POST['role'] ;

    

    //DECLARATIONS OF CONSTANTS

    $employee_INACTIVE = Config::$_EMPLOYEE_STATUS['INACTIVE'];
    $employee_ACTIVE = Config::$_EMPLOYEE_STATUS['ACTIVE'];

    $masterdata_ACTIVE = Config::$_MASTERADTA_STATUS['ACTIVE'];
    $ADMIN = Config::$_ADMIN_;
    $PRINCIPAL = Config::$_EMPLOYEE_ROLE['PRINCIPAL'];
    $HOD = Config::$_EMPLOYEE_ROLE['HOD'];
    $FACULTY = Config::$_EMPLOYEE_ROLE['FACULTY'];

    $conn = sql_conn();
   
       // ----------------------------------- Validations  ----------------------------------- //
       
   
       //Validate Name
       $empName = trim(ucwords(strtolower($empName)));

       //Validate Role
       if( $role == $PRINCIPAL ){
   
           $sql = " Select employeeID  from employees where role='$PRINCIPAL' and status='$employee_ACTIVE' ";
           
           $roleResult =  mysqli_query( $conn , $sql);
       
           if( !$roleResult ) {
               throw new Exception("Opertaion Failed");
           }   
           
           $roleResult =  mysqli_fetch_assoc($roleResult);

           if( !empty($roleResult['employeeID']) && $roleResult['employeeID'] !== $employeeID ){
       
               $msg = $role." Already Exists";
               throw new Exception("$role Already Exists");
       
           }

       }
       else if( $role == $HOD ){
   
           $sql = " Select deptHOD from departments where deptID=$deptID ";

           $roleResult =  mysqli_query( $conn , $sql);
       
           if( !$roleResult ) {
               throw new Exception("Opertaion Failed");
           }   
           
           $roleResult =  mysqli_fetch_assoc($roleResult);
           
           if( !empty($roleResult['deptHOD']) && $roleResult['deptHOD'] !== $employeeID ){
       
               throw new Exception("$role Already Exists");
       
           }else{

                $updateRole = "UPDATE `departments` SET `deptHod` = '$employeeID' WHERE deptID = $deptID";
                $updateResult =  mysqli_query( $conn , $updateRole);     

           }
           
    }
    else if(  $role == $FACULTY) {

        $sql = " Select deptHOD from departments where deptID=$deptID ";

        $roleResult =  mysqli_query( $conn , $sql);
    
        if( !$roleResult ) {
            throw new Exception("Opertaion Failed");
        }   
        
        $roleResult =  mysqli_fetch_assoc($roleResult);
        
        if( $roleResult['deptHOD']  == $employeeID ){

            $updateRole = "UPDATE `departments` SET `deptHod` = NULL WHERE deptID = $deptID";
            $updateResult =  mysqli_query( $conn , $updateRole);     

        }

    }
   
    
    //Query to update employee data into employee table

    $sql = "UPDATE employees SET  `fullName` = '$empName', `deptID` = '$deptID', `type`='$type', `role`= '$role'  where employeeID=$employeeID";    
    
    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {
        throw new Exception("Opertaion Failed");
    }
    else{

        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize(["Employee Edited Successfully" , "SUCCESS"]);
        
        header("Location: manageEmployees.php");
        exit();

        }


    }
catch(Exception $e){

        $errorMessage = $e->getMessage();
        echo $errorMessage;

        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);

        header("Location: editEmp.php?empID=$employeeID");
        exit();
    
}



ob_end_flush();
?>