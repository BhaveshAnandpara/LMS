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

    if( $user->setLeaveInactive( $_GET['leaveId'] , $_GET['status'] ) ){ 
        
        // Set a session variable with the response message
        $_SESSION['response_message'] = serialize([ "Leave set to " .$_GET['status']. " Successfully" , "SUCCESS"]);

        // Redirect back to addLeave.php
        header("Location: manageMasterData.php");
        exit();
    }
    else{ 
            // Set a session variable with the response message
            $_SESSION['response_message'] = serialize([ "Opertaion Failed" , "ERROR"]);

            // Redirect back to addLeave.php
            header("Location: manageMasterData.php");
            exit();
    }

?>