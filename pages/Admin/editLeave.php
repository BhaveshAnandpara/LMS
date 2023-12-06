<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
    
        //include Config Class
    require('../../utils/Utils.class.php');
    require('../../utils/Config/Config.class.php');

    //include class definition
    require('../../utils/Admin/admin.class.php');

    //start session
    session_start();
    
?>


<!-- Include this to use User object -->
<?php

    //Get the User Object
    $user =  $_SESSION['user'];
    $leaveID = $_GET['leaveId']

?>



<!DOCTYPE html>
<html lang="en">

<head>

    <title>Bajaj Institute of Technology, Wardha</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- all common CSS link  -->
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/Admin/manageMasterData.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- all common Script  -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>

</head>

<body>


    <!--Including sidenavbar -->
    <?php
     include "../../includes/Admin/sidenavbar.php";
    ?>


    <section class="home-section">

        <!--Including header -->
        <?php
            include "../../includes/header.php";
        ?>

        <?php 

            if( $leaveID){
                $leaveDetails = mysqli_fetch_assoc( Utils::getLeaveDetails($leaveID) );
            }

        ?>

        <div class="container">

            <?php $actionUrl = "validateEditLeave.php?leaveID=".$leaveID."" ?>

            <form class=" bg-white shadow pl-5 pr-5 pb-3 pt-2 mt-5 rounded-lg" action='<?php echo $actionUrl?>'  method="POST">

                <h4 class="pb-3 pt-2" style="color: #11101D;">Edit Leave</h4>

                <div class="form-row">

                    <!-- Leave Name -->
                    <div class="form-group col-md-4">
                        <input type="text" maxLength="100" value="<?php echo $leaveDetails['leaveType'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder=" Add Leave Name " name="leaveName">
                    </div>

                    <!-- Leave Desc -->
                    <div class="form-group col-md-8">
                        <input type="text" maxLength="1000" value="<?php echo $leaveDetails['leaveDesc'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder=" Leave Description " name="leaveDesc">
                    </div>

                    <!-- Cycle Date -->
                    <div class="form-group col-md-4">
                        <input type="text" onfocus="(this.type='date')"  value="<?php echo $leaveDetails['cycleDate'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Cycle Date" name="cycleDate">
                    </div>

                    <!-- Interval -->
                    <div class="form-group col-md-4">
                        <input type="Number" max="12" value="<?php echo $leaveDetails['leaveInterval'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder=" Enter Leave Interval ( In Months ) " name="leaveInterval">
                    </div>

                    <!-- Increment -->
                    <div class="form-group col-md-4">
                        <input type="Number" value="<?php echo $leaveDetails['increment'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Enter Leave Increment" name="leaveIncrement">
                    </div>


                    <!-- Balance Limit -->
                    <div class="form-group col-md-3">
                        <input type="Number" value="<?php echo $leaveDetails['balanceLimit'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Enter Balance Limit" name="balanceLimit">
                    </div>

                    <!-- Apply Limit -->
                    <div class="form-group col-md-3">
                        <input type="Number" value="<?php echo $leaveDetails['applyLimit'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Enter Apply Limit" name="applyLimit">
                    </div>

                    <!-- Waiting Time -->
                    <div class="form-group col-md-3">
                        <input type="Number" value="<?php echo $leaveDetails['waitingTime'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Enter Waiting Time ( In Days )" name="waitingTime">
                    </div>

                    <!-- CarryForwardInto -->
                    <div class="form-group col-md-3">

                        <select name="carryForwardInto" 
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark ">

                            <option value="NULL">No Carry Forward</option>

                            <!-- Showing Leave Types as Options -->
                            <?php
                                $leaveTypes = Utils::getLeaveTypes();

                                while( $row = mysqli_fetch_assoc($leaveTypes) ){

                                    if( $row['leaveID'] == $leaveDetails['carryForwardInto'] ){

                                        echo "<option value='" .$row['leaveID']. "' selected >". $row['leaveType'] ."</option>";
                                        
                                    }else{
                                        
                                        echo "<option value='" .$row['leaveID']. "' >". $row['leaveType'] ."</option>";

                                    }

                                
                                }

                            ?>

                        </select>

                    </div>


                    <div class="form-group col-md-6"> <input type="submit" value="Update" name="addLeaveSubmit" class="submitbtn"> </div>

            </form>

        </div>


    </section>

    <?php
    
        require('../../includes/model.php'); 

        
        // Check for response message from validateNewLeave.php
        if (isset($_SESSION['response_message']) && !empty($_SESSION['response_message'])) {
            
                    $res = unserialize($_SESSION['response_message']);
                    unset($_SESSION['response_message']); // Clear the message to prevent displaying it again

                    if( $res[1] === "SUCCESS" || !$leaveID ){   
                        echo Utils::alert(htmlspecialchars($res[0]), htmlspecialchars($res[1]) , "manageMasterData.php");
                    }
            }

    ?>

</body>

</html>