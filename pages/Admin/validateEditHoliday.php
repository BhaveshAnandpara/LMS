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
    $currDate =  $_GET['currDate'];
    $date =  $_POST['holidayDate'];
    $name =  $_POST['holidayName'];

?>


<?php

try{



      //Check Whether Name and Alias is empty or not
      if ( empty($date) ) {
        throw new Exception("Holiday Date cannot be Empty");
    }
    else if ( empty($name) ) {
        throw new Exception("Holiday Name cannot be Empty");
    }
    
    
    $holidayDate =  $date  ;
    $holidayName =  $name ;

  
    $holidayName = trim(ucwords(strtolower($holidayName))); // lowercase -> captitalized -> trim spaces

    //Check if Department Name and Alias Exists
    $sql = "Select date from holidays";
    $conn = sql_conn();
    $result =  mysqli_query( $conn , $sql);
    
    while( $row = mysqli_fetch_assoc($result) ){
        
        if(  $currDate != $date && strtotime($row['date']) == strtotime($holidayDate) ){
            throw new Exception("Holiday at this date Already Exits");
            
        }
        
    }
    
    //Query to insert holiday data into holidays
    $sql = "UPDATE `holidays` SET `date`='$holidayDate' , `holidayName`='$holidayName' where date='$currDate' ";
    $result =  mysqli_query( $conn , $sql);
    
    if( !$result ){
        
        throw new Exception("Errro Occured");
        
    }
    
    // Set a session variable with the response message
    $_SESSION['response_message'] = serialize(["Holiday Updated Successfully" , "SUCCESS"]);

    // Redirect back to addLeave.php
    header("Location: editHoliday.php?date=$date&name=$name");
    exit();

}
catch(Exception $e){

    $errorMessage = $e->getMessage();
    // echo $errorMessage;

    // Set a session variable with the response message
    $_SESSION['response_message'] = serialize([$errorMessage , "ERROR"]);

    // Redirect back to addLeave.php
    header("Location: editHoliday.php?date=$date&name=$name");
    exit();
    
}




?>