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