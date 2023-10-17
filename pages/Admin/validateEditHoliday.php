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
    $currDate =  $_GET['currDate'];
    $date =  $_POST['holidayDate'];
    $name =  $_POST['holidayName'];

?>


<?php

try{



      //Check Whether Name and Alias is empty or not
      if ( empty($date) ) {
        echo Utils::alert("Holiday Date cannot be Empty", "ERROR");
        throw new Exception("Holiday Date cannot be Empty");
    }
    else if ( empty($name) ) {
        echo Utils::alert("Holiday Name cannot be Empty", "ERROR");
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
        
        if(  strtotime($row['date']) == strtotime($holidayDate) ){

            echo Utils::alert("Holiday at this date Already Exits", "ERROR");
            throw new Exception("Holiday at this date Already Exits");

        }
        
    }
    
    //Query to insert holiday data into holidays
    $sql = "UPDATE `holidays` SET `date`='$holidayDate' , `holidayName`='$holidayName' where date='$currDate' ";
    $result =  mysqli_query( $conn , $sql);

    if( !$result ){

        echo Utils::alert("Errro Occured", "ERROR");
        
    }
    
    echo Utils::alert("Holiday Updated", "SUCCESS");

    echo "<script>
        window.location.href = './editHoliday.php?date=$date&name=$name'
    </script>";

}
    
catch(Exception $e){

    echo $e;

    echo "<script>
        window.location.href = './manageHolidays.php'
    </script>";
    
}




?>