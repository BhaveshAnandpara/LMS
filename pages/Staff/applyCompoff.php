<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>


<!-- Include this to use User object -->
<?php

    //include class definition
    require('../../utils/Staff/Staff.class.php');

    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');

    //start session
    session_start();

    //Get the User Object
    $user =  $_SESSION['user'];

?>


<!-- Load all the necessary information -->
<?php

    //Get Data of Leave types from DB
    $leaveTypes = Utils::getLeaveTypes();
    $leaveTypesArr = array();
    
    while( $rows = mysqli_fetch_assoc( $leaveTypes ) ){
        $leaveTypesArr[] =  $rows ;
    }
    
    //Get Data of employee balance from DB  
    $employeeBalance = Utils::getLeaveBalanceOfEmployee( $user->employeeId );
    $employeeBalanceArr = array();
    
    while( $rows = mysqli_fetch_assoc( $employeeBalance ) ){
        $employeeBalanceArr[] =  $rows ;
    }

    //Get Holidays Data
    $holidays = Utils::getUpcomingHolidays( );
    $holidaysArr = array();
    
    while( $rows = mysqli_fetch_assoc( $holidays ) ){
        $holidaysArr[] =  $rows ;
    }
    
    $leaveTypes = Utils::getLeaveTypes();
    $employeeBalance = Utils::getLeaveBalanceOfEmployee( $user->employeeId );
    $holidays = Utils::getUpcomingHolidays( );

?>

<script>

    var user = <?php echo json_encode( $user ); ?>;
    var leaveTypeDeatils = <?php echo json_encode( $leaveTypesArr ); ?>;
    var employeeBalance = <?php echo json_encode( $employeeBalanceArr ); ?>;

    var holidays = <?php echo json_encode( $holidaysArr ); ?>;

    holiDaysDate = []

    //Get all the holidays in array
    holidays.forEach( holiday => {
        holiDaysDate.push( new Date(holiday.date).toISOString().slice(0, 10))
    });

    var leaveNames = []

    leaveTypeDeatils.forEach( leaveDetail => {
        leaveNames[leaveDetail.leaveID + ''] = leaveDetail.leaveType
    });


</script>


<!DOCTYPE html>
<html lang="en">

<head>

    <title>Apply Leave</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- all common CSS link  -->
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/Admin/manageMasterData.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- all common Script  -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>
    <!--Including sidenavbar -->
    <?php
     include "../../includes/Staff/sidenavbar.php";
    ?>

    <section class="home-section">

        <!--Including header -->

        <?php
            include "../../includes/header.php";
        ?>

        <!-- Below code for dashboard -->
        <div class="container">

            <!-- Current Balance -->
            <form  method="POST" class="bg-white shadow pl-5 pr-5  pb-5 pt-2 mt-5 rounded-lg " style="border-right:6px solid #11101D;">

                <h4 class="pb-3 pt-2" style="color: #11101D;">Request for Leave</h4>
                
                <!-- Uneditable Info -->
                <div class="form-row">
                    
                    <!-- Input Email -->
                    <div class="form-group col-md-4">
                        
                        <input type="email" readonly class="form-control border-top-0 border-right-0 border-left-0 border border-dark bg-white" id="email" placeholder=" Email" name="email" value="
                        <?php  echo $user->email  ?>">

                    </div>

                    <!-- //Department -->
                    <div class="form-group col-md-4">

                        <input type="text" readonly class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark " id="inputEmail4" placeholder="Department" name="dept" value="<?php 
                            $deptInfo = mysqli_fetch_assoc(Utils::getDeptDetails($user->deptID));
                            echo $deptInfo['deptName'] 
                        ?>">

                    </div>

                    <!-- Application Date -->
                    <div class="form-group col-md-4">
                            
                            <!-- //Get current date -->
                            <input type="text" readonly name="date" class="form-control bg-white border-top-0 border-right-0 border-left-0  border border-dark" id="inputPassword4" placeholder="Today Date" value="<?php echo date('d-M-Y')?>">
    
                    </div>

                    <!-- // compoff  -->
                    
                      <div class="form-group col-md-3">
                        
                        <input type="date"  class="form-control border-top-0 border-right-0 border-left-0 border border-dark bg-white" id="date" placeholder=" Enter Compoff Date" name="">

                    </div>

                  
                    <div class="form-group col-md-3">
        <input type="time" id="deptTime" class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark" placeholder="Department">
    </div>
    <div class="form-group col-md-3">
        <input type="time" id="endTime" class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark" placeholder="End Time">
    </div>
    <div class="form-group col-md-3">
        <input type="text" id="leaveResult" class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark" placeholder="result">
    </div>

                    <!-- reason  -->
                    <div class="form-group col-md-12 pt-2">

<textarea type="text" name="reason"  placeholder="Reason" class="form-control border border-dark" id="reason"></textarea>

</div>

<div class="form-group col-md-12">
<button id="leaveApplyBtn" name="submit" class="btn " style="background-color: #11101D; color: white;">Request</button>
</div>
<p id="leaveResult"></p>

    <script>
        // Function to calculate leave based on time inputs
        function calculateLeave() {
            var deptTime = document.getElementById("deptTime").value;
            var endTime = document.getElementById("endTime").value;

            var deptTimeParts = deptTime.split(":");
            var endTimeParts = endTime.split(":");

            var deptHour = parseInt(deptTimeParts[0]);
            var deptMinute = parseInt(deptTimeParts[1]);

            var endHour = parseInt(endTimeParts[0]);
            var endMinute = parseInt(endTimeParts[1]);

            var deptMinutes = deptHour * 60 + deptMinute;
            var endMinutes = endHour * 60 + endMinute;

            var minutesDifference = endMinutes - deptMinutes;

            if (minutesDifference >= 60 * 24) {
              var result=  document.getElementById("leaveResult").innerText = "cant  take leave";
              document.getElementById("leaveResult").value = result;

            } else if (minutesDifference <= 60 * 4) {
                var result=  document.getElementById("leaveResult").innerText = "half leave";
              document.getElementById("leaveResult").value = result;
            } else {
                var result=  document.getElementById("leaveResult").innerText = "full leave";
              document.getElementById("leaveResult").value = result;
            }
        }

        // Add event listener to calculate leave when End Time input changes
        document.getElementById("endTime").addEventListener("input", calculateLeave);
    </script>
                </div>
                

                </form>
                </div>
                </section>
                
                    
                </body>
                </html>