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
    if ( empty($_POST['holidayDate']) ) {
        throw new Exception("Holiday Date cannot be Empty");
    }
    else if ( empty($_POST['holidayName']) ) {
        throw new Exception("Holiday Name cannot be Empty");
    }


    $holidayDate =   $_POST['holidayDate'] ;
    $holidayName =  $_POST['holidayName'] ;

    $holidayName = trim(ucwords(strtolower($holidayName))); // lowercase -> captitalized -> trim spaces

    //Check if Department Name and Alias Exists
    $sql = "Select date from holidays";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);
    
    while( $row = mysqli_fetch_assoc($result) ){
        
        if(  strtotime($row['date']) == strtotime($holidayDate) ){

            throw new Exception("Holiday at this date Already Exits");

        }
        
    }
    
    //Query to insert holiday data into holidays
    $sql = "INSERT INTO `holidays` (`date`, `holidayName`) VALUES ('$holidayDate', '$holidayName');";
    $result =  mysqli_query( $conn , $sql);

    if( !$result ){

        throw new Exception("Error Occured");
        
    }
    
    // Set a session variable with the response message
    $_SESSION['response_message'] = serialize(["Holiday Added Successfully" , "SUCCESS"]);

    // Redirect back to addLeave.php
    header("Location: addHoliday.php");
    exit();


    
}
catch(Exception $e){

    $errorMessage = $e->getMessage();
    echo $errorMessage;

    // Set a session variable with the response message
    $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);

    // Redirect back to addLeave.php
    header("Location: addHoliday.php");
    exit();
    
}

ob_end_flush();

?>