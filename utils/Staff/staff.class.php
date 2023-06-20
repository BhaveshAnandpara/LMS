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


    // ------------------------------------ Leave History amd Adjustment request ------------------------------------ //

    // This Function is Used To get applied Leave History details of Login Employee
    public function appliedLeaveHistory()
    {
        $employeeID = $this->employeeId;
        // SQL Query to get the leave history of login employee
        $sql = "SELECT applications.* From applications  where employeeID='$employeeID'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    // This Function is Used To find details of perticuler applicatiion detail of Leave

    public function applicationDetailsUsingId($appliID)
    {
        $employeeID = $this->employeeId;
        
        // SQL Query to get the leave history of login employee
        $sql = "SELECT * From applications  where employeeID='$employeeID' AND applicationID ='$appliID'";
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

    // This Function is Used To get  employee  department  details Using id 
    public function getDepartmet($deptID)
    {
        $sql = "SELECT * From departments where deptID='$deptID'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));
        return $result;
    }

    // This Function is Used To get  employee leaves and balance  details  Using employeeID 
    public function getLeaveTypeBalance($employeeID)
    {
        $sql = "SELECT * From leavebalance where employeeID='$employeeID'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    // This Function is Used To get  employees applied leaves  Using applicationID 
    public function leaveTypeApplied($applicationID)
    {
        $sql = "SELECT * from leaveType where applicationID = '$applicationID'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));
        return $result;
    }

    // This function is used to find leave leave type using leaveID ---> comman function to all stackeholder 
    public function findLeaveTypeUsingId($leaveType)
    {
        $sql = "SELECT * from masterdata where leaveID = '$leaveType'";
        $conn = sql_conn();
        $result = mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));
        return $result;
    }

    // This Function is Used To Find  lecture adjustment data  of perticuler aapplication  of login employee
    public function lectureAdjustmentData($applicationID)
    {
        $employeeID = $this->employeeId;
        $sql = "SELECT * from lectureadjustments where applicationID = '$applicationID' AND applicantID = '$employeeID'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));
        return $result;
    }


    // This Function is Used To Find pending lecture adjustment request of login employee
    public function lectureAdjustmentRequst()
    {
        $employeeID = $this->employeeId;
        // SQL Query to get the lecture adjustment of login employee
        $sql = "SELECT * From applications inner join lectureadjustments on applications.applicationID = lectureadjustments.applicationID  where adjustedWith='$employeeID' and lectureadjustments.status ='PENDING'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }
    // This Function is Used To Find previous lecture adjustment request of login employee -->inshort(Accepted and Reejected request)
    public function PreviouslectureAdjustmentRequst()
    {
        $employeeID = $this->employeeId;
        // SQL Query to get the lecture adjustemnt of login employee
        $sql = "SELECT * From applications inner join lectureadjustments on applications.applicationID = lectureadjustments.applicationID  where adjustedWith='$employeeID'and (lectureadjustments.status ='ACCEPT' OR lectureadjustments.status ='REJECT' OR lectureadjustments.status ='WITHDROWN' ) ";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    // function to get the Task adjustment of login employee
    public function taskAdjustmentRequst()
    {
        $employeeID = $this->employeeId;
        // SQL Query to get the task adjustemnt of login employee
        $sql = "SELECT * From applications inner join taskadjustments on applications.applicationID = taskadjustments.applicationID  where adjustedWith='$employeeID'and taskadjustments.status ='PENDING'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));
        return $result;
    }
    // This Function is Used To Find previous Task adjustment request of login employee -->inshort(Accepted and Rejected request)
    public function PreviousTaskAdjustmentRequst()
    {
        $employeeID = $this->employeeId;
        // SQL Query to get the task adjustemnt of login employee
        $sql = "SELECT * From applications inner join taskadjustments on applications.applicationID = taskadjustments.applicationID  where adjustedWith='$employeeID'and (taskadjustments.status ='ACCEPT' OR taskadjustments.status ='REJECT' OR taskadjustments.status ='WITHDROWN' )";
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