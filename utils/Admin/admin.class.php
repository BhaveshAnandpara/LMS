
<?php

    //Admin Class to define all functions related to Admin


    Class Admin{

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
        @description "Contructor 
                        --> get user data and set values
                        
         */
        public function __construct( $employeeID ){

            //Establish Connection
            $conn = sql_conn();
            
            //Run a Query
            $getUserInfo = "Select * from employees where employeeID = $employeeID";
            $result =  mysqli_query( $conn , $getUserInfo);

            // Error Handling
            if ( !$result ) {
                echo("Error description: " . mysqli_error($con));
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


         /*
        @function "getTotalEmployee"
        @description "getTotalEmployee
                        --> get the count of total Active employees 
                        
         */

        public function getTotalEmployee(){

            $ACTIVE = Config::$_EMPLOYEE_STATUS['ACTIVE'] ;

            // SQL Query to get the count of all active employees
            $sql = "SELECT COUNT(employeeID) from employees Where status='$ACTIVE' ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ) echo("Error description: " . mysqli_error($con));

            $rows = mysqli_fetch_assoc($result); // Response

            return $rows['COUNT(employeeID)'];

        }

                 /*
        @function "getTotalDepartment"
        @description "getTotalDepartment
                        --> get the count of total Departments
                        
         */
        
         public function getTotalDepartment(){

            // SQL Query to get the count of all departments
            $sql = "SELECT COUNT(deptID) from departments";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ) echo("Error description: " . mysqli_error($con));

            $rows = mysqli_fetch_assoc($result); // Response

            return $rows['COUNT(deptID)'];

        }


        /*
        @function "getTotalLeaveTypes"
        @description "getTotalLeaveTypes
                        --> get the count of total Leave Types
                        
         */
        
         public function getTotalLeaveTypes(){

            $status = Config::$_MASTERADTA_STATUS['ACTIVE'] ;

            // SQL Query to get the count of all leave types
            $sql = "SELECT COUNT(leaveID) from masterdata where status='$status' ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ) echo("Error description: " . mysqli_error($con));

            $rows = mysqli_fetch_assoc($result); // Response

            return $rows['COUNT(leaveID)'];

        }
        
                /*
        @function "getTotalTeachingStaff"
        @description "getTotalTeachingStaff
                        --> get the count of total Teaching Staff
                        
         */
        
         public function getTotalTeachingStaff(){

            $status = Config::$_MASTERADTA_STATUS['ACTIVE'] ;
            $type = Config::$_EMPLOYEE_TYPE['TEACHING_STAFF'] ;

            // SQL Query to get the count of all teaching staff
            $sql = "SELECT COUNT(employeeID) from employees where status='$status' and type='$type' ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ) echo("Error description: " . mysqli_error($con));

            $rows = mysqli_fetch_assoc($result); // Response

            return $rows['COUNT(employeeID)'];

        }


                        /*
        @function "getTotalNonTeachingStaff"
        @description "getTotalNonTeachingStaff
                        --> get the count of total Non Teaching Staff
                        
         */
        
         public function getTotalNonTeachingStaff(){

            $status = Config::$_MASTERADTA_STATUS['ACTIVE'] ;
            $type = Config::$_EMPLOYEE_TYPE['NON_TEACHING_STAFF'] ;

            // SQL Query to get the count of all non teaching staff
            $sql = "SELECT COUNT(employeeID) from employees where status='$status' and type='$type' ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ) echo("Error description: " . mysqli_error($con));

            $rows = mysqli_fetch_assoc($result); // Response

            return $rows['COUNT(employeeID)'];

        }


                                /*
        @function "getTotalDeactivatedStaff"
        @description "getTotalDeactivatedStaff
                        --> get the count of Total Deactivated Staff
                        
         */
        
         public function getTotalInactiveStaff(){

            $status = Config::$_MASTERADTA_STATUS['INACTIVE'] ;

            // SQL Query to get the count of all inactive employees
            $sql = "SELECT COUNT(employeeID) from employees where status='$status' ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( !$result ) echo("Error description: " . mysqli_error($con));

            $rows = mysqli_fetch_assoc($result); // Response

            return $rows['COUNT(employeeID)'];

        }
        
        

    }


?>