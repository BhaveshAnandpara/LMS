<?php

//include class definition
require_once __DIR__ . '/../Admin/admin.class.php';


class Principal extends Admin
{

    public function recentlyAppliedLeaveOfEmp( $empID )
    {

        // SQL Query to get the leave history of login employee

        $sql = "SELECT applications.applicationID , applications.dateTime , applications.startDate , applications.endDate , applications.totalDays , applications.reason , applications.hodApproval , applications.principalApproval , applications.status From applications where employeeID=$empID ORDER BY applications.dateTime DESC";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    public function getAppLeaveTypes($appID)
    {

        // SQL Query to get the leave history of login employee

        $sql = "SELECT leavetype.leaveType from leavetype where applicationID=$appID";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);

        if (!$result) echo ("Error description: " . mysqli_error($conn));

        $ans = "";

        while ($row = mysqli_fetch_assoc($result)) {

            $ans = $ans . " + " . $row['leaveType'];
        }

        $ans = substr($ans, 2) . "";

        return $ans;
    }

    public function getLeaveRequests(  )    {

        // SQL Query to get the leave history of login employee
        $status = Config::$_APPLICATION_STATUS['APPROVED_BY_HOD'];

        $sql = "SELECT employees.employeeID , departments.deptName , applications.applicationID , applications.startDate ,applications.endDate ,applications.totalDays ,applications.status from employees inner join departments on employees.deptID = departments.deptID inner join applications on applications.employeeID = employees.employeeID where applications.status = '$status'";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    public function findEmployeeDetailsUsingId( $id ){

        // SQL Query to get the leave history of login employee
        $sql = "SELECT * From employees where employeeID='$id'";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    public function viewDetailApplication( $id ){

        $sql = "SELECT applications.status as applicationStatus , applications.* , employees.* FROM `applications` inner join employees on applications.employeeID = employees.employeeID WHERE `applicationID` = $id";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;

    }

    public function getApprovalRequst( $applicationID ){

        $employeeID = $this->employeeId;
        $curr = date('Y-m-d', time());

        // SQL Query to get the task adjustment of login employee

        $sql = "SELECT * from approvals where approvals.applicationID = '$applicationID' ";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;


    }

    public function getLectureAdjustments( $applicationID ){

        $employeeID = $this->employeeId;
        $curr = date('Y-m-d', time());

        // SQL Query to get the lecture adjustment of login employee

        $sql = "SELECT lectureadjustments.lecAdjustmentID, lectureadjustments.applicantID , lectureadjustments.date , lectureadjustments.startTime , lectureadjustments.endTime, lectureadjustments.semester, lectureadjustments.date, lectureadjustments.subject , lectureadjustments.status , employees.fullName , employees.email , employees.employeeID from lectureadjustments inner join employees on lectureadjustments.adjustedWith = employees.employeeID where lectureadjustments.applicationID = '$applicationID' ";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;


    }

    public function getTaskAdjustments( $applicationID ){

        $employeeID = $this->employeeId;
        $curr = date('Y-m-d', time());

        // SQL Query to get the task adjustment of login employee

        $sql = "SELECT taskadjustments.taskAdjustmentID, taskadjustments.applicantID , taskadjustments.startDate , taskadjustments.endDate, taskadjustments.task, taskadjustments.status , employees.fullName , employees.email , employees.employeeID from taskadjustments inner join employees on taskadjustments.adjustedWith = employees.employeeID where taskadjustments.applicationID = '$applicationID' ";
        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;


    }

    public function getFileDetails($applicationId)
    {
        $employeeID = $this->employeeId;

        // to get additinal approval 
        $sql = "SELECT * 
        FROM files 
        WHERE files.applicationID = $applicationId;
        ";

        $conn = sql_conn();
        $result =  mysqli_query($conn, $sql);
        if (!$result) echo ("Error description: " . mysqli_error($conn));

        return $result;
    }

    


}
?>
