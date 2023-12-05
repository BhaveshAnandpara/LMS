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

try{

    //Check Whether Name and Desc is empty or not
    if ( empty($_POST['empName']) ) {
        echo Utils::alert("Employee Name cannot be Empty", "ERROR");
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

    $conn = sql_conn();
   
       // ----------------------------------- Validations  ----------------------------------- //
       
   
       //Validate Name
       $empName = trim(ucwords(strtolower($empName)));

       //Validate Role
       if( $role == $PRINCIPAL ){
   
           $sql = " Select employeeID  from employees where role='$PRINCIPAL' and status='$employee_ACTIVE' ";
           
           $roleResult =  mysqli_query( $conn , $sql);
       
           if( !$roleResult ) {
               Utils::alert("Opertaion Failed", "ERROR");
               throw new Exception("Error Occured During Validation of Role");
           }   
           
           $roleResult =  mysqli_fetch_assoc($roleResult);

           print_r( $roleResult );
           echo "</br>";
           
           if( !empty($roleResult['employeeID']) ){
       
               $msg = $role." Already Exists";

               $alert = Utils::alert($msg, "ERROR"); 
               print_r( $alert ); //! don't remove this don't know why but it is working only when storing it in variabale
               throw new Exception("$role Already Exists");
       
           }

       }
       else if( $role == $HOD ){
   
           $sql = " Select deptHOD from departments where deptID=$deptID ";

           $roleResult =  mysqli_query( $conn , $sql);
       
           if( !$roleResult ) {
               Utils::alert("Opertaion Failed", "ERROR");
               throw new Exception("Error Occured During Validation of Role");
           }   
           
           $roleResult =  mysqli_fetch_assoc($roleResult);
           
           if( !empty($roleResult['deptHOD']) ){
       
               $msg = $role." Already Exists";
               $alert = Utils::alert($msg, "ERROR"); 
               print_r( $alert ); //! don't remove this don't know why but it is working only when storing it in variabale
               throw new Exception("$role Already Exists");
       
           }
           
    }
   
    
    //Query to update employee data into employee table

    $sql = "UPDATE employees SET  `fullName` = '$empName', `deptID` = '$deptID', `type`='$type', `role`= '$role'  where employeeID=$employeeID";    
    
    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {
        Utils::alert("Opertaion Failed", "ERROR");
        throw new Exception("Error Occured During Query Updation");
    }
    else{

        echo Utils::alert("Employee Updated Successfully", "SUCCESS");
        echo "<script>
            window.location.href = './manageEmployees.php'
        </script>";

        }


    }
        


    
catch(Exception $e){

    echo $e;

    echo "<script>
        window.location.href = './manageEmployees.php'
    </script>";
    
}




?>