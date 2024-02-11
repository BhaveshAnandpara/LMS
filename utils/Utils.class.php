<?php
    

    class Utils{
    
        public static function getTimeDiff( $date ){

            //Get the Difference of Time and Show that
            $diff = round(abs( time() -  strtotime($date)) / 60 , 0);
            $time = 0;
            if( $diff < 60 ) $time = $diff. " Mins Ago";
            else if( $diff >= 60 && $diff < (24*60 ) ) $time = round( $diff/60 , 0 ). " Hrs Ago";
            else $time = round($diff/ 3600 , 0 ). " Days Ago";

            return $time;

        }
      

        public static function getLeaveTypes(){

            $ACTIVE = Config::$_MASTERADTA_STATUS['ACTIVE'];

            $sql = "Select * from masterdata where status='$ACTIVE' ORDER BY leaveID";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);


            return $result ;

        }

        public static function getLeaveDetails( $leaveID ){

            $sql = "Select * from masterdata where leaveID=$leaveID";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);


            return $result ;

        }


        public static function getAllEmployees(  ){

            $sql = "Select * from employees inner join departments on employees.deptID = departments.deptID ORDER BY Status";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);
            return $result ;


        }

        public static function getAllActiveEmployees(  ){

            $sql = "Select * from employees inner join departments on employees.deptID = departments.deptID where employees.status='ACTIVE' ORDER BY employees.fullName";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);
            return $result ;


        }

        public static function getLeaveBalanceOfEmployee( $empID ){

            $ACTIVE = Config::$_MASTERADTA_STATUS['ACTIVE'];
            $INACTIVE = Config::$_MASTERADTA_STATUS['INACTIVE'];

            $sql = "SELECT employees.employeeID , masterdata.leaveID , masterdata.leaveType , leavebalance.balance , leavebalance.leaveCounter , leavetransactions.date , leavetransactions.reason from 
			employees 
            JOIN
            masterdata 
            LEFT JOIN
            leavebalance on masterdata.leaveID = leavebalance.leaveID and employees.employeeID = leavebalance.employeeID 
            LEFT JOIN
            leavetransactions on leavebalance.lastUpdatedOn = leavetransactions.transactionID
            where employees.employeeID = $empID and masterdata.status = '$ACTIVE'";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);
            return $result ;

        }

        public static function getLeaveBalanceOfEmployeeForSpecificLeave( $empID , $leaveID ){

            $sql = "SELECT balance from leavebalance where employeeID='$empID' and leaveID='$leaveID'";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);
            return $result ;

        }

        public static function getLeaveDetailsOfEmployee( $empID , $leaveID ){


            $sql = "SELECT  employees.fullName ,  leavebalance.leaveID , leavebalance.leaveType , leavebalance.balance , leavebalance.leaveCounter , leavetransactions.reason , leavetransactions.date from leavebalance inner join employees on employees.employeeID = leavebalance.employeeID inner join leavetransactions on leavebalance.lastUpdatedOn = leavetransactions.transactionID where leavebalance.employeeID=$empID and leavebalance.leaveID =$leaveID;";

            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);
            return $result ;

        }
        
        public static function getEmpLeaveCounter( $empID ){

            $sql = "SELECT SUM( leaveCounter ) as count from leavebalance where employeeID=$empID ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            return $result ;

        }

        public static function getAllDepts(  ){

            $sql = "Select * from departments";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            return $result ;

        }        

        public static function getDeptDetails( $deptID ){

            $sql = "Select * from departments where deptID=$deptID";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            return $result ;

        }
      
        public static function getEmpDetails( $empID ){

            $sql = "Select * from employees inner join departments on employees.deptID = departments.deptID where employeeID=$empID";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            return $result ;

        }

        public static function getUpcomingHolidays( ){

            $time =  date( 'Y-m-d' , time() ) ;

            $sql = "Select * from holidays where holidays.date >= '$time' ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            return $result ;

        }

        public static function getElapsedHolidays( ){

            $time =  date( 'Y-m-d' , time() ) ;

            $sql = "Select * from holidays where holidays.date < '$time' ";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            return $result ;

        }  
        
        public static function viewDetailApplication( $id ){

            $sql = "SELECT applications.status as applicationStatus , applications.* , employees.* FROM `applications` inner join employees on applications.employeeID = employees.employeeID WHERE `applicationID` = $id";
    
            $conn = sql_conn();
            $result =  mysqli_query($conn, $sql);
            if (!$result) echo ("Error description: " . mysqli_error($conn));
    
            return $result;
    
        }

        public static function getApplicationLeaveData($appID)
        {
    
            // SQL Query to get the leave history of login employee
    
            $sql = "SELECT * from leavetype where applicationID=$appID";
    
            $conn = sql_conn();
            $result =  mysqli_query($conn, $sql);
    
            return $result;
        }

        public static function getEmployeeLeaveCalendar($empID)
        {
    
            // SQL Query to get the leave history of login employee
    
            $startCalendar =  date( 'Y-m-d' , strtotime('-15 days', time() ) ) ;
            $endCalendar =  date( 'Y-m-d' , strtotime('+15 days', time() ) ) ;
            $approved = Config::$_APPLICATION_STATUS['APPROVED_BY_PRINCIPAL'];
            $sanctioned = Config::$_APPLICATION_STATUS['SANCTIONED'];
            $dfel = Config::$_APPLICATION_STATUS['DEDUCTED_FROM_EL'];
            $lwp = Config::$_APPLICATION_STATUS['LEAVE_WITHOUT_PAY'];

            $sql = "SELECT startDate , endDate , totalDays from applications where employeeID=$empID and status='$approved' or  status='$sanctioned' or  status='$dfel' or  status='$lwp' and  applications.startDate <= '$endCalendar' or applications.endDate <= '$startCalendar' GROUP BY startDate ORDER BY startDate  ASC";
    
            $conn = sql_conn();
            $result =  mysqli_query($conn, $sql);
    
            return $result;
        }


        public static function alert( $msg  ,$title , $redirect){

            return "<script>  
            

                document.querySelector('.modal-body').innerHTML = '" .$msg. "';
                document.querySelector('.modal-title').innerHTML = '" .$title. "';

                document.querySelector('#close-btn').onclick = ()=>{
                    window.location.href = '" . $redirect . "'
                }

              
                $('#myModal').modal();
                
            </script>";

        }

        public static function confirm( $msg ){

            return "<script> confirm('" .$msg. "') </script>";

        }

    
    }


?>