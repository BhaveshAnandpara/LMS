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

    echo Utils::alert( "Are you Sure ! You want to delete the Department" );
    echo Utils::alert($user->deleteDept( $_GET['deptID'] ));

    echo 
    "<script>
        window.location.href='./manageDepartment.php'
    </script>";

?>