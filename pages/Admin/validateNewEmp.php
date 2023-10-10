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

try{

    //Check Whether Name and Desc is empty or not
    if ( empty($_POST['empEmail']) ) {
        echo Utils::alert("Employee Email cannot be empty" , "ERROR");
        throw new Exception("Employee Email cannot be empty ");
    }
    else if ( empty($_POST['empName']) ) {
        echo Utils::alert("Employee Name cannot be Empty" , "ERROR");
        throw new Exception("Employee Name cannot be Empty");
    }
    else if ( empty($_POST['joiningDate']) ) {
        echo Utils::alert("Joining Date cannot be Empty" , "ERROR");
        throw new Exception("Joining Date cannot be Empty");
    }

    //Get the Data
    $empEmail =  $_POST['empEmail'] ;
    $empName =  $_POST['empName'] ;
    $deptID =  $_POST['dept'] ;
    $joiningDate =  $_POST['joiningDate'] ;
    $type =  $_POST['type'] ;
    $role =  $_POST['role'] ;
    $status =  $_POST['status'] ;
    $inactiveDate =  $_POST['inactiveDate'] ;

    


    //If Null Assign NULL Values
    if( empty($deptID) ) $deptID = "NULL";
    if( empty($inactiveDate) ) $inactiveDate = "NULL";



    //DECLARATIONS OF CONSTANTS
    
    $transaction_PENDING = Config::$_TRANSACTION_STATUS['PENDING'];
    $transaction_FAILED = Config::$_TRANSACTION_STATUS['FAILED'];
    $transaction_SUCCESSFULL = Config::$_TRANSACTION_STATUS['SUCCESSFULL'];

    $employee_INACTIVE = Config::$_EMPLOYEE_STATUS['INACTIVE'];

    $masterdata_ACTIVE = Config::$_MASTERADTA_STATUS['ACTIVE'];
    $ADMIN = Config::$_ADMIN_;
    $PRINCIPAL = Config::$_EMPLOYEE_ROLE['PRINCIPAL'];
    $HOD = Config::$_EMPLOYEE_ROLE['HOD'];

    // ----------------------------------- Validations  ----------------------------------- //
    

    //Employee Email
    $empEmail = trim(strtolower($empEmail)); // lowercase -> trim spaces
    
    
    //Check whether entered email is bitwardha email or not
    if( !(str_contains( $empEmail , '@bitwardha.ac.in')) ){
        
        echo Utils::alert("Email is not recognized by Institution" , "ERROR");
        throw new Exception("Email is not recognized by Institution");
        
    }
    
    //Check if email already Exists
    $sql = "Select email from employees";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);
    
    while( $row = mysqli_fetch_assoc($result) ){
        
        
        if( $row['email'] == $empEmail ){
            echo Utils::alert("Employee with this Email Already Exists" ,"ERROR");
            throw new Exception("Employee with this Email Already Exists");
        }
        
        
    }

    //Validate Name
    $empName = trim(ucwords(strtolower($empName)));

    //Validate Role
    if( $role == $PRINCIPAL ){

        $sql = " Select COUNT(employeeID) as count from employees where role='$PRINCIPAL' ";
        
    }
    else if( $role == $HOD ){

        $sql = " Select deptHOD as count from departments where deptID=$deptID ";

    }

    $roleResult =  mysqli_query( $conn , $sql);
    
    if( !$roleResult ) {
        Utils::alert("Opertaion Failed" , "ERROR");
        throw new Exception("Error Occured During Validation of Role");
    }   
    
    $roleResult =  mysqli_fetch_assoc($roleResult);
    
    if( $roleResult['count'] != 0 ){

        $msg = $role." Already Exists";
        Utils::alert($msg , "ERROR"); //! not working ( Don't know why )
        throw new Exception("$role Already Exists");

    }
    

    //Valildate Status and Inactive Date

    if( empty($status) ) {
        $status = Config::$_EMPLOYEE_STATUS['ACTIVE'];
        $inactiveDate = "NULL";
    }else{
        $status = Config::$_EMPLOYEE_STATUS['INACTIVE'];
    }


    
    //Query to insert employee info
    $sql = "INSERT INTO `employees` (`employeeID`, `email`, `fullName`, `deptID`, `joiningDate`, `deactivationDate`, `type`, `role`, `status`) VALUES (NULL, '$empEmail', '$empName', '$deptID', '$joiningDate', '$inactiveDate', '$type', '$role', '$status')";   
    
    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {

        Utils::alert("Opertaion Failed" , "ERROR");
        throw new Exception("Error Occured During Query Insertion");

    }
    else{

            echo Utils::alert("Employee Added Successfully" , "SUCCESS");

            //------------------------------ Main Logic  ------------------------------//

            // 1. Get EmployeeID
            $sql = "Select * from employees where email='$empEmail'";
            $conn = sql_conn();
            $result =  mysqli_fetch_assoc( mysqli_query( $conn , $sql) );
            
            $employeeID = $result['employeeID'];

            if( $result['status'] == $employee_INACTIVE ){

                    echo "<script>
                        window.location.href = './addEmp.php'
                    </script>";

                exit(0);

            }
            
            // 2. for every Leave Type
            $leaveTypes = Utils::getLeaveTypes();

            while( $row = mysqli_fetch_assoc($leaveTypes) ){

                $leaveID = $row['leaveID'];
                $leaveType = $row['leaveType'];

                // 3. Start A Transaction
                $sql = "INSERT INTO leavetransactions (`transactionID`, `applicantID`, `leaveID`, `date`, `reason`, `status`, `balance`) VALUES (NULL, $employeeID , $leaveID , current_timestamp(), 'Intial Balance of $leaveType : 0 Leaves', '$transaction_PENDING', '0' );";
                $conn = sql_conn();

                $result =  mysqli_query( $conn , $sql);

                if( !$result ){ //If transaction fails
                    echo Utils::alert(" Error Occured during ". $row['fullName']. "Transaction " , "ERROR");
                }

                // 4. Get Transaction ID
                $sql = "Select * from leavetransactions where applicantID=$employeeID and leaveID=$leaveID and status = '$transaction_PENDING'";
                $conn = sql_conn();
                $result =   mysqli_fetch_assoc(mysqli_query( $conn , $sql));

                $transactionID = $result['transactionID'];

                // 5. Insert Balance
                $sql = "INSERT INTO leavebalance (`employeeID`, `leaveID`, `leaveType`, `balance`, `leaveCounter`, `lastUpdatedOn`) VALUES ( $employeeID , $leaveID , '$leaveType' , '0', '0', $transactionID )";

                $conn = sql_conn();
                $insertBalance = mysqli_query( $conn , $sql);

                //Error Handling
                if( !$insertBalance ) {
                    //Set transaction as Failed
                    $sql = "Update leavetransactions set status='$transaction_FAILED' where applicantID=$employeeID and leaveID=$leaveID and status = '$transaction_PENDING'";
                    $conn = sql_conn();
                    $result = mysqli_query( $conn , $sql);
                    echo "Error Occured during Adding New Balances";
                    
                }
                
                else{
                    
                    // 6.Set transaction as Successfull
                    $sql = "Update leavetransactions set status='$transaction_SUCCESSFULL' where applicantID=$employeeID and leaveID=$leaveID and status = '$transaction_PENDING'";
                    $conn = sql_conn();
                    $result = mysqli_query( $conn , $sql);

                }

                
                echo "<script>
                    window.location.href = './addEmp.php'
                </script>";


            
            }

    }
}
catch(Exception $e){

    echo $e;

    echo "<script>
        window.location.href = './addEmp.php'
    </script>";
    
}




?>