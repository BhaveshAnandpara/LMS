<?php ob_start();
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

    //Check Whether Name and Alias is empty or not
    if ( empty($_POST['deptName']) ) {
        throw new Exception("Department Name cannot be Empty");
    }
    else if ( empty($_POST['deptAlias']) ) {
        throw new Exception("Department Alias cannot be Empty");
    }

    //Get the Data
    $deptName =  $_POST['deptName'] ;
    $deptAlias =  $_POST['deptAlias'] ;
    $deptHOD =  empty($_POST['deptHOD'])  ? "NULL" : $_POST['deptHOD'] ; //Gives undefined if not selected but whatever


    // ----------------------------------- Validate Department Name ----------------------------------- //
    
    //Capitalized

    $deptName = trim(ucwords(strtolower($deptName))); // lowercase -> captitalized -> trim spaces

    //Check if Department Name and Alias Exists
    $sql = "Select deptName , deptAlias from departments";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);
    
    while( $row = mysqli_fetch_assoc($result) ){
        
        if( $row['deptName'] == $deptName ){
            throw new Exception("Department Name Already Exits");
        }
        if( $row['deptAlias'] == $deptAlias ){
            throw new Exception("Department Alias Already Exits");
        }
        
        
    }
    

    //Query to insert dept data into departments
    $sql = "INSERT INTO `departments` (`deptID`, `deptName`, `deptAlias`, `deptHOD`) VALUES (NULL, '$deptName', '$deptAlias', $deptHOD);";
    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {
        throw new Exception("Opertaion Failed");
    }
    else{

        $time = date( 'Y-m-d H:i:s' , time());

        if( $deptHOD != "NULL" ) {


            //Set Faculty Role as HOD
            
            //1.Query to get DeptID
            $sql = "Select * from departments where deptName='$deptName' ";
            $deptresult = mysqli_fetch_assoc( mysqli_query( $conn , $sql) );
            
            $deptID = $deptresult['deptID'];
            
            $newRole = Config::$_HOD_;

            //2. Query to set faculty as HOD
            $sql =  "UPDATE employees SET `deptID` = '$deptID' , `role` = '$newRole' where employeeID=$deptHOD"; 
            $result =  mysqli_query( $conn , $sql);

            //3. Send Notification to Employee
            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$deptHOD', 'You have been Assigned new role : HOD of $deptName', '$time' );";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);
            if( !$result ){
                // echo "Error Occured During Insertion of ". $deptHOD ."  Notification";
            }

            //4. Send Notification to Admin
            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$user->employeeId', '$deptName has been assigned a new HOD.<a href=./manageDepartment.php > View Details </a>', '$time' );";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);
            if( !$result ){
                // echo "Error Occured During Insertion of Notification";
            }else{



            }

        }

                // Set a session variable with the response message
                $_SESSION['response_message'] = serialize(["Department Added Successfully" , "SUCCESS"]);

                // Redirect back to addLeave.php
                header("Location: addDept.php");
                exit();



    }



    
}
catch(Exception $e){

    $errorMessage = $e->getMessage();
    // echo $errorMessage;

    // Set a session variable with the response message
    $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);

    // Redirect back to addLeave.php
    header("Location: addDept.php");
    exit();
    
}



ob_end_flush();

?>