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
    if ( empty($_POST['deptName']) ) {
        echo Utils::alert("Department Name cannot be Empty");
        throw new Exception("Department Name cannot be Empty");
    }

    else if ( empty($_POST['deptAlias']) ) {
        echo Utils::alert("Department Alias cannot be Empty");
        throw new Exception("Department Alias cannot be Empty");
    }


    //Get the Data
    $deptID =  $_GET['deptID'] ;
    $deptName =  $_POST['deptName'] ;
    $deptAlias =  $_POST['deptAlias'] ;
    $deptHOD =  $_POST['deptHOD']; //Gives undefined if not selected but whatever

    //If Null Assign NULL Values
    if( empty($deptHOD) ) $deptHOD = "NULL";


    // ----------------------------------- Validate Department Name ----------------------------------- //
    
    //Capitalized

    $deptName = trim(ucwords(strtolower($deptName))); // lowercase -> captitalized -> trim spaces


    //Check if leave Exists
    $sql = "Select deptName from departments EXCEPT ( Select deptName from departments where deptID=$deptID )";
    $conn = sql_conn();
    $deptNameResult =  mysqli_query( $conn , $sql);
    

    while( $row = mysqli_fetch_assoc($deptNameResult) ){
        

        if( $row['deptName'] == $deptName ){
            echo Utils::alert("Department Name Already Exits");
            throw new Exception("Department Name Already Exits");
        }
        
        
    }


    //Query to insert Department data into masterdata
    $sql = "UPDATE departments SET `deptName`='$deptName', `deptAlias` = '$deptAlias', `deptHOD` = $deptHOD where deptID=$deptID";    

    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ) {
        Utils::alert("Opertaion Failed");
        throw new Exception("Error Occured During Query Updation");
    }
    else{

        echo Utils::alert("Department Updated Successfully");
        echo "<script>
            window.location.href = './manageDepartment.php'
        </script>";

        }


    }
        


    
catch(Exception $e){

    echo $e;

    echo "<script>
        window.location.href = './manageDepartment.php'
    </script>";
    
}




?>