<?php

    $conn = sql_conn(); // You should define the database connection function

    echo "<div class='employeesOnLeave' id='employeesOnLeaves' >";

    $days = 7;

    if( $user->role == Config::$_PRINCIPAL_ ) $days = 28;


    for ($i = 0; $i < $days; $i++) {

        $currentDayDisplay = date("l", strtotime("+{$i} days"));
        $currentDateDisplay = date("d-m-y", strtotime("+{$i} days"));
        
        $currentDate = date("Y-m-d", strtotime("+{$i} days"));
        
        $approved = Config::$_APPLICATION_STATUS['APPROVED_BY_PRINCIPAL'];
        $sanctioned = Config::$_APPLICATION_STATUS['SANCTIONED'];
        $deductedFromEL = Config::$_APPLICATION_STATUS['DEDUCTED_FROM_EL'];
        $lwp = Config::$_APPLICATION_STATUS['LEAVE_WITHOUT_PAY'];


        $employeesOnLeaveTodayQuery =   "SELECT *
        FROM employees AS e
        JOIN applications AS a ON e.employeeID = a.employeeID
        WHERE (a.status='$approved' OR a.status='$sanctioned' OR a.status='$deductedFromEL' OR a.status='$lwp')
          AND '$currentDate' BETWEEN a.startDate AND a.endDate;
        ";

        $employeesOnLeaveTodayQueryOutput = mysqli_query($conn, $employeesOnLeaveTodayQuery);

        echo
        "<div class='seperateDates'  >
            <div class='currentDate'>$currentDateDisplay<br>$currentDayDisplay</div>
            <div class='employeesOnLeaves' >";

        while ($employeesOnLeaveRow = mysqli_fetch_array($employeesOnLeaveTodayQueryOutput)) {

                echo "<a href='../../pages/Staff/viewDetails.php?id=$employeesOnLeaveRow[applicationID]' class='calender-text'  >  $employeesOnLeaveRow[fullName] </a>";
        }

        echo "</div></div>";
    }

    echo "</div>";
?>
