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

            $sql = "Select * from masterdata ORDER BY leaveID ";
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

            $sql = "Select * from employees inner join departments on employees.deptID = departments.deptID";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);
            return $result ;


        }


        public static function getLeaveBalanceOfEmployee( $empID ){


            $sql = "SELECT employees.employeeID , masterdata.leaveType , leavebalance.balance from employees 
            JOIN
            masterdata 
            LEFT JOIN
            leavebalance on masterdata.leaveID = leavebalance.leaveID and employees.employeeID = leavebalance.employeeID where employees.employeeID = $empID";

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

            $sql = "Select * from employees where employeeID=$empID";
            $conn = sql_conn();
            $result =  mysqli_query( $conn , $sql);

            return $result ;

        }


        public static function alert( $msg ){

            return "<script> alert('" .$msg. "') </script>";

        }

    
    }


?>