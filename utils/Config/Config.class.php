<?php


    Class Config{

        //Roles
        public static $_ADMIN_ = "ADMIN";
        public static $_HOD_ = "HOD";
        public static $_PRINCIPAL_ = "PRINCIPAL";
        public static $_FACULTY_ = "FACULTY";
        
        //Semesters
        public static $_SEMESTERS  = array('1 Sem' => "1", '2 Sem' => "2", '3 Sem' => "3" , '4 Sem' => "4", '5 Sem' => "5", '6 Sem' => "6",  '7 Sem' => "7",  '8 Sem' => "8" );

        //Employee Type
        public static $_EMPLOYEE_ROLE  = array( 'FACULTY' => "FACULTY" ,'ADMIN' => "ADMIN", 'HOD' => "HOD" , 'PRINCIPAL' => "PRINCIPAL" );

        //Employee Type
        public static $_EMPLOYEE_TYPE  = array('TEACHING_STAFF' => "TEACHING_STAFF", 'NON_TEACHING_STAFF' => "NON_TEACHING_STAFF" );

        //Employee Status
        public static $_EMPLOYEE_STATUS  = array('ACTIVE' => "ACTIVE", 'INACTIVE' => "INACTIVE" );

        //Department Status
        public static $_DEPT_STATUS  = array('ACTIVE' => "ACTIVE", 'INACTIVE' => "INACTIVE" );

        //Leave Status
        public static $_MASTERADTA_STATUS  = array('ACTIVE' => "ACTIVE", 'INACTIVE' => "INACTIVE" );

        //Transaction Status
        public static $_TRANSACTION_STATUS  = array('PENDING' => "INITIATED", 'SUCCESSFULL' => "SUCCESSFULL", 'FAILED' => "FAILED" );

        //HOD  Status
        public static $_HOD_STATUS  = array('PENDING' => "PENDING", 'APPROVED' => "APPROVED", 'REJECTED' => "REJECTED" );

        //Principal  Status
        public static $_PRINCIPAL_STATUS  = array('PENDING' => "PENDING", 'APPROVED' => "APPROVED", 'REJECTED' => "REJECTED" );

        //Application  Status
        public static $_APPLICATION_STATUS  = array('PENDING' => "PENDING", 'APPROVED_BY_HOD' => "APPROVED_BY_HOD", 'REJECTED_BY_HOD' => "REJECTED_BY_HOD", 'APPROVED_BY_PRINCIPAL' => "APPROVED_BY_PRINCIPAL", 'REJECTED_BY_PRINCIPAL' => "REJECTED_BY_PRINCIPAL", 'SANCTIONED' => "SANCTIONED", 'WITHDRAWN' => "WITHDRAWN", 'DEDUCTED_FROM_EL' => "DEDUCTED_FROM_EL", 'LEAVE_WITHOUT_PAY' => "LEAVE_WITHOUT_PAY"   );

        //Adjustment  Status
        public static $_ADJUSTMENT_STATUS  = array('PENDING' => "PENDING", 'ACCEPTED' => "ACCEPTED", 'REJECTED' => "REJECTED" , 'WITHDRAWN' => "WITHDRAWN", );

        //ComOff Types
        public static $_COMPOFF_TYPES  = array('Half Day' => "Half Day", 'Full Day' => "Full Day");



    }




?>