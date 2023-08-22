<?php

class Staff
{

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
        @description "Contructor 
                        --> get user data and set values
                        
         */
    public function __construct($employeeID)
    {

        //Establish Connection
        $conn = sql_conn();

        //Run a Query
        $getUserInfo = "Select * from employees where employeeID = $employeeID";
        $result =  mysqli_query($conn, $getUserInfo);

        // Error Handling
        if (!$result) {
            echo ("Error description: " . mysqli_error($conn));
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

    /*
                @function "getNotifications"
                @description "getNotifications --> get all notifications
                        
         */

    public function getNotifications()
    {

        $employeeID = $this->employeeId;


        // SQL Query to get the count of all inactive employees
        $sql = "SELECT * from notifications where employeeID='$employeeID' Order By dateTime DESC";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);

        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }


    /*
                @function "getNotifications"
                @description "getNotifications --> get all notifications
                        
         */

    public function getCurrentBalance()
    {

        $employeeID = $this->employeeId;


        // SQL Query to get the count of all inactive employees
        $sql = "SELECT * from leavebalance inner join leavetransactions on leavebalance.lastUpdatedOn = leavetransactions.transactionID where employeeID='$employeeID'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);

        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }


    // ------------------------------------ Leave History ------------------------------------ //

    public function recentlyAppliedLeave( $limit )
    {
        $employeeID = $this->employeeId;

        // SQL Query to get the leave history of login employee
        
        $sql = "SELECT applications.applicationID , applications.dateTime , applications.startDate , applications.endDate , applications.totalDays , applications.reason , applications.hodApproval , applications.principalApproval , applications.status From applications where employeeID=$employeeID ORDER BY applications.dateTime DESC LIMIT $limit";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }


    public function getAppLeaveTypes( $appID )
    {

        // SQL Query to get the leave history of login employee

        $sql = "SELECT leavetype.leaveType from leavetype where applicationID=$appID";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);

        if (!$result) echo ("Error description: " . mysqli_error($conn));

        $ans = "";

        while( $row = mysqli_fetch_assoc($result) ){

            $ans = $ans. " + " .$row['leaveType'];

        }

        $ans = substr( $ans , 2 )."";

        return $ans;

    }


    public function incomingApplications( )
    {
        $employeeID = $this->employeeId;

        // SQL Query to get the leave history of login employee
        
        $curr = date( 'Y-m-d' , time() );

        $sql = "SELECT applications.applicationID , applications.dateTime , applications.startDate , applications.endDate , applications.totalDays , applications.reason , applications.extension , applications.hodApproval , applications.principalApproval , applications.status From applications where employeeID=$employeeID and applications.startDate >= '$curr' ORDER BY applications.dateTime DESC";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }


    public function elapsedApplications( )
    {
        $employeeID = $this->employeeId;

        // SQL Query to get the leave history of login employee
        
        $curr = date( 'Y-m-d' , time() );

        $sql = "SELECT applications.applicationID , applications.dateTime , applications.startDate , applications.endDate , applications.totalDays , applications.reason , applications.extension , applications.hodApproval , applications.principalApproval , applications.status From applications where employeeID=$employeeID and applications.startDate < '$curr' ORDER BY applications.dateTime DESC";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }


    public function appliedLeaveHistory()
    {
        $employeeID = $this->employeeId;

        // SQL Query to get the leave history of login employee
        
        $sql = "SELECT * From applications inner join lectureadjustments on applications.applicationID = lectureadjustments.applicationID inner join leavetype on lectureadjustments.applicationID = leavetype.applicationID where employeeID='$employeeID'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    // This Function is Used To Find employee details Using id 
    public function findEmployeeDetailsUsingId($id)
    {

        // SQL Query to get the leave history of login employee
        $sql = "SELECT * From employees where employeeID='$id'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }
    
    // This Function is Used To Find pending lecture adjustment request of login employee
    public function lectureAdjustmentRequst()
    {
        $employeeID = $this->employeeId;
        $curr = date( 'Y-m-d' , time() );

        // SQL Query to get the lecture adjustment of login employee

        $sql = "SELECT lectureadjustments.lecAdjustmentID, lectureadjustments.applicantID , lectureadjustments.date , lectureadjustments.startTime , lectureadjustments.endTime, lectureadjustments.semester, lectureadjustments.date, lectureadjustments.subject , lectureadjustments.status , employees.fullName , employees.email , employees.employeeID from lectureadjustments inner join employees on lectureadjustments.applicantID = employees.employeeID Where lectureadjustments.adjustedWith = '$employeeID' and lectureadjustments.date >= $curr  ORDER BY lectureadjustments.status ;
        ";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    // This Function is Used To Find previous lecture adjustment request of login employee -->inshort(Accepted and Reejected request)
    
    public function elapsedLecAdjustments()
    {
        $employeeID = $this->employeeId;
        $curr = date( 'Y-m-d' , time() );

        // SQL Query to get the lecture adjustemnt of login employee

        $sql = "SELECT lectureadjustments.date , lectureadjustments.startTime , lectureadjustments.endTime, lectureadjustments.semester, lectureadjustments.subject , lectureadjustments.status , employees.fullName , employees.email , employees.employeeID  from lectureadjustments left join employees on lectureadjustments.applicantID = employees.employeeID Where lectureadjustments.adjustedWith = '$employeeID' and lectureadjustments.date < $curr ";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    // SQL Query to get the Task adjustment of login employee
    public function taskAdjustmentRequst()
    {
        $employeeID = $this->employeeId;
        $curr = date( 'Y-m-d' , time() );

        // SQL Query to get the task adjustemnt of login employee

        $sql = "SELECT taskadjustments.taskAdjustmentID , taskadjustments.applicantID , taskadjustments.startDate , taskadjustments.endDate, taskadjustments.task, taskadjustments.status , employees.fullName , employees.email , employees.employeeID from taskadjustments inner join employees on taskadjustments.applicantID = employees.employeeID Where taskadjustments.adjustedWith = '$employeeID' and taskadjustments.startDate >= $curr ORDER BY taskadjustments.status ";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));
        return $result;
    }

    // This Function is Used To Find previous Task adjustment request of login employee -->inshort(Accepted and Reejected request)
    
    public function elapsedTaskAdjustments(){
        $employeeID = $this->employeeId;
        $curr = date( 'Y-m-d' , time() );

        // SQL Query to get the task adjustemnt of login employee
        $sql = "SELECT taskadjustments.startDate , taskadjustments.endDate, taskadjustments.task, taskadjustments.status , employees.fullName , employees.email , employees.employeeID from taskadjustments left join employees on taskadjustments.applicantID = employees.employeeID Where taskadjustments.adjustedWith = '$employeeID' and taskadjustments.endDate < $curr";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));
        return $result;
    }
}
?>


<?php
class Faculty extends Staff
{
}

?>