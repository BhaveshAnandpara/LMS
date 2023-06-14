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

    //Check Whether Name and Alias is empty or not
    if ( empty($_POST['deptName']) ) {
        echo Utils::alert("Department Name cannot be Empty");
        throw new Exception("Department Name cannot be Empty");
    }
    else if ( empty($_POST['deptAlias']) ) {
        echo Utils::alert("Department Alias cannot be Empty");
        throw new Exception("Department Alias cannot be Empty");
    }

    //Get the Data
    $deptName =  $_POST['deptName'] ;
    $deptAlias =  $_POST['deptAlias'] ;
    $deptHOD =  $_POST['deptHOD'] ; //Gives undefined if not selected but whatever


    //If Null Assign NULL Values
    if( empty($deptHOD) ) $deptHOD = "NULL";


    // ----------------------------------- Validate Department Name ----------------------------------- //
    
    //Capitalized

    $deptName = trim(ucwords(strtolower($deptName))); // lowercase -> captitalized -> trim spaces

    //Check if Department Name and Alias Exists
    $sql = "Select deptName , deptAlias from departments";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);
    
    while( $row = mysqli_fetch_assoc($result) ){
        
        if( $row['deptName'] == $deptName ){
            echo Utils::alert("Department Name Already Exits");
            throw new Exception("Department Name Already Exits");
        }
        if( $row['deptAlias'] == $deptAlias ){
            echo Utils::alert("Department Alias Already Exits");
            throw new Exception("Department Alias Already Exits");
        }
        
        
    }
    
    //Query to insert dept data into departments
    $sql = "INSERT INTO `departments` (`deptID`, `deptName`, `deptAlias`, `deptHOD`) VALUES (NULL, '$deptName', '$deptAlias', $deptHOD);";

    
    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {
        Utils::alert("Opertaion Failed");
        throw new Exception("Error Occured During Query Insertion");
    }
    else{

        echo Utils::alert("Department Added Successfully");

        $time = date( 'Y-m-d H:i:s' , time());


        if( $deptHOD != "NULL" ) {


        // 1. Send Notification to Employee
        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$deptHOD', 'You have been Assigned new role : HOD of $deptName', '$time' );";
        $conn = sql_conn();
        $result =  mysqli_query( $conn , $sql);
        if( !$result ){
            echo "Error Occured During Insertion of ". $deptHOD ."  Notification";
        }


        //Send Notification to Admin
        $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$user->employeeId', '$deptName has been assigned a new HOD.<a href=./manageDepartment.php > View Details </a>', '$time' );";
        $conn = sql_conn();
        $result =  mysqli_query( $conn , $sql);
        if( !$result ){
            echo "Error Occured During Insertion of Notification";
        }

    }

    echo "<script>
        window.location.href = './manageDepartment.php'
    </script>";
    exit(0);

    }



    
}
catch(Exception $e){

    echo $e;

    echo "<script>
        window.location.href = './addDept.php'
    </script>";
    
}




?>