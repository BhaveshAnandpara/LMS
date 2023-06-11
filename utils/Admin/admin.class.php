
<?php

    //Admin Class to define all functions related to Admin


    Class Admin{

        public $employeeId;
        public $email;
        public $fullName;
        public $deptID;
        public $joiningDate;
        public $deactivationDate;
        public $type;
        public $role;
        public $status;

         /*
            @function "__contruct"
            @description "Contructor  --> get user data and set values
                        
         */
        
        public function __construct( $employeeID ){

            //Establish Connection
            $conn = sql_conn();
            
            //Run a Query
            $getUserInfo = "Select * from employees where employeeID = $employeeID";
            $result =  mysqli_query( $conn , $getUserInfo);

            // Error Handling
            if ( !$result ) {
                echo("Error description: " . mysqli_error($conn));
                return false;
            }

            $row = mysqli_fetch_assoc($result); // Response

            $this->employeeId = $row['employeeID'];
            $this->email = $row['email'];
            $this->fullName = $row['fullName'];
            $this->deptID = $row['deptID'];
            $this->joiningDate = $row['joiningDate'];
            $this->deactivationDate = $row['deactivationDate'];
            $this->type = $row['type'];
            $this->role = $row['role'];
            $this->status = $row['status'];

        }


        // ------------------------------------ Dashboard ------------------------------------ //

         
        
        /*
        @function "getNotifications"
        @description "getNotifications
                        --> get all notifications
                        
         */
        
         public function getNotifications(){

            $employeeID = $this->employeeId;


            // SQL Query to get the count of all inactive employees
            $sql = "SELECT * from notifications where employeeID='$employeeID' Order By dateTime DESC";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ) echo("Error description: " . mysqli_error($con));

            return $result;

        }


    }


?>