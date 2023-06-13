<?php



    Class Config{

        //Roles
        public static $_ADMIN_ = "ADMIN";
        public static $_HOD_ = "HOD";
        public static $_PRINCIPAL_ = "PRINCIPAL";
        public static $_FACULTY_ = "FACULTY";
        
        
        //Employee Type
        public static $_EMPLOYEE_TYPE  = array('TEACHING_STAFF' => "TEACHING_STAFF", 'NON_TEACHING_STAFF' => "NON_TEACHING_STAFF" );

        //Employee Status
        public static $_EMPLOYEE_STATUS  = array('ACTIVE' => "ACTIVE", 'INACTIVE' => "INACTIVE" );

        //Leave Status
        public static $_MASTERADTA_STATUS  = array('ACTIVE' => "ACTIVE", 'INACTIVE' => "INACTIVE" );

        //Transaction Status
        public static $_TRANSACTION_STATUS  = array('PENDING' => "PENDING", 'SUCCESSFULL' => "SUCCESSFULL", 'FAILED' => "FAILED" );
    

    }




?>