<!-- Include this to use User object -->
<?php
//  Creates database connection 
require "../../includes/db.conn.php";
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <!-- all common links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/Admin/manageMasterData.css?v=<?php echo time(); ?>">
    <!-- <link rel="stylesheet" href="../../css/leaveHistory.css"> -->


    <!-- all common Script  -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>

    <!-- DataTables library to implement optimal search functinality ---- light weight library -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>

    <!-- style for collapse  -->
    <style>
        .collapse-btn {
            cursor: pointer;
        }

        .collapse-containt {
            display: block;
            margin-top: 10px;
        }

        .collapse-containt-privious {
            display: none;
            margin-top: 10px;
        }
        .collapse-btn-adjustment {
            cursor: pointer;
        }
        .collapse-containt-adjustment {
            display: block;
            margin-top: 10px;
        }
        .collapse-containt-privious-adjustment {
            display: none;
            margin-top: 10px;
        }
    </style>

</head>

<body>
    <!--Including sidenavbar -->
    <?php

        if( $user->role === Config::$_HOD_ ) include "../../includes/HOD/sidenavbar.php";
        if( $user->role === Config::$_FACULTY_ ) include "../../includes/Staff/sidenavbar.php";
    ?>
    <!-- Write all code in section with class "home-section"  -->

    <section class="home-section">

        <!--Including header -->
        <?php
            include "../../includes/header.php";
        ?>

        <div class="container">

            <!------------------------------ Lecture Adjustments ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-5 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">

                <h4 class="pb-3 pt-2  " style="color: #11101D;"> Lecture Adjustments Requests<i id="lec-adj" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                        <div class="form-row" id="lec-adj-container">

                            <table class="tablecontent" id="lec-adj-table">


                                <thead>
                                    <tr>
                                        <th>APPLICANT NAME</th>
                                        <th>APPLICANT EMAIL</th>
                                        <th>DATE</th>
                                        <th>START TIME</th>
                                        <th>END TIME</th>
                                        <th>SEMESTER</th>
                                        <th>SUBJECT</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="tbody">

                                    <?php

                                        $lecAdjData =  $user->lectureAdjustmentRequst(); 
                                        while($row =  mysqli_fetch_assoc($lecAdjData) ){
                                        

                                            echo "<tr>";
                                            echo "<td  >" . $row['fullName'] . "</td>";
                                            echo "<td  >" . $row['email'] . "</td>";
                                            echo "<td  >" . date( 'd-m-Y' , strtotime($row['date']) )  . "</td>";
                                            echo "<td  >" . $row['startTime'] . "</td>";
                                            echo "<td  >" . $row['endTime'] . " </td>";
                                            echo "<td  >" . $row['semester']  . " </td>";
                                            echo "<td  >" . $row['subject']  . " </td>";
                                            
                                            if( $row['status']  === Config::$_ADJUSTMENT_STATUS['PENDING'] ){
                                                echo "<td  > <a href='../../pages/Staff/validateAdjAction.php?applicantID=" .$row['applicantID']. "&adjID=" .$row['lecAdjustmentID']. "&type=lec&action=" .Config::$_ADJUSTMENT_STATUS['ACCEPTED']. "'> <button class='submitbtn clickable my-0 mx-2' > Accept </button> </a></td>";
                                                echo "<td  > <a href='../../pages/Staff/validateAdjAction.php?applicantID=" .$row['applicantID']. "&adjID=" .$row['lecAdjustmentID']. "&type=lec&action=" .Config::$_ADJUSTMENT_STATUS['REJECTED']. "'> <button class='submitbtn clickable my-0 mx-2' > Reject </button> </a></td>";
                                            }else{
                                                echo "<td class=" .$row['status']. " >" . $row['status']  . " </td>";
                                            }
                                        }
                                    
                                    ?>
                                </tbody>
                            </table>
                                    
                                    
                        </div>

                        <p class=" w-100 mt-3 clickable " id="lec-elapsed-btn">Elapsed Lecture Adjustments <i class="fa-solid fa-caret-down ml-2"></i> </p>

                        <br>

                        <div class="w-100" id="lec-elapsed">

                            <input type="text" class="form-control bg-white p-4 my-3 " id="lec-searchInput" placeholder="Search...">

                            <!-- //Filters -->
                            <div class="col-md-12 col-sm-12 py-3 border my-3 " id='filter-box'>

                                <!-- Status -->
                                <div class="my-2 col-md-12 ">

                                    <!-- Showing Status as Options -->
                                    <?php

                                        $status = Config::$_ADJUSTMENT_STATUS;

                                        foreach($status as $key => $value) {
                                            echo "<input type='checkbox' class=' lec-check-inp lec-inp-status' checked value='$key' >";
                                            echo "<label class=' mr-4 ml-2  ' >$value</label>";
                                        }


                                    ?>

                                </div>


                                <!-- Semester -->
                                <div class="my-2 col-md-12 ">
                                <!-- Showing Semester as Options -->
                                    <?php

                                        $sems = Config::$_SEMESTERS;

                                        foreach( $sems as $key => $value ){
                                                echo "<input type='checkbox' class='lec-check-inp lec-inp-sem' checked value='$value' >";
                                                echo "<label class=' mr-4 ml-2 ' >$key</label>";
                                        }
                                    
                                    ?>

                                </div>


                        </div>

                        <!-- Elapsed Requets  -->

                        <table id="lec-elapsedApp" class="tablecontent ">

                            <thead>
                                <tr>
                                <th>APPLICANT NAME</th>
                                <th>APPLICANT EMAIL</th>
                                <th>START TIME</th>
                                <th>END TIME</th>
                                <th>SEMESTER</th>
                                <th>SUBJECT</th>
                                <th>STATUS</th>
                                </tr>
                            </thead>

                            <tbody id="tbody">

                                <?php

                                    $elapsedlecAdjData =  $user->elapsedLecAdjustments(); 
                                    while($row =  mysqli_fetch_assoc($elapsedlecAdjData) ){

                                        echo "<tr>";
                                        echo "<td  >" . $row['fullName'] . "</td>";
                                        echo "<td  >" . $row['email'] . "</td>";
                                        echo "<td  >" . $row['startTime'] . "</td>";
                                        echo "<td  >" . $row['endTime'] . " </td>";
                                        echo "<td  >" . $row['semester']  . " </td>";
                                        echo "<td  >" . $row['subject']  . " </td>";
                                        echo "<td  >" . $row['status']  . " </td>";
                                    }

                                ?>
                                                                
                            </tbody>
                        </table>
                                
                        </div>


            </div>

            <!------------------------------ Task Adjustments ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-5 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>' method="POST">

                <h4 class="pb-3 pt-2  " style="color: #11101D;"> Task Adjustments Requests<i id="task-adj" class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                        <div class="form-row" id="task-adj-container">

                            <table class="tablecontent" id="task-adj-table">


                                <thead>
                                    <tr>
                                        <th>APPLICANT NAME</th>
                                        <th>APPLICANT EMAIL</th>
                                        <th>START DATE</th>
                                        <th>END DATE</th>
                                        <th>TASK</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="tbody">

                                    <?php

                                        $lecAdjData =  $user->taskAdjustmentRequst(); 
                                        while($row =  mysqli_fetch_assoc($lecAdjData) ){
                                        
                                            echo "<tr>";
                                            echo "<td  >" . $row['fullName'] . "</td>";
                                            echo "<td  >" . $row['email'] . "</td>";
                                            echo "<td  >" . $row['startDate'] . "</td>";
                                            echo "<td  >" . $row['endDate'] . " </td>";
                                            echo "<td  >" . $row['task']  . " </td>";
                                            if( $row['status']  === Config::$_ADJUSTMENT_STATUS['PENDING'] ){
                                                echo "<td  > <a href='../../pages/Staff/validateAdjAction.php?applicantID=" .$row['applicantID']. "&adjID=" .$row['taskAdjustmentID']. "&type=task&action=" .Config::$_ADJUSTMENT_STATUS['ACCEPTED']. "'> <button class='submitbtn clickable my-0 mx-2' > Accept </button> </a></td>";
                                                echo "<td  > <a href='../../pages/Staff/validateAdjAction.php?applicantID=" .$row['applicantID']. "&adjID=" .$row['taskAdjustmentID']. "&type=task&action=" .Config::$_ADJUSTMENT_STATUS['REJECTED']. "'> <button class='submitbtn clickable my-0 mx-2' > Reject </button> </a></td>";
                                            }else{
                                                echo "<td class=" .$row['status']. " >" . $row['status']  . " </td>";
                                            }
                                        }
                                    
                                    ?>
                                </tbody>
                            </table>
                                    
                                    
                        </div>

                            <p class=" w-100 mt-3 clickable " id="task-elapsed-btn">Elapsed Task Adjustments<i class="fa-solid fa-caret-down ml-2"></i> </p>

                            <br>

                            <div class="w-100" id="task-elapsed">

                                <input type="text" class="form-control bg-white p-4 my-3 " id="task-searchInput" placeholder="Search...">

                                <!-- //Filters -->
                                <div class="col-md-12 col-sm-12 py-3 border my-3 " id='filter-box'>

                                    <!-- Status -->
                                    <div class="my-2 col-md-12 ">

                                        <!-- Showing Status as Options -->
                                        <?php

                                            $status = Config::$_ADJUSTMENT_STATUS;

                                            foreach($status as $key => $value) {
                                                echo "<input type='checkbox' class=' task-check-inp task-inp-status' checked value='$key' >";
                                                echo "<label class=' mr-4 ml-2  ' >$value</label>";
                                            }


                                        ?>

                                    </div>


                                </div>

                                <!-- Elapsed Requests  -->

                                <table id="task-elapsedApp" class="tablecontent ">

                                    <thead>
                                        <tr>
                                        <th>APPLICANT NAME</th>
                                        <th>APPLICANT EMAIL</th>
                                        <th>START DATE</th>
                                        <th>END DATE</th>
                                        <th>TASK</th>
                                        <th>STATUS</th>
                                        </tr>
                                    </thead>

                                    <tbody id="tbody">

                                        <?php

                                            $taskAdjData =  $user->elapsedTaskAdjustments(); 
                                            while($row =  mysqli_fetch_assoc($taskAdjData) ){
                                            
                                                echo "<tr>";
                                                echo "<td  >" . $row['fullName'] . "</td>";
                                                echo "<td  >" . $row['email'] . "</td>";
                                                echo "<td  >" . $row['startDate'] . "</td>";
                                                echo "<td  >" . $row['endDate'] . " </td>";
                                                echo "<td  >" . $row['task']  . " </td>";
                                                echo "<td  >" . $row['status']  . " </td>";
                                            }
                                        
                                        ?>
                                    </tbody>

                                </table>

                            </div>
    
            </div>

            <!------------------------------ Additional Approvals ------------------------------>

            <div class=" bg-white shadow pl-5 pr-5 pb-5 pt-4 mt-5 rounded-lg" action='<?php echo $actionUrl?>'
                method="POST">

                <h4 class="pb-3 pt-2  " style="color: #11101D;"> Approval Requests<i id="add-app"
                        class="fa-solid fa-caret-down ml-3 clickable"></i> </h4>

                        <div class="form-row" id="add-app-container">

                            <table class="tablecontent" id="add-app-table">

                                <thead>
                                    <tr>
                                        <th>APPLICANT EMAIL</th>
                                        <th>START DATE</th>
                                        <th>END DATE</th>
                                        <th>REASON</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="tbody">

                                    <?php

                                        $addApp =  $user->approvalRequst(); 
                                        while($row =  mysqli_fetch_assoc($addApp) ){
                                        
                                            
                                            echo "<tr>";
                                            echo "<td  >" . $row['email'] . "</td>";
                                            echo "<td  >" . $row['startDate'] . "</td>";
                                            echo "<td  >" . $row['endDate'] . " </td>";
                                            echo "<td  >" . $row['reason']  . " </td>";
                                            if( $row['status']  === Config::$_ADJUSTMENT_STATUS['PENDING'] ){
                                                echo "<td  > <a href='../../pages/Staff/validateAdjAction.php?applicationID=" .$row['applicationID']. "&approverId=" .$row['approverId']. "&type=approval&action=" .Config::$_ADJUSTMENT_STATUS['ACCEPTED']. "'> <button class='submitbtn clickable my-0 mx-2' > Accept </button> </a></td>";
                                                echo "<td  > <a href='../../pages/Staff/validateAdjAction.php?applicationID=" .$row['applicationID']. "&approverId=" .$row['approverId']. "&type=approval&action=" .Config::$_ADJUSTMENT_STATUS['REJECTED']. "'> <button class='submitbtn clickable my-0 mx-2' > Reject </button> </a></td>";
                                            }else{
                                                echo "<td class=" .$row['status']. " >" . $row['status']  . " </td>";
                                            }
                                        }
                                    
                                    ?>
                                </tbody>
                            </table>
                                    
                                    
                        </div>

                            <p class=" w-100 mt-3 clickable " id="addApp-elapsed-btn">Elapsed Approvals<i class="fa-solid fa-caret-down ml-2"></i> </p>

                            <br>

                            <div class="w-100" id="addApp-elapsed">

                                <input type="text" class="form-control bg-white p-4 my-3 " id="addApp-searchInput" placeholder="Search...">

                                <!-- //Filters -->
                                <div class="col-md-12 col-sm-12 py-3 border my-3 " id='filter-box'>

                                    <!-- Status -->
                                    <div class="my-2 col-md-12 ">

                                        <!-- Showing Status as Options -->
                                        <?php

                                            $status = Config::$_ADJUSTMENT_STATUS;

                                            foreach($status as $key => $value) {
                                                echo "<input type='checkbox' class=' addApp-check-inp addApp-inp-status' checked value='$key' >";
                                                echo "<label class=' mr-4 ml-2  ' >$value</label>";
                                            }


                                        ?>

                                    </div>


                                </div>

                            <!-- Elapsed Requests  -->

                            <table id="addApp-elapsedApp" class="tablecontent ">

                                <thead>
                                    <tr>
                                    <th>APPLICANT EMAIL</th>
                                    <th>START DATE</th>
                                    <th>END DATE</th>
                                    <th>REASON</th>
                                    <th>STATUS</th>
                                    </tr>
                                </thead>

                                <tbody id="tbody">

                                    <?php

                                        $elapsedApprovals =  $user->elapsedapprovalRequst(); 
                                        while($row =  mysqli_fetch_assoc($elapsedApprovals) ){
                                        
                                            echo "<tr>";
                                            echo "<td  >" . $row['email'] . "</td>";
                                            echo "<td  >" . $row['startDate'] . "</td>";
                                            echo "<td  >" . $row['endDate'] . " </td>";
                                            echo "<td  >" . $row['reason']  . " </td>";
                                            echo "<td  >" . $row['status']  . " </td>";
                                        }
                                    
                                    ?>
                                </tbody>

                            </table>

                        </div>
    
            </div>

            
    </section>


        <script>
            // script for search functionality 
            $(document).ready(function() {


                //Lecture Adjustments
                $('#lec-elapsed').hide()

                $('#lec-elapsed-btn').click(() => {
                    $('#lec-elapsed').toggle()
                })

                $('#lec-adj-container').hide()

                $('#lec-adj').click(() => {
                
                    $('#lec-adj-container').toggle();
            
                })



                //Task Adjustments

                $('#task-elapsed').hide()

                $('#task-elapsed-btn').click(() => {
                    $('#task-elapsed').toggle()
                })

                $('#task-adj-container').hide()

                $('#task-adj').click(() => {
                
                    $('#task-adj-container').toggle();
                
                })

                //Additonal Approvals

                $('#addApp-elapsed').hide()

                $('#addApp-elapsed-btn').click(() => {
                    $('#addApp-elapsed').toggle()
                })

                $('#add-app-container').hide()

                $('#add-app').click(() => {
                
                    $('#add-app-container').toggle();
                
                })



                // Initialize DataTable
                var table = $('#lec-elapsedApp').DataTable(
                        {
                            paging: false,
                            ordering: false,
                            info: true
                        }
                );

                // Initialize DataTable
                var tasktable = $('#task-elapsedApp').DataTable(
                        {
                            paging: false,
                            ordering: false,
                            info: true
                        }
                );

                // Initialize DataTable
                var apptable = $('#addApp-elapsedApp').DataTable(
                        {
                            paging: false,
                            ordering: false,
                            info: true
                        }
                );


                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

                    if( data.length === 7 ){


                    //* Lecture Adjustments

                        //Take all Types of Filters
                        let statusInps = document.querySelectorAll('.lec-inp-status')
                        let semInps = document.querySelectorAll('.lec-inp-sem')

                        //Create Arrays
                        let status = []
                        let sem = []

                        //For Status
                        statusInps.forEach(element => {


                            if (element.checked) {

                                status.push(element.value);
                            }

                        });


                        //For status
                        semInps.forEach(element => {

                            if (element.checked) {

                                sem.push(element.value);
                            }

                        });

                        let tableStatus = data[6];
                        let tableSem = data[4];

                        //Filter Logic
                        if( status.includes( tableStatus ) && sem.includes( tableSem ) ) return true;
                        else return false;

                    }
                    else if( data.length === 5 ){


                        //* Additional Approvals

                        //Take all Types of Filters
                        let addAppstatusInps = document.querySelectorAll('.addApp-inp-status')

                        //Create Arrays
                        let addAppstatus = []

                        //For Status
                        addAppstatusInps.forEach(element => {

                            if (element.checked) {

                                addAppstatus.push(element.value);
                            }

                        });

                        let addApptableStatus = data[4];

                        console.log(addApptableStatus);

                        //Filter Logic
                        if( addAppstatus.includes( addApptableStatus ) ) return true;
                        else return false;



                    }
                    else{


                        //* Task Adjustments

                        //Take all Types of Filters
                        let taskstatusInps = document.querySelectorAll('.task-inp-status')

                        //Create Arrays
                        let taskstatus = []

                        //For Status
                        taskstatusInps.forEach(element => {

                            if (element.checked) {

                                taskstatus.push(element.value);
                            }

                        });

                        let tasktableStatus = data[5];

                        console.log(tasktableStatus);

                        //Filter Logic
                        if( taskstatus.includes( tasktableStatus ) ) return true;
                        else return false;

                    }


                    });

                    //On Filter Change load the table
                    $('.lec-check-inp').change(() => {
                        table.draw();
                    })


                    // Add event listener for the search input
                    $('#lec-searchInput').on('keyup', function() {
                        table.search(this.value).draw();
                    });


                    //On Filter Change load the table
                    $('.task-check-inp').change(() => {
                        tasktable.draw();
                    })


                    // Add event listener for the search input
                    $('#task-searchInput').on('keyup', function() {
                        tasktable.search(this.value).draw();
                    });

                    //On Filter Change load the table
                    $('.addApp-check-inp').change(() => {
                        apptable.draw();
                    })


                    // Add event listener for the search input
                    $('#addApp-searchInput').on('keyup', function() {
                        apptable.search(this.value).draw();
                    });

            })

        </script>
</body>

</html>