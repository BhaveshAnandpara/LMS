
<?php 
    //  Creates database connection 
    require "../includes/db.conn.php";
?>


<?php


    //  Autoloader to load PHP class files automatically 

    function autoloadModel($class) {

    if( $class == 'Config' ){
        $filename =  $_SERVER['DOCUMENT_ROOT']. "/LMS/utils/" . $class ."/". $class . ".class.php";
    }
    
    //For Faculties and HOD we have defined class in same Staff.class.php file
    else if( $class == Config::$_HOD_ || $class == Config::$_FACULTY_ ){
        $class = "Staff";
    }

    $filename =  $_SERVER['DOCUMENT_ROOT']. "/LMS/utils/" . $class ."/". $class . ".class.php";

    if (is_readable($filename)) {
        require $filename;
    }

    }

    spl_autoload_register("autoloadModel");

?>


<?php


    //   Auth Class to define all functions related to Authentication  

    Class Auth{

     /*
        @function "__contruct"
        @description "Contructor 
                        --> check's user is valid or not
                        --> creates instance of User Type and return all information
    */

        public function __construct( $email ) {

            //Establish Database Connection
            $conn = sql_conn();

            if( $this->isValidUser($conn , $email) ){


                if( $_SESSION['role'] == Config::$_ADMIN_ ){
                     $user = new Admin( $_SESSION['employeeID']);
                     echo json_encode($user);
                }
                else if( $_SESSION['role'] == Config::$_HOD_ ){
                    return $user = new HOD( $_SESSION['employeeID']);
                }
                else if( $_SESSION['role'] == Config::$_PRINCIPAL_ ){
                    return $user = new Principal( $_SESSION['employeeID']);
                }
                else if( $_SESSION['role'] == Config::$_FACULTY_ ){
                    return $user = new Faculty( $_SESSION['employeeID']);
                }
                else{
                    return $user = new Staff( $_SESSION['employeeID']);
                }


            }
            

             
        }

        /*
        @function "isValidUser"
        @description "Check Whether the user is valid or not"
        @returns { return true or false} 
        */

        public function isValidUser( $conn , $email ){

            // SQL Query to check whether user with this email exists or not
            $sql = "SELECT * FROM employees where email='$email' ";

            $result =  mysqli_query( $conn , $sql);

            // Error Handling
            if ( !$result ) {
                echo("Error description: " . mysqli_error($con));
                return false;
            }else{

                $row = mysqli_fetch_array($result); // Response


                if( !empty( $row ) ){
    
                    //Starting the Session
                    session_start();
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['employeeID'] = $row['employeeID'];
                    $_SESSION['email'] = $row['email'];
                    
                }

                 return true;
            }




        }


    }


?>


<?php
    //  Check for fucntion name and execute code


    if( isset( $_POST['function'] )  ){ //Check whether Function exists or not
        
        if( $_POST['function'] == "validateUser" ){

            return setUser();

        }

    }

?>


<?php  

     /*
        @function "setUser"
        @description "setUser 
                        --> creates instance of Auth
        */

    function setUser(){

        try{

            // email from POST request
            $email = $_POST['email'];

            // creating a new obj of user
            $newUser = new Auth($email);

        }catch(Exception $err){
            return $err;
        }

    }

?>