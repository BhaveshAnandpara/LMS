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

    echo Utils::alert( "Are you Sure ! You want to change the Status " );

    if( $user->setLeaveInactive( $_GET['leaveId'] , $_GET['status'] ) ){ echo Utils::alert("Leave set to " .$_GET['status']. " Successfully" , "SUCCESS"); }

    else{ echo Utils::alert("Opertaion Failed", "ERROR"); }

    echo 
    "<script>
        window.location.href='./manageMasterData.php'
    </script>";

?>