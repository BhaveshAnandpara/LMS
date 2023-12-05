<?php
    $conn = sql_conn(); // You should define the database connection function

    echo "<div class='employeesOnLeave'>";

    for ($i = 0; $i < 7; $i++) {
        $currentDayDisplay = date("D", strtotime("+{$i} days"));
        $currentDateDisplay = date("d-m-y", strtotime("+{$i} days"));
        $currentDate = date("Y-m-d", strtotime("+{$i} days"));

        $employeesOnLeaveTodayQuery =   "SELECT *
                                            FROM employees AS e
                                            JOIN applications AS a ON e.employeeID = a.employeeID
                                            WHERE '$currentDate' BETWEEN a.startDate AND a.endDate";        
        $employeesOnLeaveTodayQueryOutput = mysqli_query($conn, $employeesOnLeaveTodayQuery);

        echo
        "<div class='seperateDates'>
            <div class='currentDate'>$currentDayDisplay<br>$currentDateDisplay</div>
            <div class='employeesOnLeaves'>";

        while ($employeesOnLeaveRow = mysqli_fetch_array($employeesOnLeaveTodayQueryOutput)) {
            echo "<a href='../../pages/Staff/viewDetails.php?id=$employeesOnLeaveRow[applicationID]' >$employeesOnLeaveRow[fullName]</a>";
        }

        echo "</div></div>";
    }

    echo "</div>";
?>
