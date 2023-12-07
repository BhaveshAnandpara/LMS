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
    if ( empty($_POST['deptName']) ) {
        throw new Exception("Department Name cannot be Empty");
    }

    else if ( empty($_POST['deptAlias']) ) {
        throw new Exception("Department Alias cannot be Empty");
    }


    //Get the Data
    $deptID =  $_GET['deptID'] ;
    $deptName =  $_POST['deptName'] ;
    $deptAlias =  $_POST['deptAlias'] ;
    $deptHOD =  $_POST['deptHOD']; //Gives undefined if not selected but whatever



    // ----------------------------------- Validate Department Name ----------------------------------- //
    
    //Capitalized

    $deptName = trim(ucwords(strtolower($deptName))); // lowercase -> captitalized -> trim spaces


    //Check if department Exists

    $sql = "Select deptName from departments EXCEPT ( Select deptName from departments where deptID=$deptID )";
    $conn = sql_conn();
    $deptNameResult =  mysqli_query( $conn , $sql);
    

    while( $row = mysqli_fetch_assoc($deptNameResult) ){
        
        if( $row['deptName'] == $deptName ){
            throw new Exception("Department Name Already Exits");
        }
        
        
    }


    //Check if department alias Exists

    $sql = "Select deptAlias from departments EXCEPT ( Select deptAlias from departments where deptID=$deptID )";
    $conn = sql_conn();
    $deptAliasResult =  mysqli_query( $conn , $sql);
    

    while( $row = mysqli_fetch_assoc($deptAliasResult) ){
        
        if( $row['deptAlias'] == $deptAlias ){
            throw new Exception("Department Alias Already Exits");
        }
        
        
    }



    //Updating Current HOD role to faculty
    if( $deptHOD == "NULL" ){

        //Check if HOD exists

            //Check if departmentHOD Exists
            $sql = "Select deptHOD from departments where deptID=$deptID";
            $conn = sql_conn();
            $currDept = mysqli_fetch_assoc( mysqli_query( $conn , $sql) );

            if( !empty($currDept['deptHOD']) ){

                $hod = $currDept['deptHOD'];

                $employee_FACULTY = Config::$_EMPLOYEE_ROLE['FACULTY'];

                //Query to update currentHOD to faculty
                $sql = "UPDATE employees SET `role`='$employee_FACULTY' where employeeID=$hod"; 
                $result =  mysqli_query( $conn , $sql);

                if( !$result ) {
                    throw new Exception("Opertaion Failed");
                }

            }

    }


    //Query to insert Department data into masterdata
    $sql = "UPDATE departments SET `deptName`='$deptName', `deptAlias` = '$deptAlias', `deptHOD` = $deptHOD where deptID=$deptID";    

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
                echo "Error Occured During Insertion of ". $deptHOD ."  Notification";
            }

        }


        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize(["Department Edited Successfully" , "SUCCESS"]);
        header("Location: editDept.php?deptID=$deptID");
        exit();


        }


    }
        


    
catch(Exception $e){

        $errorMessage = $e->getMessage();
        echo $errorMessage;

        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);

        header("Location: editDept.php?deptID=$deptID");
        exit();
    
}




?>