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

    $res = $user->deleteDept( $_GET['deptID'] );

    if( $res  === "Department Removed Successfully" ){ 
        
        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize([ "Department deleted Successfully" , "SUCCESS"]);

        // Redirect back to addLeave.php
        header("Location: manageDepartment.php");
        exit();
    }
    else{ 
            // Set a session variable with the response message
            $_SESSION['response_message'] = serialize([ $res , "ERROR"]);

            // Redirect back to addLeave.php
            header("Location: manageDepartment.php");
            exit();
    }
?>