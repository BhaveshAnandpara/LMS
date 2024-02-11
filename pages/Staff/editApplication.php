<?php

    //start session
    session_start();

    //  Creates database connection 
    require "../../includes/db.conn.php";
    
    //include class definition
    require('../../utils/Staff/staff.class.php');
    
    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');

?>


<!-- Include this to use User object -->

<?php

//Get the User Object
$user = unserialize($_SESSION['user']) ;

?>


<!-- Load all the necessary information -->
<?php

    // ------------------------For Current Application Data ----------------------------

    //Get Data of Applied Leave Types for this application
    $appID = $_GET['id'];
    $isExtension = $_GET['extend'];

    // All Data 

    $applicationData = $user->viewDetailApplication($appID);

    $appData = array();
    
    while ($rows = mysqli_fetch_assoc($applicationData)) {
        $appData[] =  $rows;
    } 

    // Leave Data

    $applicationLeaveData = $user->getApplicationLeaveData($appID);
    
    $appLeaveTypeArr = array();
    
    while ($rows = mysqli_fetch_assoc($applicationLeaveData)) {
        $appLeaveTypeArr[] =  $rows;
    } 

    // Lecture Adjustments Data

    $applicationLecAdjData = $user->getLectureAdjustments($appID);
    
    $appLecAdjArr = array();
    
    while ($rows = mysqli_fetch_assoc($applicationLecAdjData)) {
        $appLecAdjArr[] =  $rows;
    } 

    // Task Adjustments Data

    $applicationTaskAdjData = $user->getTaskAdjustments($appID);
    
    $appTaskAdjArr = array();
    
    while ($rows = mysqli_fetch_assoc($applicationTaskAdjData)) {
        $appTaskAdjArr[] =  $rows;
    } 

    // Additional Approvals Data

    $applicationAddAppData = $user->getApprovalRequst($appID);
    
    $appAddAppArr = array();
    
    while ($rows = mysqli_fetch_assoc($applicationAddAppData)) {
        $appAddAppArr[] =  $rows;
    } 

    // Files Attach Data

    $applicationFilesData = $user->getFileDetails($appID);
    
    $appFilesArr = array();
    
    while ($rows = mysqli_fetch_assoc($applicationFilesData)) {
        $appFilesArr[] =  $rows;
    }
    

    // --------------------------- For Validation ----------------------------------

    //Get Data of Leave types from DB
    $leaveTypes = Utils::getLeaveTypes();
    $leaveTypesArr = array();

    while ($rows = mysqli_fetch_assoc($leaveTypes)) {
        $leaveTypesArr[] =  $rows;
    }

    //Get Data of employee balance from DB  
    $employeeBalance = Utils::getLeaveBalanceOfEmployee($user->employeeId);
    $employeeBalanceArr = array();

    while ($rows = mysqli_fetch_assoc($employeeBalance)) {
        $employeeBalanceArr[] =  $rows;
    }

    //Get Holidays Data
    $holidays = Utils::getUpcomingHolidays();
    $holidaysArr = array();

    while ($rows = mysqli_fetch_assoc($holidays)) {
        $holidaysArr[] =  $rows;
    }

    $leaveTypes = Utils::getLeaveTypes();
    $employeeBalance = Utils::getLeaveBalanceOfEmployee($user->employeeId);
    $holidays = Utils::getUpcomingHolidays();

?>

<script>

    var appID = "<?php echo $appID ?>"
    var isExtension = "<?php echo $isExtension ?>"

    isExtension = isExtension === "true" ? true : false


    var user = <?php echo json_encode($user); ?>;
    var leaveTypeDeatils = <?php echo json_encode($leaveTypesArr); ?>;
    var employeeBalance = <?php echo json_encode($employeeBalanceArr); ?>;

    var holidays = <?php echo json_encode($holidaysArr); ?>;

    holiDaysDate = []

    //Get all the holidays in array
    holidays.forEach(holiday => {
        holiDaysDate.push(new Date(holiday.date).toISOString().slice(0, 10))
    });

    var leaveNames = []

    //Get all the leave type in DB in array
    leaveTypeDeatils.forEach(leaveDetail => {
        leaveNames[leaveDetail.leaveID + ''] = leaveDetail.leaveType
    });

    // ------------- For Current Application Data ---------------------------

    var appLeaveTypesData = <?php echo json_encode($appLeaveTypeArr); ?>;

    var deletedFiles = []
    
    var leaveTypesData = new Map();
    
    //Get all the leave type for application in array
    appLeaveTypesData.forEach((leaveDetail , idx) => {
        leaveTypesData.set(leaveDetail.leaveID + '' , leaveDetail) 
    });
    

    // Reason
    
    var applicationData = <?php echo json_encode($appData); ?>;
    applicationData = applicationData[0]

    // Lecture Adjustments

    var appLecAdjData = <?php echo json_encode($appLecAdjArr); ?>;

    appLecAdjData = appLecAdjData.filter(function (el) {
        return el != null;
    });

    // Tasks Adjustments

    var appTaskAdjData = <?php echo json_encode($appTaskAdjArr); ?>;

    appTaskAdjData = appTaskAdjData.filter(function (el) {
        return el != null;
    });

    // Additional Approval

    var appAddAppData = <?php echo json_encode($appAddAppArr); ?>;

    appAddAppData = appAddAppData.filter(function (el) {
        return el != null;
    });

    // Additional Approval

    var appFilesData = <?php echo json_encode($appFilesArr); ?>;

    appFilesData = appFilesData.filter(function (el) {
        return el != null;
    });

</script>


<!DOCTYPE html>
<html lang="en">

<head>

    <title>Edit Leave</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- all common CSS link  -->
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/Admin/manageMasterData.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- For Spinner -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- all common Script  -->

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Bootstrap JavaScript (if you're using Bootstrap) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

</head>

<body>

    <?php 

        require('../../includes/spinner.php');
    
    ?>

    <!--Including sidenavbar -->
    <?php

        if( $user->role === Config::$_HOD_ ) include "../../includes/HOD/sidenavbar.php";
        if( $user->role === Config::$_FACULTY_ ) include "../../includes/Staff/sidenavbar.php";
    ?>

    <section class="home-section">

        <!--Including header -->

        <?php
        include "../../includes/header.php";
        ?>

        <!-- Below code for dashboard -->
        <div class="container">

            <!-- Current Balance -->
            <form method="POST" class="bg-white shadow pl-5 pr-5  pb-5 pt-2 mt-4 rounded-lg " style="border-right:6px solid #11101D;">

                <h4 class="pb-3 pt-2 mt-4" style="color: #11101D;">Apply for Leave</h4>

                <!-- Uneditable Info -->
                <div class="form-row">

                    <!-- Input Email -->
                    <div class="form-group col-md-4">

                        <input type="email" readonly class="form-control border-top-0 border-right-0 border-left-0 border border-dark bg-white" id="email" placeholder=" Email" name="email" value="
                        <?php echo $user->email  ?>">

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
                        <input type="text" readonly name="date" class="form-control bg-white border-top-0 border-right-0 border-left-0  border border-dark" id="inputPassword4" placeholder="Today Date" value="<?php echo date('d-M-Y') ?>">

                    </div>

                </div>

                <!-- Leave Type Box -->
                <div class="form-row border py-1 px-3 mt-2 rounded">

                    <h6 class="pb-3 pt-2 col-md-12" style="color: #11101D;"> Choose Leave Types </h6>

                    <!-- Container for all Leave Types -->
                    <div class="leavetypesContainer col-md-12">

                        <!-- Leave Type Row ( Each Leave Type ) -->
                        <div id="leavetypeItem-0" class="leavetypeItem  form-row flex justify-content-between align-items-end mu-2 mb-3">

                            <!-- Leave Type -->
                            <select id="leaveType-0" name="leaveID" class=" leaveType  border-top-0 border-right-0 border-left-0 border border-dark col-md-2" data-toggle="tooltip" data-placement="top" title="Select Leave Type">


                                <?php

                                while ($row = mysqli_fetch_assoc($leaveTypes)) {

                                    echo "<option value='" . $row['leaveID'] . "' disable> " . $row['leaveType'] . " </option>";
                                }

                                ?>



                            </select>

                            <p id='fromDate-0-label' class=" border-top-0 border-right-0 border-left-0  border border-dark col-md-2 mb-0 text-center"  ></p>

                            
                            <!-- From Date -->
                            <input type="date" name="fromDate" data-toggle="tooltip" data-placement="top" title="From Date" placeholder="From Date" class=" border-top-0 border-right-0 border-left-0  border border-dark " id="fromDate-0" onchange="updateFromLabel(this)" min="<?php echo date('Y-m-d') ?>" max="<?php echo date('Y-m-d' , strtotime('+1 month', time() ) ) ?> ">

                            <!-- From Date Type -->
                            <select id="fromDateType-0" name="fromDateType" class=" fromDateType  border-top-0 border-right-0 border-left-0 border border-dark col-md-2" data-toggle="tooltip" data-placement="top" title="Select From Date Type">

                                <option value='FULL' disable> FULL </option>
                                <option value='FIRST HALF' disable> FIRST HALF </option>
                                <option value='SECOND HALF' disable> SECOND HALF </option>

                            </select>

                            <!-- To Date -->
                            <p id='toDate-0-label' name="toDate-label" class=" border-top-0 border-right-0 border-left-0  border border-dark col-md-2 mb-0 text-center"  ></p>

                            <input type="date" name="toDate" data-toggle="tooltip" data-placement="top" title="To Date" placeholder="To Date" class=" border-top-0 border-right-0 border-left-0  border border-dark " id="toDate-0" min="<?php echo date('Y-m-d') ?> " onchange="updateToLabel(this)" max="<?php echo date('Y-m-d' , strtotime('+1 month', time() ) ) ?>">

                            <!-- From Date Type -->
                            <select id="toDateType-0" name="toDateType" class=" toDateType  border-top-0 border-right-0 border-left-0 border border-dark col-md-2" data-toggle="tooltip" data-placement="top" title="Select To Date Type">

                                <option value='FULL' disable> FULL </option>
                                <option value='FIRST HALF' disable> FIRST HALF </option>
                                <option value='SECOND HALF' disable> SECOND HALF </option>

                            </select>

                            <button type="button" id="leavetyperemove-0" class=" leavetypeRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row'> <i class="fas fa-minus mr-1"></i> Remove </button>

                        </div>


                    </div>

                    <!-- Add Row Button -->
                    <button type="button" id="addleaveTypeRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row'> Add Row </button>

                </div>

                <!-- Total Days -->
                <div class="form-row">

                    <div class="form-group col-md-12 mt-3 md-0">

                        <p class="form-control border h-100 bg-white mb-0" id="totalDays" name="totalDays"> Total Days </p>

                    </div>

                </div>

                <!-- Reason -->
                <div class="form-row">
                    <div class="form-group col-md-12 pt-2">

                        <textarea type="text" name="reason" placeholder="Reason" class="form-control border border-dark" id="reason"></textarea>

                    </div>
                </div>

                <!-- Lecture Adjustments -->
                <div class="form-row border py-1 px-3 mb-4 rounded">

                    <h6 class=" pb-3 pt-2 col-md-12" style="color: #11101D;"> <input id="lecAdj" name="lecAdj" class="pe-auto" type="checkbox"> Lecture Adjustments </h6>

                    <!-- Container for all lecAdj Rows -->
                    <div class="lecAdjContainer col-md-12  ">

                        <!-- lecAdj Row ( Each lecAdj ) -->
                        <div id="lecAdjItem-0" class="lecAdjItem border pb-2 pt-3 px-4  form-row flex justify-content-between align-items-end mb-3">

                            <div class="form-row col-md-10 justify-content-between mb-2 pt-3">

                                <!-- Adjust With Email -->
                                <select id="lecAdjEmail-0" name="lecAdjEmail" class=" lecAdjEmail  border-top-0 border-right-0 border-left-0 border border-dark mb-4 col-md-5" data-toggle="tooltip" data-placement="top" title="Select Adjusted With">

                                    <option value=""> Adjust With </option>

                                    <?php

                                    $emps = Utils::getAllActiveEmployees();

                                    while ($row = mysqli_fetch_assoc($emps)) {
                                        if( $row['employeeID'] === $user->employeeId || $row['role'] === Config::$_ADMIN_ ) continue;
                                        echo "<option value='" . $row['employeeID'] . "' > " . $row['fullName'] . " </option>";
                                    }

                                    ?>



                                </select>

                                <!-- Semester -->
                                <select id="lecAdjSem-0" name="lecAdjSem" class=" lecAdjSem  border-top-0 border-right-0 border-left-0 border border-dark mb-4 col-md-3" data-toggle="tooltip" data-placement="top" title="Semester">

                                    <option value="0"> Select Semester </option>
                                    <option value="1"> 1st Sem </option>
                                    <option value="2"> 2nd Sem </option>
                                    <option value="3"> 3rd Sem </option>
                                    <option value="4"> 4th Sem </option>
                                    <option value="5"> 5th Sem </option>
                                    <option value="6"> 6th Sem </option>
                                    <option value="7"> 7th Sem </option>
                                    <option value="8"> 8th Sem </option>

                                </select>

                                <!-- Subject -->
                                <input type="text" name="sub" data-toggle="tooltip" data-placement="top" title="Subject" placeholder="Subject" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-4 col-md-3" id="sub-0">


                                <!-- lec Date -->

                                <p id='lecDate-0-label' class="h-50 border-top-0 border-right-0 border-left-0  border border-dark col-md-5 mb-0 text-left text-black-50"  > Lecture Date </p>

                                <input type="date" name="lecDate" data-toggle="tooltip" data-placement="top" title="Lecture Date" placeholder="Lecture Date" class='border-0' id="lecDate-0" onchange="updateFromLabel(this)" >

                                <!-- Lec Start Time -->
                                <input type="text" name="lecStartTime" data-toggle="tooltip" data-placement="top" title="Lecture Start Time" placeholder="Lecture Start Time" onfocus="(this.type='time')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-2 col-md-3" id="lecStartTime-0">

                                <!-- Lec End Time -->
                                <input type="text" name="lecEndTime" data-toggle="tooltip" data-placement="top" title="Lecture End Time" placeholder="Lecture End Time" onfocus="(this.type='time')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-2 col-md-3" id="lecEndTime-0">

                            </div>

                            <button type="button" id="lecAdjRemove-0" class=" lecAdjRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row'> <i class="fas fa-minus mr-1"></i> Remove </button>

                        </div>


                    </div>

                    <!-- Add Row Button -->
                    <button type="button" id="lecAdjRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row'> Add Row </button>


                </div>

                <!-- Task Adjustments -->
                <div class="form-row border py-1 px-3 mb-4 rounded">

                    <h6 class=" pb-3 pt-2 col-md-12" style="color: #11101D;"> <input id="taskAdj" class="pe-auto" name="taskAdj" type="checkbox"> Task Adjustments </h6>

                    <!-- Container for all taskAdj Rows -->
                    <div class="taskAdjContainer col-md-12  ">

                        <!-- taskAdj Row ( Each taskAdj ) -->
                        <div id="taskAdjItem-0" class="taskAdjItem border pb-2 pt-3 px-4  form-row flex justify-content-between align-items-center mb-3">

                            <div class="form-row col-md-10 justify-content-between mb-2 pt-3">

                                <!-- Adjust With Email -->
                                <select id="taskAdjEmail-0" name="taskAdjEmail" class=" taskAdjEmail  border-top-0 border-right-0 border-left-0 border border-dark mb-4 col-md-4" data-toggle="tooltip" data-placement="top" title="Select Adjusted With">

                                    <option value=""> Adjust With </option>

                                    <?php

                                    $emps = Utils::getAllActiveEmployees();
                                    
                                    while ($row = mysqli_fetch_assoc($emps)) {
                                        if( $row['employeeID'] === $user->employeeId || $row['role'] === Config::$_ADMIN_ ) continue;
                                        echo "<option value='" . $row['employeeID'] . "' > " . $row['fullName'] . " </option>";
                                    }

                                    ?>



                                </select>

                                <p id='taskFromDate-0-label' class="h-50 border-top-0 border-right-0 border-left-0  border border-dark col-md-3 mb-0 text-left text-black-50"  > From Date </p>

                                <input type="date" name="taskFromDate" data-toggle="tooltip" data-placement="top" title="From Date" placeholder="From Date" class='border-0 mb-4' id="taskFromDate-0" onchange="updateFromLabel(this)" >

                                <!-- To Date -->
                                <p id='taskToDate-0-label' class="h-50 border-top-0 border-right-0 border-left-0  border border-dark col-md-3 mb-0 text-left text-black-50"  > To Date </p>

                                <input type="date" name="taskToDate" data-toggle="tooltip" data-placement="top" title="To Date" placeholder="To Date" class='border-0 mb-4 ' id="taskToDate-0" onchange="updateFromLabel(this)" >


                                <!-- Task -->
                                <textarea name="task" data-toggle="tooltip" data-placement="top" title="Task" placeholder="Task" class=" border border-dark mb-4 col-md-12 pt-2" id="task-0"></textarea>

                            </div>

                            <button type="button" id="taskAdjRemove-0" class=" lecAdjRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row'> <i class="fas fa-minus mr-1"></i> Remove </button>

                        </div>


                    </div>

                    <!-- Add Row Button -->
                    <button type="button" id="taskAdjRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row'> Add Row </button>


                </div>


                <!-- Additional Approvals -->
                <div class="form-row border py-1 px-3 mb-4 rounded">

                    <h6 class=" pb-3 pt-2 col-md-12" style="color: #11101D;"> <input id="addApproval" name="addApproval" class="pe-auto" type="checkbox"> Additional Approvals ( Optional )</h6>

                    <!-- Container for all Approval Rows -->
                    <div class="approvalsContainer col-md-12">

                        <!-- Approval Row ( Each Approval ) -->
                        <div id="approvalItem-0" class="approvalItem  form-row flex justify-content-between align-items-end mu-2 mb-3">

                            <!-- Approver Email -->
                            <select id="approvalEmail-0" name="approvalEmail" class=" approvalEmail  border-top-0 border-right-0 border-left-0 border border-dark col-md-9" data-toggle="tooltip" data-placement="top" title="Select Approver Email">


                                <?php

                                $emps = Utils::getAllActiveEmployees();

                                while ($row = mysqli_fetch_assoc($emps)) {
                                    if( $row['employeeID'] === $user->employeeId || $row['role'] === Config::$_ADMIN_ ) continue; 
                                    echo "<option value='" . $row['employeeID'] . "' disable> " . $row['fullName'] . " </option>";
                                }

                                ?>



                            </select>

                            <button type="button" id="remove-0" class=" approvalRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row'> <i class="fas fa-minus mr-1"></i> Remove </button>

                        </div>


                    </div>

                    <!-- Add Row Button -->
                    <button type="button" id="addApprovalRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row'> Add Row </button>


                </div>

                <!-- Attach Files-->
                <div class="form-row border py-1 px-3 mb-4 rounded">

                    <h6 class=" pb-3 pt-2 col-md-12" style="color: #11101D;"> <input id="files" name="files" class="pe-auto" type="checkbox"> Attach Files ( Optional ) </h6>

                    <!-- Container for all Files Rows -->
                    <div class="filesContainer col-md-12">

                        <!-- Files Row ( Each Files ) -->
                        <div id="filesItem-0" class="filesItem  form-row flex justify-content-between align-items-end mu-2 mb-3">

                            <!-- Files -->
                            <input type="file" id="file-0" class="file" accept=".pdf, .doc, image/*">

                            <button type="button" id="fileRemove-0" class=" filesRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row'> <i class="fas fa-minus mr-1"></i> Remove </button>

                        </div>


                    </div>

                    <!-- Add Row Button -->
                    <button type="button" id="filesRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row'> Add Row </button>


                </div>

                <!-- Button trigger modal -->
                
                <!-- <button id="leaveApplyBtn" name="submit" class="btn mt-2" style="background-color: #11101D; color: white; " onclick="openModal('Your leave application has been <span style=\'color: green;\'>successfully processed</span>.','<span style=\'color: green;\'>Application Processed</span>')" data-toggle="modal" data-target="#exampleModal">Apply</button> -->
                <button id="leaveApplyBtn" name="submit" class="btn mt-2" style="background-color: #11101D; color: white; " data-toggle="modal" data-target="#exampleModal">Apply</button>
                

            </form>

        </div>

    </section>
    <!-- Modal -->

    <?php
        include "../../includes/model.php";
    ?>

    <script>

        function updateFromLabel(e){

            let id = e.id
            id = id+'-label'

            let selectedDate = new Date(e.value);
            let yyyy = selectedDate.getFullYear();
            let mm = selectedDate.getMonth() + 1; // Months start at 0!
            let dd = selectedDate.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            let formattedToday = dd + '/' + mm + '/' + yyyy;
            document.getElementById(id).innerHTML = formattedToday

        }

        function updateToLabel(e){

            
            let id = e.id
            id = id+'-label'

            let selectedDate = new Date(e.value);
            let yyyy = selectedDate.getFullYear();
            let mm = selectedDate.getMonth() + 1; // Months start at 0!
            let dd = selectedDate.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            let formattedToday = dd + '/' + mm + '/' + yyyy;

            if( selectedDate instanceof Date && !isNaN(dd) )document.getElementById(id).innerHTML = formattedToday

        }

    </script>

    <script>
        $(document).ready(function() {

            // ----------- Pre rendering of Application ----------------------------

            leaveTypes = new Map() // array to avoid duplicate leave types ( Using Map to maintain the order ) 
            leaveTypes.set('final' , {})  //final object will have first fromDate to last toDate

            var isSafeToAddNewLeaveTypeRow = true //if false meanse the currrent data is not validated hence don't allow adding another row
            

            for( let i = 1 ; i < leaveTypesData.size ; i++ ){
                addEmptyRows()
            }

            for( let i = 1 ; i < appLecAdjData.length ; i++ ){
                addEmptyLecAdjRow()
            }

            for( let i = 1 ; i < appTaskAdjData.length ; i++ ){
                addEmptyTaskAdjRow()
            }

            for( let i = 1 ; i < appAddAppData.length ; i++ ){
                addEmptyAddAppRow()
            }

            for( let i = 1 ; i <= appFilesData.length ; i++ ){
                addEmptyFilesRow()
            }

            populateData()

            function populateData(){

                // ------------------ Populate Leave Type Box ------------------

                let i = -1;

                for( let [key , value] of leaveTypesData ){

                    i++;
                    let prevValue = $(`#leavetypeItem-${i}`)[0]
                    let childrens = prevValue.children

                    //For every element in row
                    for (let j = 0; j < childrens.length - 1; j++) {


                        switch(j) {
                        case 0: // For Leave type
                            childrens[j].value = parseInt(leaveTypesData.get(key).leaveID) ;
                            break;
                        case 1: // For StartDay Label
                            childrens[2].value = leaveTypesData.get(key).startDate ;
                            updateFromLabel(childrens[2])
                            break;
                        case 3: // For StartDay Type
                            childrens[j].value = leaveTypesData.get(key).startDateType ;
                            break;
                        case 4: // For endDay Label
                            childrens[5].value = leaveTypesData.get(key).endDate ;
                            updateToLabel(childrens[5])
                            break;
                        case 7: // For EndDate Type
                            childrens[j].value = leaveTypesData.get(key).endDateType ;
                            break;

                        }

                        handleLeaveDataChange(`_-${i}`);

                    }


                }

                //------------------  Populate Reason ------------------
                let reason = $("#reason")
                reason[0].value = applicationData.reason

                // ------------------ Populate Lecture Adjustments ------------------
                for( let i = 0 ; i < appLecAdjData.length ; i++ ){

                    let prevValue = $(`#lecAdjItem-${i} > div`)[0]
                    let childrens = prevValue.children

                    // For every element in row
                    for (let j = 0; j < childrens.length ; j++) {

                        switch(j) {
                        case 0: 
                            childrens[j].value = parseInt(appLecAdjData[i].employeeID) ;
                            break;
                        case 1: 
                            childrens[j].value = appLecAdjData[i].semester ;
                            break;
                        case 2: 
                            childrens[j].value = appLecAdjData[i].subject ;
                            break;
                        case 3: 
                            break;
                        case 4: 
                            childrens[j].value = appLecAdjData[i].date ;
                            updateFromLabel(childrens[j])
                            break;
                        case 5: 
                            childrens[j].value = appLecAdjData[i].startTime ;
                            break;
                        case 6: 
                            childrens[j].value = appLecAdjData[i].endTime ;
                        break;

                        }

                    }


                }

                // ------------------ Populate Tasks Adjustments ------------------
                for( let i = 0 ; i < appTaskAdjData.length ; i++ ){

                    let prevValue = $(`#taskAdjItem-${i} > div`)[0]
                    let childrens = prevValue.children

                    
                    // For every element in row
                    for (let j = 0; j < childrens.length ; j++) {

                        switch(j) {
                        case 0: 
                            childrens[j].value = parseInt(appTaskAdjData[i].employeeID) ;
                            break;
                        case 1: 
                            break;
                        case 2: 
                            childrens[j].value = appTaskAdjData[i].startDate ;
                            updateFromLabel(childrens[j])
                            break;
                        case 3: 
                            break;
                        case 4: 
                            childrens[j].value = appTaskAdjData[i].endDate ;
                            updateFromLabel(childrens[j])
                            break;
                        case 5: 
                            childrens[j].value = appTaskAdjData[i].task ;
                            break;
                        }

                    }


                }

                // ------------------ Populate Additional Approvals Adjustments ------------------
                for( let i = 0 ; i < appAddAppData.length ; i++ ){

                    let prevValue = $(`#approvalItem-${i}`)[0]
                    let childrens = prevValue.children

                    
                    // For every element in row
                    for (let j = 0; j < childrens.length ; j++) {

                        switch(j) {
                        case 0: // For Adjus tWith
                            childrens[j].value = parseInt(appAddAppData[i].approverId) ;
                            break;
                        }

                    }


                }

                // ------------------ Populate Files Attach  ------------------
                for( let i = 0 ; i < appFilesData.length ; i++ ){

                    let prevValue = $(`#filesItem-${i}`)[0]
                    let childrens = prevValue.children
                    
                    // For every element in row
                    for (let j = 0; j < childrens.length ; j++) {

                        switch(j) {
                        case 0:
                            
                            let filename = appFilesData[i].file
                            filename = filename.split('/')
                            filename = filename[filename.length-1] 

                            let newElement = ` <a href='${appFilesData[i].file}' name='${filename}' class="btn btn-success">${filename}</a> `

                            $(`#file-${i}`).replaceWith(newElement)
                            break;
                        }

                    }


                }

            }

            // ---------- Function to add empty new rows

            //function to add empty Leave type Rows
            function addEmptyRows() {

                //Clone the HTML structure of row
                let leavetypeItem = $('.leavetypeItem:last').clone() //clone of last row
                let leaveTypeNo = (leavetypeItem[0].id).split('-')[1] //get the no. of last row

                leavetypeItem.attr('id', `leavetypeItem-${parseInt(leaveTypeNo) + 1}`); //update the no.of cloned row

                $('.leavetypesContainer').append(leavetypeItem) //append the new row

                let len = leavetypeItem[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i = i + 1) {

                    //Set Dates input as blank for cloned row
                    if (leavetypeItem[0].children[i].name === "toDate") leavetypeItem[0].children[i].value = ""

                    let id = (leavetypeItem[0].children[i].id).toString()
                    let newId = (id.replace(leaveTypeNo, `${parseInt(leaveTypeNo) + 1}`)) //change the id according to no.

                    leavetypeItem[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        leavetypeItem[0].children[i].onclick = (e) => removeLeaveTypeRow(e);
                    } else {
                        leavetypeItem[0].children[i].onchange = (e) => handleLeaveDataChange(e.target.id);
                    }

                }

            }

            //function to add empty Lecture Adjustment Rows
            function addEmptyLecAdjRow(  ){

                // leaveTypes
                let prevValue = $('.lecAdjItem:last')[0]
                let children = $('.lecAdjItem:last')[0].children[0].children
                let totalChildren = children.length

                //Clone the HTML structure of row
                let lecAdjItem = $('.lecAdjItem:last').clone()
                let lecAdjNo = (lecAdjItem[0].id).split('-')[1] //get the no. of row

                lecAdjItem.attr('id', `lecAdjItem-${parseInt(lecAdjNo) + 1}`); //update the no.of row

                $('.lecAdjContainer').append(lecAdjItem) //append the new row

                let len = lecAdjItem[0].children[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i++) {

                    lecAdjItem[0].children[0].children[i].value = ""
                    let id = (lecAdjItem[0].children[0].children[i].id).toString()
                    let newId = (id.replace(lecAdjNo, `${parseInt(lecAdjNo) + 1}`)) //change the id according to no.

                    lecAdjItem[0].children[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        lecAdjItem[0].children[1].onclick = (e) => removeLecAdjRow(e);
                    }

                }

                id = (lecAdjItem[0].children[1].id).toString()
                newId = (id.replace(lecAdjNo, `${parseInt(lecAdjNo) + 1}`)) //change the id according to no.

                lecAdjItem[0].children[1].id = newId;

            }

            //function to add empty Lecture Adjustment Rows
            function addEmptyTaskAdjRow(  ){

                let taskChildren = $('.taskAdjItem:last')[0].children[0].children
                let totalTaskChildren = taskChildren.length

                //Clone the HTML structure of row
                let taskAdjItem = $('.taskAdjItem:last').clone()
                let taskAdjNo = (taskAdjItem[0].id).split('-')[1] //get the no. of row

                taskAdjItem.attr('id', `taskAdjItem-${parseInt(taskAdjNo) + 1}`); //update the no.of row

                $('.taskAdjContainer').append(taskAdjItem) //append the new row

                let len = taskAdjItem[0].children[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i++) {

                    taskAdjItem[0].children[0].children[i].value = ""
                    let id = (taskAdjItem[0].children[0].children[i].id).toString()
                    let newId = (id.replace(taskAdjNo, `${parseInt(taskAdjNo) + 1}`)) //change the id according to no.

                    taskAdjItem[0].children[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        taskAdjItem[0].children[1].onclick = (e) => removeTaskAdjRow(e);
                    }

                }


                id = (taskAdjItem[0].children[1].id).toString()
                newId = (id.replace(taskAdjNo, `${parseInt(taskAdjNo) + 1}`)) //change the id according to no.

                taskAdjItem[0].children[1].id = newId;
            }

            //function to add empty Additional Approvals Rows
            function addEmptyAddAppRow(){

                let prevValue = $('.approvalItem:last > select')[0].value

                //Clone the HTML structure of row
                let approvalItem = $('.approvalItem:last').clone()
                let approvalNo = (approvalItem[0].id).split('-')[1] //get the no. of row

                approvalItem.attr('id', `approvalItem-${parseInt(approvalNo) + 1}`); //update the no.of row

                $('.approvalsContainer').append(approvalItem) //append the new row

                let len = approvalItem[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i = i + 1) {

                    let id = (approvalItem[0].children[i].id).toString()
                    let newId = (id.replace(approvalNo, `${parseInt(approvalNo) + 1}`)) //change the id according to no.

                    approvalItem[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        approvalItem[0].children[i].onclick = (e) => removeApprovalRow(e);
                    }

                }


            }

            function addEmptyFilesRow(){

                //Clone the HTML structure of row
                let FilesItem = $('.filesItem:last').clone()
                let FilesNo = (FilesItem[0].id).split('-')[1] //get the no. of row

                FilesItem.attr('id', `filesItem-${parseInt(FilesNo) + 1}`); //update the no.of row

                $('.filesContainer').append(FilesItem) //append the new row

                let len = FilesItem[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i = i + 1) {

                    FilesItem[0].children[i].value = ""

                    let id = (FilesItem[0].children[i].id).toString()
                    let newId = (id.replace(FilesNo, `${parseInt(FilesNo) + 1}`)) //change the id according to no.

                    FilesItem[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        FilesItem[0].children[i].onclick = (e) => removeFilesRow(e);
                    }

                }

            }


            //* ------------------------- Additional Approvals Box --------------------------------

            $('#addApproval')[0].checked = true
            
            //Additional Approvals Container
            if( appAddAppData.length == 0 ){
                $('.approvalsContainer').hide()
                $('#addApprovalRowBtn').hide()
                $('#addApproval')[0].checked = false
            }

            $('#addApproval').click(() => {

                $('#addApprovalRowBtn').toggle()
                $('.approvalsContainer').toggle()

            })

            //Logic to add new Blank rows
            $('#addApprovalRowBtn').click(() => {

                let prevValue = $('.approvalItem:last > select')[0].value

                if (prevValue === "") return

                //Clone the HTML structure of row
                let approvalItem = $('.approvalItem:last').clone()
                let approvalNo = (approvalItem[0].id).split('-')[1] //get the no. of row

                approvalItem.attr('id', `approvalItem-${parseInt(approvalNo) + 1}`); //update the no.of row

                $('.approvalsContainer').append(approvalItem) //append the new row

                let len = approvalItem[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i = i + 1) {

                    let id = (approvalItem[0].children[i].id).toString()
                    let newId = (id.replace(approvalNo, `${parseInt(approvalNo) + 1}`)) //change the id according to no.

                    approvalItem[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        approvalItem[0].children[i].onclick = (e) => removeApprovalRow(e);
                    }

                }

            })

            //to remove for first child
            $('#remove-0').click((e) => {
                removeApprovalRow(e)
            })

                //Function to remove approval rows
            function removeApprovalRow(e) {

                let len = $('.approvalItem').length

                if (len == 1) return

                let id = (e.target.id).split('-')[1]
                $(`#approvalItem-${id}`).remove()

            }

            //* ------------------------- Lecture Adjustment Box --------------------------------

            //Additional Approvals Container

            $('#lecAdj')[0].checked = true
            
            if( appLecAdjData.length == 0 ){
                $('.lecAdjContainer').hide()
                $('#lecAdjRowBtn').hide()
                $('#lecAdj')[0].checked = false
            }
            

            $('#lecAdj').click(() => {

                $('#lecAdjRowBtn').toggle()
                $('.lecAdjContainer').toggle()

            })

        //Logic to add new Blank rows
            $('#lecAdjRowBtn').click(() => {

                addLecAdjRow()

            })

            //to remove for first child
            $('#lecAdjRemove-0').click((e) => {
                removeLecAdjRow(e)
            })

            //Function to remove approval rows
            function removeLecAdjRow(e) {

                let len = $('.lecAdjItem').length

                if (len == 1) return

                let id = (e.target.id).split('-')[1]
                $(`#lecAdjItem-${id}`).remove()

            }


            function addLecAdjRow(  ){

                // leaveTypes
                let prevValue = $('.lecAdjItem:last')[0]
                let children = $('.lecAdjItem:last')[0].children[0].children
                let totalChildren = children.length

                for (let i = 0; i < totalChildren - 1; i++) {

                    if (children[i].value === "") return

                }

                //Clone the HTML structure of row
                let lecAdjItem = $('.lecAdjItem:last').clone()
                let lecAdjNo = (lecAdjItem[0].id).split('-')[1] //get the no. of row

                lecAdjItem.attr('id', `lecAdjItem-${parseInt(lecAdjNo) + 1}`); //update the no.of row

                $('.lecAdjContainer').append(lecAdjItem) //append the new row

                let len = lecAdjItem[0].children[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i++) {


                    if (lecAdjItem[0].children[0].children[i].name === "lecDate") {

                        lecAdjItem[0].children[0].children[i].max = new Date(leaveTypes.get('final').endDate).toISOString().slice(0, 10)
                        lecAdjItem[0].children[0].children[i].min = new Date(leaveTypes.get('final').startDate).toISOString().slice(0, 10)

                    }

                    lecAdjItem[0].children[0].children[i].value = ""
                    let id = (lecAdjItem[0].children[0].children[i].id).toString()
                    let newId = (id.replace(lecAdjNo, `${parseInt(lecAdjNo) + 1}`)) //change the id according to no.

                    lecAdjItem[0].children[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        lecAdjItem[0].children[1].onclick = (e) => removeLecAdjRow(e);
                    }

                }


                id = (lecAdjItem[0].children[1].id).toString()
                newId = (id.replace(lecAdjNo, `${parseInt(lecAdjNo) + 1}`)) //change the id according to no.

                lecAdjItem[0].children[1].id = newId;

            }

            //* ------------------------- Task Adjustment Box --------------------------------

            $('#taskAdj')[0].checked = true

            if( appTaskAdjData.length == 0 ){
                $('.taskAdjContainer').hide()
                $('#taskAdjRowBtn').hide()
                $('#taskAdj')[0].checked = false
            }
            

            $('#taskAdj').click(() => {

                $('#taskAdjRowBtn').toggle()
                $('.taskAdjContainer').toggle()

            })

            //Logic to add new Blank rows
            $('#taskAdjRowBtn').click(() => {


                let taskChildren = $('.taskAdjItem:last')[0].children[0].children
                let totalTaskChildren = taskChildren.length

                for (let i = 0; i < totalTaskChildren - 1; i++) {

                    if (taskChildren[i].value === "") return

                }

                //Clone the HTML structure of row
                let taskAdjItem = $('.taskAdjItem:last').clone()
                let taskAdjNo = (taskAdjItem[0].id).split('-')[1] //get the no. of row

                taskAdjItem.attr('id', `taskAdjItem-${parseInt(taskAdjNo) + 1}`); //update the no.of row

                $('.taskAdjContainer').append(taskAdjItem) //append the new row

                let len = taskAdjItem[0].children[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i++) {

                    taskAdjItem[0].children[0].children[i].value = ""
                    let id = (taskAdjItem[0].children[0].children[i].id).toString()
                    let newId = (id.replace(taskAdjNo, `${parseInt(taskAdjNo) + 1}`)) //change the id according to no.

                    taskAdjItem[0].children[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        taskAdjItem[0].children[1].onclick = (e) => removeTaskAdjRow(e);
                    }

                }


                id = (taskAdjItem[0].children[1].id).toString()
                newId = (id.replace(taskAdjNo, `${parseInt(taskAdjNo) + 1}`)) //change the id according to no.

                taskAdjItem[0].children[1].id = newId;

            })

            //to remove for first child
            $('#taskAdjRemove-0').click((e) => {
                removeTaskAdjRow(e)
            })

            //Function to remove approval rows
            function removeTaskAdjRow(e) {

                let len = $('.taskAdjItem').length

                if (len == 1) return

                let id = (e.target.id).split('-')[1]
                $(`#taskAdjItem-${id}`).remove()

            }


            //* ------------------------- Attach Files --------------------------------

            //Additional Approvals Container

            $('#files')[0].checked = true
            
            if( appFilesData.length == 0 ){
                $('.filesContainer').hide()
                $('#filesRowBtn').hide()
                $('#files')[0].checked = false
            }


            $('#files').click(() => {

                $('#filesRowBtn').toggle()
                $('.filesContainer').toggle()

            })

            //Logic to add new Blank rows
            $('#filesRowBtn').click(() => {

                // let prevValue = $('.filesItem:last > input')[0].value

                // if (prevValue === "") return

                //Clone the HTML structure of row
                let FilesItem = $('.filesItem:last').clone()
                let FilesNo = (FilesItem[0].id).split('-')[1] //get the no. of row

                FilesItem.attr('id', `filesItem-${parseInt(FilesNo) + 1}`); //update the no.of row

                $('.filesContainer').append(FilesItem) //append the new row

                let len = FilesItem[0].children.length //get all elements in row like select and buttons

                //for every element
                for (let i = 0; i < len; i = i + 1) {

                    FilesItem[0].children[i].value = ""

                    let id = (FilesItem[0].children[i].id).toString()
                    let newId = (id.replace(FilesNo, `${parseInt(FilesNo) + 1}`)) //change the id according to no.

                    FilesItem[0].children[i].id = newId;

                    //for remove button add onclick function
                    if (i == len - 1) {
                        FilesItem[0].children[i].onclick = (e) => removeFilesRow(e);
                    }

                }

            })

            //to remove for first child
            $('#fileRemove-0').click((e) => {
                removeFilesRow(e)
            })

            //Function to remove files rows
            function removeFilesRow(e) {

                
                let len = $('.filesItem').length
                
                if (len == 1) return
                
                let id = (e.target.id).split('-')[1]
                let fileItem = $(`#filesItem-${id}`)

                if( fileItem[0].children[0].tagName === 'A' ){
                    deletedFiles.push( { name : fileItem[0].children[0].name , action : 'delete' } )
                }

                $(`#filesItem-${id}`).remove()

            }


            //* ------------------------- Leave Types box --------------------------------



            //Add new Row
            $('#addleaveTypeRowBtn').click(() => {
                addLeaveTypeNewRow()
            })

            //Validate data
            $('#leaveType-0 , #fromDate-0 , #fromDateType-0 ,  #toDate-0 ,  #toDateType-0').change((e) => {
                handleLeaveDataChange(e.target.id)
            })

            //to remove for first child
            $('#leavetyperemove-0').click((e) => {
                removeLeaveTypeRow(e)
            })

            //Fucntion to Validate the current data and configure final object
            function handleLeaveDataChange(e) {

                if( e.srcElement !== undefined ){
                    e  = e.srcElement.id
                }

                //get the id of row
                let id = e
                id = parseInt(id.split('-')[1])

                let prevValue = $(`#leavetypeItem-${id}`)[0]
                let childrens = prevValue.children

                //For every element in row
                for (let i = 0; i < childrens.length - 1; i++) {

                    //If input is blank
                    if (childrens[i].value === "") {
                        return
                    }

                }


                let lastRow = $(`#leavetypeItem-${id}`)[0]
                let childrensOfLastRow = lastRow.children


                //If some leave type for the rowNo already exist then remove that

                for (let [key, value] of leaveTypes) {
                    
                    if ( value.rowNo === id) {
                        delete leaveTypes.delete(key);
                        break;
                    }
                }


                //Check if there is already some data for specific leavetype
                if (leaveTypes.get($(`#leaveType-${id}`)[0].value + '') != undefined && leaveTypes.get($(`#leaveType-${id}`)[0].value + '').rowNo !== id) {
                    
                    // alert("Cannot Select Leave Type more than Once !")
                    let message = "Cannot Select Leave Type more than Once !!!"
                    document.querySelector('.modal-body').innerHTML = message;
                    document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                    $('#myModal').modal();
                    return;

                }

                //create a object for that specefic leave type
                leaveTypes.set($(`#leaveType-${id}`)[0].value + '' , {})
                leaveTypes.get($(`#leaveType-${id}`)[0].value + '').rowNo = id;


                //For every element in row i.e , select , inputs,
                for (let i = 0; i < childrensOfLastRow.length - 1; i++) {

                    //If input is blank
                    if (childrensOfLastRow[i].value === "") {
                        return
                    }

                    if (childrensOfLastRow[i].name === "leaveID") {

                        //create new object for new leave type
                        leaveTypes.get(childrensOfLastRow[i].value + '')[childrensOfLastRow[i].name + ''] = childrensOfLastRow[i].value

                        leaveTypes.get(childrensOfLastRow[i].value + '')['leaveType'] = childrensOfLastRow[i].options[childrensOfLastRow[i].value - 1].text

                    }

                    //Add all the data in leave type specific object
                    leaveTypes.get(childrensOfLastRow[0].value + '')[childrensOfLastRow[i].name + ''] = childrensOfLastRow[i].value

                }


                //reset final object
                leaveTypes.set('final' , {});


                let validate = validateLeaveData(leaveTypes) //Validate Data and configure final dates

                //If validations fails
                if (!validate.isValid) {

                    // alert( validate.msg )
                    
                    let message = validate.msg;
                    document.querySelector('.modal-body').innerHTML = message;
                    document.querySelector('.modal-title').innerHTML = "<span>ALERT</span>";

                    $('#myModal').modal();

                    isSafeToAddNewLeaveTypeRow = false //don't allow to add new Row
                    return;

                }

                //Validations were fine hence allow to add new Row
                isSafeToAddNewLeaveTypeRow = true


            }


            //Fucntion to remove leave type Row
            function removeLeaveTypeRow(e) {

                let len = $('.leavetypeItem').length
                if (len == 1) return //there must be atleast 1 row

                let id = (e.target.id).split('-')[1]

                //Remove the leave type specific object
                for (let [key, value] of leaveTypes) {
                    
                    if (value.rowNo === parseInt(id)) {
                        delete leaveTypes.delete(key);
                        break;
                    }

                }

                //Remove Row
                $(`#leavetypeItem-${id}`).remove()


                //reset final object
                leaveTypes.set('final' , {})

                let validate = validateLeaveData(leaveTypes) //Validate Data and configure final dates

                //If validations fails
                if (!validate.isValid) {

                    // alert( validate.msg )
                    // let message = validate.msg;
                    document.querySelector('.modal-body').innerHTML = validate.msg;
                    document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                    $('#myModal').modal('show');
                    isSafeToAddNewLeaveTypeRow = false //don't allow to add new Row
                    return;

                }

                //Validations were fine hence allow to add new Row
                isSafeToAddNewLeaveTypeRow = true

                calculateTotalDays()

            }


            //Validate Current data and return response
            function validateLeaveData(leavedata) {

                

                //For every leave in leave data
                for (let [key , value] of leavedata) {

                    if (key === 'final') continue

                    //Define all data
                    let appliedLeaveData = value
                    let validationsRequired //LeaveType data from DB
                    let balances //Employee Balance data from DB

                    //define data from db
                    for (let i = 0; i < leaveTypeDeatils.length; i = i + 1) {

                        if (leaveTypeDeatils[i].leaveID === key) {
                            validationsRequired = leaveTypeDeatils[i];
                            break;
                        }
                    }

                    //define data from db
                    for (let i = 0; i < employeeBalance.length; i = i + 1) {

                        if (employeeBalance[i].leaveID === key) {
                            balances = employeeBalance[i];
                            break;
                        }
                    }

                    //The Time according to UTC is 5:30:00 ahead for india
                    let currTime = new Date()
                    currTime.setHours(5, 30, 0, 0)

                    let fromDate = new Date(appliedLeaveData.fromDate)
                    fromDate.setHours(5, 30, 0, 0)
                    let toDate = new Date(appliedLeaveData.toDate)
                    toDate.setHours(5, 30, 0, 0)

                    let finalEndDate = new Date(leavedata.get('final').endDate)
                    finalEndDate.setHours(5, 30, 0, 0)


                     //Validate dates sequence from above rows ( from Date of row cannot be less than toDate of prev row )
                     if (leavedata.get('final').endDate != undefined && (finalEndDate.getTime() >= fromDate.getTime())) return {
                        msg: `fromDate of lower Row cannot be less than or equals to endDate of above rows!`,
                        isValid: false
                    }

                    //Validate Waiting Time 
                    if (fromDate.getTime() < (currTime.getTime() + parseInt(validationsRequired.waitingTime) * (24 * 60 * 60 * 1000))) return {
                        msg: `${validationsRequired.leaveType} needs to apply atleast ${validationsRequired.waitingTime} Days prior !`,
                        isValid: false
                    }

                    //ToDate Should be greater or equal to fromDate
                    if (toDate.getTime() < fromDate.getTime()) return {
                        msg: "toDate cannot be less than fromDate !",
                        isValid: false
                    }

                    //calculate difference between days
                    let diffBtnDates = (Math.floor((toDate.getTime() - fromDate.getTime()) / (24 * 60 * 60 * 1000))) + 1

                    //validate Apply Limit
                    if (diffBtnDates > parseInt(validationsRequired.applyLimit)) return {
                        msg: `${validationsRequired.leaveType} has apply limit of ${validationsRequired.applyLimit}`,
                        isValid: false
                    }

                    //Check for insuffcient Balance
                    if (parseInt(balances.balance) < diffBtnDates) return {
                        msg: `Insuffcient balance , Current ${balances.leaveType}s :  ${balances.balance} `,
                        isValid: false
                    }

                    //Add totalDays to leave type specefic object
                    value.totalDays = diffBtnDates;

                    //update final object
                    if (leavedata.get('final').startDate === undefined) {

                        leavedata.get('final').startDate = appliedLeaveData.fromDate
                        leavedata.get('final').startDateType = appliedLeaveData.fromDateType;

                    }

                    leavedata.get('final').endDate = appliedLeaveData.toDate;
                    leavedata.get('final').endDateType = appliedLeaveData.toDateType;

                }

                calculateTotalDays(leavedata)

                return {
                    msg: "Validations Successfull",
                    isValid: true
                }
            }



            //  Add new Row
            function addLeaveTypeNewRow() {

                if (!isSafeToAddNewLeaveTypeRow) return //If it is not safe then don't add row and return

                //Clone the HTML structure of row
                let leavetypeItem = $('.leavetypeItem:last').clone() //clone of last row
                let leaveTypeNo = (leavetypeItem[0].id).split('-')[1] //get the no. of last row

                leavetypeItem.attr('id', `leavetypeItem-${parseInt(leaveTypeNo) + 1}`); //update the no.of cloned row

                $('.leavetypesContainer').append(leavetypeItem) //append the new row

                let len = leavetypeItem[0].children.length //get all elements in row like select and buttons
                let id = parseInt(leaveTypeNo) + 1;

                //for every element
                for (let i = 0; i < len; i = i + 1) {

                    //if form Date set it to immediate next date to toDate from prev row
                    if (leavetypeItem[0].children[i].name === "fromDate") {

                        //get the endDate of last row and get next date
                        let tom = new Date(leavetypeItem[0].children[i+3].value)
                        tom.setDate(tom.getDate() + 1)

                        leavetypeItem[0].children[i].value = tom.toISOString().slice(0, 10)
                        leavetypeItem[0].children[i].disabled = true

                        //format that date into dd/mm/yyyy
                        let yyyy = tom.getFullYear();
                        let mm = tom.getMonth() + 1; // Months start at 0!
                        let dd = tom.getDate();

                        if (dd < 10) dd = '0' + dd;
                        if (mm < 10) mm = '0' + mm;

                        let formattedToday = dd + '/' + mm + '/' + yyyy;

                        leavetypeItem[0].children[i-1].innerHTML = formattedToday
                        
                        //Set endDate for new row as blank
                        
                        leavetypeItem[0].children[i+2].innerHTML = ""



                    }

                    
                    let id = (leavetypeItem[0].children[i].id).toString()
                    let newId = (id.replace(leaveTypeNo, `${parseInt(leaveTypeNo) + 1}`)) //change the id according to no.
                    
                    leavetypeItem[0].children[i].id = newId;

                    //Set Dates input as blank for cloned row
                    if (leavetypeItem[0].children[i].name === "toDate"){

                         leavetypeItem[0].children[i].value = ""
                         $(`#${newId}`).change((e)=>updateToLabel(e.target))

                    }

                    //for remove button add onclick function
                    if (i == len - 1) {
                        leavetypeItem[0].children[i].onclick = (e) => removeLeaveTypeRow(e);
                    } else {
                        leavetypeItem[0].children[i].onchange = (e) => handleLeaveDataChange(e);
                    }

                }


            }


            //* ------------------------- Calculate Total Days --------------------------------

            function calculateTotalDays(leavedata) {

                
                let leaveTypesLen = leavedata.size //No of Leave Type selected
                
                let idx = 1;
                
                let calculations = "" //text for total days input
                
                //for every leave type user selected
                for (let [key , value] of leavedata) {
                    
                    if (key === 'final') continue

                    let holidayLeaves = 0;

                    let startDate = new Date(value.fromDate)
                    startDate.setHours(5, 30, 0, 0)

                    let startDateType = value.fromDateType

                    let endDate = new Date(value.toDate)
                    endDate.setHours(5, 30, 0, 0)

                    let endDateType = value.toDateType


                    let totalDays = (endDate.getTime() - startDate.getTime()) / (24 * 60 * 60 * 1000) + 1 //total difference between fromDate and startDate
                    let leaveName = leaveNames[key] //name of the leave type

                    calculations += `Total ${ leaveName } = <b>${totalDays} Days </b> </br>Holidays =  `

                    let leftHolidays = 0 // holidays counting from left side ( fromDate )

                    let dumStartDate = startDate;
        
                    //Decrement total days if the startingDate ends starts some holiday
                    while (idx == 1) {

                        //if there is holiday then check for next day
                        if ( dumStartDate <= endDate && (holiDaysDate.includes(dumStartDate.toISOString().slice(0, 10)) || dumStartDate.getDay() == 0) ) {

                            calculations += `<b>${ dumStartDate.toISOString().slice(0, 10) }</b> |`
                            dumStartDate.setDate(dumStartDate.getDate() + 1);
                            leftHolidays++;

                        } else {
                            break;
                        }

                    }

                    //If dateType is half then reduce it by 0.5
                    if (idx === 1 && startDateType !== 'FULL' && leftHolidays === 0 && leaveName !== 'Earned Leave') totalDays -= 0.5


                    let rightHolidays = 0 // holidays counting from right side ( toDate )
                    //Decrement total days if the endingDate ends with some holiday

                    let dumEndDate = endDate;
                    
                    while (startDate !== endDate && idx === leaveTypesLen-1) {

                        if ( dumEndDate > startDate && (holiDaysDate.includes(dumEndDate.toISOString().slice(0, 10)) || dumEndDate.getDay() == 0) ) {

                            calculations += `<b>${ dumEndDate.toISOString().slice(0, 10) }</b> |`
                            dumEndDate.setDate(dumEndDate.getDate() - 1);
                            rightHolidays++;
                        } else {
                            break;
                        }

                    }


                    //If dateType is half then reduce it by 0.5
                    if (startDate !== endDate && idx === leaveTypesLen && endDateType !== 'FULL' && rightHolidays === 0 && leaveName !== 'Earned Leave') totalDays -= 0.5

                    //total holidays through application
                    holidayLeaves = leftHolidays + rightHolidays

                    if (holidayLeaves === 0) calculations += "<b>No Holiday Leaves !!</b>"
                    if (holidayLeaves > totalDays) totalDays = 0;

                    let finalTotaldays = (totalDays - holidayLeaves) < 0 ? 0 : (totalDays - holidayLeaves);

                    calculations += `</br>Total Holiday Leaves = <b>${holidayLeaves}</b></br>`
                    calculations += `Total Leaves to be deducted from ${leaveNames[key]} = <b>${ finalTotaldays  }</b></br></br>`

                    value.totalDays = finalTotaldays;

                    leavedata.get('final').totalDays = leavedata.get('final').totalDays != undefined ? leavedata.get('final').totalDays + finalTotaldays : finalTotaldays;


                    idx++;

                }


                $('#totalDays').html(calculations)


            }


            //* ------------------------- API Call --------------------------------


            $('#leaveApplyBtn').click((e) => {

                e.preventDefault()
                handleApply()

            })


            function handleApply() {


                let lecAdjs = [];
                let taskAdjs = [];
                let AddApp = [];
                let files = [];
                let reason

                var body = new FormData();

                if ( leaveTypes.size <= 1) {

                    let message = "You Need to Select atleast one Leave Type to Apply !!"
                    
                    document.querySelector('.modal-body').innerHTML = message;
                    document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                    $('#myModal').modal();
                        return;
                    
                }

                if( leaveTypes.get('final').totalDays === 0 ) {
                    
                    let message = "You cannot apply as 0 Leaves will be deducted !!";
                    
                    document.querySelector('.modal-body').innerHTML = message;
                    document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                    $('#myModal').modal();
                        return;
                    
                }

                //Validating Reason
                if ($('#reason')[0].value === "") {
                    // alert('reason cannot be empty !!')
                    let message = "reason cannot be empty !!"
                    document.querySelector('.modal-body').innerHTML = message;
                document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                $('#myModal').modal();
                    return;
                } else {
                    reason = $('#reason')[0].value
                }


                let len = $('.lecAdjContainer')[0].children.length

                // validate and store Lecture Adjustment Details
                if ($('#lecAdj')[0].checked) {

                    // Get Lec Adjustments Details
                    for (let i = 0; i < len; i++) {

                        let container = $('.lecAdjContainer')[0].children[i].children[0]
                        let isValidated = true
                        let lecAdjDetails = {}

                        for (let j = 0; j < container.children.length; j++) {

                            //Validate Lecture Details
                            if (container.children[j].value === "") {

                                isValidated = false;

                                // alert('Inputs in Lecture Adjustment cannot be Empty')

                                let message = "Inputs in Lecture Adjustment cannot be Empty !!"
                    document.querySelector('.modal-body').innerHTML = message;
                document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                $('#myModal').modal();
                                break;
                            }

                if (container.children[j].name === 'lecDate') {


                    if (new Date(container.children[j].value) < new Date(leaveTypes.get('final').startDate) || new Date(container.children[j].value) > new Date(leaveTypes.get('final').endDate)) {
                        isValidated = false;
                        // alert('Lec Date in Lecture Adjustments should be between startDate and endDate of Application')
                        let message = "Lec Date in Lecture Adjustments should be between startDate and endDate of Application !!"
                        document.querySelector('.modal-body').innerHTML = message;
                        document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                        $('#myModal').modal();
                                    break;
                    }

                }

                lecAdjDetails[container.children[j].name + ''] = container.children[j].value

                }

                        if (isValidated) lecAdjs.push(lecAdjDetails);
                        else return

                    }

                }



                let totalTasks = $('.taskAdjContainer')[0].children.length

                // validate and store task Adjustment Details
                if ($('#taskAdj')[0].checked) {

                    // Get task Adjustments Details
                    for (let i = 0; i < totalTasks; i++) {

                        let container = $('.taskAdjContainer')[0].children[i].children[0]
                        let isValidated = true
                        let taskAdjDetails = {}

                        for (let j = 0; j < container.children.length; j++) {

                            if (container.children[j].value === "") {
                                isValidated = false;
                                // alert('Inputs in Task Adjustment cannot be Empty')
                                let message = "Inputs in Task Adjustment cannot be Empt!!"
                    document.querySelector('.modal-body').innerHTML = message;
                document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                $('#myModal').modal();
                                break;
                            }

                            if (container.children[j].name === 'taskFromDate' || container.children[j].name === 'taskToDate') {

                                if ((new Date(container.children[j].value) < new Date(leaveTypes.get('final').startDate)) || (new Date(container.children[j].value) > new Date(leaveTypes.get('final').endDate))) {
                                    isValidated = false;
                                    // alert('Dates in Task Adjustments should be between startDate and endDate of Application !')
                                    let message = "Dates in Task Adjustments should be between startDate and endDate of Application !!"
                    document.querySelector('.modal-body').innerHTML = message;
                document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                $('#myModal').modal();
                                    break;
                                }

                            }

                            taskAdjDetails[container.children[j].name + ''] = container.children[j].value

                        }

                        if (isValidated) taskAdjs.push(taskAdjDetails);
                        else return


                    }

                }


                let totalApp = $('.approvalsContainer')[0].children.length

                // validate and store Additonal Approval Details
                if ($('#addApproval')[0].checked) {

                    // Get Additional Approvals Details
                    for (let i = 0; i < totalApp; i++) {

                        let container = $('.approvalsContainer')[0].children[i]

                        if (!AddApp.includes(container.children[0].value)) AddApp.push(container.children[0].value)

                    }

                }

                let totalFiles = $('.filesContainer')[0].children.length

                // validate and store Files Details
                if ($('#files')[0].checked) {

                    // Get Files Details
                    for (let i = 0; i < totalFiles; i++) {

                        let container = $('.filesContainer')[0].children[i]


                        if (  container.children[0].tagName != 'A' && !files.includes(container.children[0].value)) {

                            console.log(container.children[0])
                            body.append('files[]', container.children[0].files[0])
                            files.push(container.children[0].files[0])

                        }

                    }

                }



                let leaveTypesObj = Object.fromEntries(leaveTypes); //Convert map to js obj as we have intially wrote the whole logic using js object logic

                body.append('user', JSON.stringify(user));
                body.append('appID', JSON.stringify(appID));
                body.append('applicationDate', JSON.stringify(new Date().toISOString().slice(0, 10)));
                body.append('leaveTypes', JSON.stringify(leaveTypesObj));
                body.append('reason', JSON.stringify(reason));
                body.append('lecAdjs', JSON.stringify(lecAdjs));
                body.append('taskAdjs', JSON.stringify(taskAdjs));
                body.append('AddApp', JSON.stringify(AddApp));
                body.append('deletedFiles', JSON.stringify(deletedFiles));

                document.getElementById('spinner-container').style.display = 'flex'
                document.querySelector('.home-section').style.display = 'none'

                let url = "./validateEditApplication.php";

                if( isExtension ){ 
                    url = "./validateApplyLeave.php"
                    body.append('extensionOf', JSON.stringify(appID));
                }

                // Fire off the request to ./validateEditApplication.php
                request = $.ajax({
                    url: url,
                    type: "post",
                    data: body,
                    processData: false,
                    contentType: false,

                    success: function(response) {

                        console.log(response);
                        let message = response;

                        document.querySelector('.modal-body').innerHTML = message;
                        document.querySelector('.modal-title').innerHTML = "<span style=\'color: green;\'>Application Submitted</span>";
                        document.getElementById('spinner-container').style.display = 'none'
                        document.querySelector('.home-section').style.display = 'flex'
                        document.querySelector('#close-btn').onclick = ()=>{
                        //   window.location.href = './dashboard.php'
                        }
                        $('#myModal').modal();
                    },

                    error: function(jqXHR, textStatus, errorThrown) {

                        console.log(textStatus, errorThrown);
                        let message = jqXHR.responseText || "Error occurred during Applying Leave!!";
                        let message = "Error occured during Applying Leave !!"
                        document.querySelector('.modal-body').innerHTML = message;
                        document.querySelector('.modal-title').innerHTML = "<span style=\'color: red;\'>ALERT</span>";
                        document.getElementById('spinner-container').style.display = 'none'
                        document.querySelector('.home-section').style.display = 'flex'

                        $('#myModal').modal();
                        // windows.location.href = "./apply_leave.php"
                    }
                });


            }

        })
    </script>

</body>

</html>