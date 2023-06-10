<?php

    Class Staff{
        public $email;
        public $fullName;
        public $deptID;
        public $joiningDate;
        public $deactivationDate;
        public $type;
        public $role;
        public $status;
        //Methods will written over here
        // e.g methods for staff apply ( same for both HOD and staff )

        /**
         * Class constructor.
         */
        public function __construct($employeeID)
        {
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
    
                $this->email = $row['email'];
                $this->fullName = $row['fullName'];
                $this->deptID = $row['deptID'];
                $this->joiningDate = $row['joiningDate'];
                $this->deactivationDate = $row['deactivationDate'];
                $this->type = $row['type'];
                $this->role = $row['role'];
                $this->status = $row['status'];
        

        }

         
        public function getLeaveTypes(){

            $status = Config::$_MASTERADTA_STATUS['ACTIVE'] ;

            // SQL Query to get the count of all leave types

             $sql1 = "SELECT * FROM leavebalance INNER JOIN employees ON employees.employeeId = leavebalance.employeeId where email = '$this->email'"; 
             $conn = sql_conn();  
             $result = mysqli_query($conn, $sql1) or die("result failed in table");
                
            // $sql = "SELECT * from masterdata where status='$status' ";
            // $conn = sql_conn();
            // $result =  mysqli_query( $conn , $sql);

            if( !$result ) echo("Error description: " . mysqli_error($conn));

            $rows = mysqli_fetch_assoc($result); // Response

            return $rows['$result'];

        }

        // public function getLeaveBalance(){

        //     $status = Config::$_MASTERADTA_STATUS['ACTIVE'] ;

        //     // SQL Query to get the count of all leave types
        //     $sql = "SELECT * from masterdata where status='$status' ";
        //     $conn = sql_conn();
        //     $result =  mysqli_query( $conn , $sql);

        //     if( !$result ) echo("Error description: " . mysqli_error($con));

        //     $rows = mysqli_fetch_assoc($result); // Response

        //     return $rows['$result'];

        // }


    }



?>


<?php


    Class Faculty extends Staff{

        //Methods will written over here 
        // e.g methods for staff apply ( same for both HOD and staff )

        /*
        @function "getTotalLeaveTypes"
        @description "getTotalLeaveTypes
                        --> get the count of total Leave Types
                        
         */
       


    }


?>