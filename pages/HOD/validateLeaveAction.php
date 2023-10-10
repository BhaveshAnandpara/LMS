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
    require('../../utils/Staff/staff.class.php');

    //start session
    session_start();

    $user =  $_SESSION['user'];
    $applicationID = $_GET['id'];
    $action = $_GET['action'];

    $data =  mysqli_fetch_assoc( $user->viewDetailApplication($applicationID) );

?>

<?php

        try{

            $status = Config::$_APPLICATION_STATUS['PENDING'];
            $hodApproval = Config::$_HOD_STATUS['PENDING'];
            
            if( $action === 'APPROVE' ){
                
                $status = Config::$_APPLICATION_STATUS['APPROVED_BY_HOD'];
                $hodApproval = Config::$_HOD_STATUS['APPROVED'];

            }

            else if( $action === 'REJECT' ){
                
                $status = Config::$_APPLICATION_STATUS['REJECTED_BY_HOD'];
                $hodApproval = Config::$_HOD_STATUS['REJECTED'];

            }

            //Update the status of Adjustment
            $query = "UPDATE applications SET status='$status', hodApproval = '$hodApproval' WHERE applicationID = $applicationID";

            print_r( $query );

            $conn = sql_conn();
            $result = mysqli_query($conn, $query);

            if( !$result ){
                echo Utils::alert("Errro Occured");
            }

            // Insert instance into notification table for applicant id

            $empID = $data['employeeID'];

            $time = date( 'Y-m-d H:i:s' , time());

            $sql = "INSERT INTO notifications (`employeeID`, `notification`, `dateTime`) VALUES ('$empID', 'Your Application has been $hodApproval by department HOD ', '$time' );";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ){
                echo "Error Occured During Insertion of ". $empID ."  Notification";
            }


            
            


            echo "<script>
                window.location.href = './leave_request.php'
            </script>";

    }catch( Exception $e){
                
        Utils::alert( "Error Occured" );

        print_r( $e );

    }

?>


