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


        // ------------------------------------ Manage Master Data ------------------------------------ //


        /*
        @function "getMasterData"
        @description "getMasterData
                        --> get all leave types and data
                        
         */
        public function getMasterData(){

            // SQL Query to get masterdata Info
            $sql = 
            "(SELECT * from masterdata where carryForwardInto IS NULL
             UNION
             SELECT A.leaveID , A.leaveType , A.leaveDesc , A.cycleDate , A.leaveInterval , A.increment ,B.leaveType as carryForwardInto , A.balanceLimit , A.applyLimit , A.waitingTime , A.status from masterdata as A , masterdata as B where A.carryForwardInto = B.leaveID ) ORDER BY status";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            return $result ;

        }

         /*
        @function "setLeaveInactive"
        @description "setLeaveInactive
                        --> Set Leave as Inactive
                        
         */
        public function setLeaveInactive($leaveID , $status ){

            // SQL Query to get masterdata Info
            $sql = " Update masterdata set status='$status' where leaveID=$leaveID ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            if( $result ) return true;
            else return false;


        }


    }


?>