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

}
?>
