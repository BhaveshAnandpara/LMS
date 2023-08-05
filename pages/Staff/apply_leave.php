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
    
    $leaveTypes = Utils::getLeaveTypes();
    $employeeBalance = Utils::getLeaveBalanceOfEmployee( $user->employeeId );

?>

<script>

    var leaveTypeDeatils = <?php echo json_encode( $leaveTypesArr ); ?>;
    var employeeBalance = <?php echo json_encode( $employeeBalanceArr ); ?>;

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
            <form action="../../utils/insertLeave.php" method="POST" class="bg-white shadow pl-5 pr-5  pb-5 pt-2 mt-5 rounded-lg " style="border-right:6px solid #11101D;">

                <h4 class="pb-3 pt-2" style="color: #11101D;">Apply for Leave</h4>
                
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

                </div>

                <!-- Leave Type Box -->
                <div class="form-row border py-1 px-3 mt-2 rounded">

                    <h6 class="pb-3 pt-2 col-md-12" style="color: #11101D;"> Choose Leave Types </h6>

                    <!-- Container for all Leave Types -->
                    <div class="leavetypesContainer col-md-12">

                        <!-- Leave Type Row ( Each Leave Type ) -->
                        <div id="leavetypeItem-0" class="leavetypeItem  form-row flex justify-content-between align-items-end mu-2 mb-3">

                            <!-- Leave Type -->
                            <select  id="leaveType-0" name="leaveID" class=" leaveType  border-top-0 border-right-0 border-left-0 border border-dark col-md-2" data-toggle="tooltip" data-placement="top" title="Select Leave Type"   >


                                <?php

                                    while( $row = mysqli_fetch_assoc( $leaveTypes ) ){

                                        echo "<option value='" .$row['leaveID']. "' disable> ".$row['leaveType']." </option>";

                                    }
                                
                                ?>

                                

                            </select>

                            <!-- From Date -->
                            <input type="text"  name="fromDate" data-toggle="tooltip" data-placement="top" title="From Date" placeholder="From Date" onfocus="(this.type='date')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark col-md-2" id="fromDate-0"  min="<?php echo date('Y-m-d') ?>"    >

                            <!-- From Date Type -->
                            <select  id="fromDateType-0" name="fromDateType" class=" fromDateType  border-top-0 border-right-0 border-left-0 border border-dark col-md-2" data-toggle="tooltip" data-placement="top" title="Select From Date Type"   >

                                <option value='FULL' disable> FULL </option>
                                <option value='FIRST HALF' disable> FIRST HALF </option>
                                <option value='SECOND HALF' disable> SECOND HALF </option>

                            </select>

                            <!-- To Date -->
                            <input type="text"  name="toDate" data-toggle="tooltip" data-placement="top" title="To Date" placeholder="To Date" onfocus="(this.type='date')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark col-md-2" id="toDate-0"  min="<?php echo date('Y-m-d') ?>"    >

                            <!-- From Date Type -->
                            <select  id="toDateType-0" name="toDateType" class=" toDateType  border-top-0 border-right-0 border-left-0 border border-dark col-md-2" data-toggle="tooltip" data-placement="top" title="Select To Date Type"    >

                                <option value='FULL' disable> FULL </option>
                                <option value='FIRST HALF' disable> FIRST HALF </option>
                                <option value='SECOND HALF' disable> SECOND HALF </option>

                            </select>
                            
                            <button type="button" id="leavetyperemove-0"  class=" leavetypeRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row' > <i class="fas fa-minus mr-1"></i> Remove </button>
                            
                        </div>
                        
                        
                    </div>
                    
                    <!-- Add Row Button -->
                    <button type="button" id="addleaveTypeRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row' > Add Row </button>
                    
                </div>
            
                <!-- Total Days -->
                <div class="form-row">

                    <div class="form-group col-md-12 mt-3 md-0">
                        
                        <p class="form-control border bg-white mb-0" id="totalDays" name="totalDays"> Total Days </p>

                    </div>

                </div>

                <!-- Reason -->
                <div class="form-row">
                    <div class="form-group col-md-12 pt-2">

                        <textarea type="text" name="reason"  placeholder="Reason" class="form-control border border-dark" id="reason"></textarea>

                    </div>
                </div>

                <!-- Lecture Adjustments -->
                <div class="form-row border py-1 px-3 mb-4 rounded">

                    <h6 class=" pb-3 pt-2 col-md-12" style="color: #11101D;"> <input id="lecAdj" name="lecAdj" class="pe-auto" type="checkbox"  > Lecture Adjustments ( Optional )</h6>

                    <!-- Container for all lecAdj Rows -->
                    <div class="lecAdjContainer col-md-12  ">

                        <!-- lecAdj Row ( Each lecAdj ) -->
                        <div id="lecAdjItem-0" class="lecAdjItem border pb-2 pt-3 px-4  form-row flex justify-content-between align-items-end mb-3">

                            <div class="form-row col-md-10 justify-content-between mb-2 pt-3">

                                    <!-- Adjust With Email -->
                                    <select  id="lecAdjEmail-0" name="lecAdjEmail" class=" lecAdjEmail  border-top-0 border-right-0 border-left-0 border border-dark mb-4 col-md-5" data-toggle="tooltip" data-placement="top" title="Select Adjusted With"  >

                                        <option value="" > Adjust With </option>

                                        <?php

                                            $emps = Utils::getAllActiveEmployees();

                                            while( $row = mysqli_fetch_assoc( $emps ) ){

                                            echo "<option value='" .$row['employeeID']. "' > ".$row['fullName']." </option>";

                                            }
                                        
                                        ?>

                                        

                                    </select>

                                    <!-- Semester -->
                                    <select  id="lecAdjSem-0" name="lecAdjSem" class=" lecAdjSem  border-top-0 border-right-0 border-left-0 border border-dark mb-4 col-md-3" data-toggle="tooltip" data-placement="top" title="Semester"  >

                                        <option value="0" > Select Semester </option>
                                        <option value="1" > 1st Sem </option>
                                        <option value="2" > 2nd Sem </option>
                                        <option value="3" > 3rd Sem </option>
                                        <option value="4" > 4th Sem </option>
                                        <option value="5" > 5th Sem </option>
                                        <option value="6" > 6th Sem </option>
                                        <option value="7" > 7th Sem </option>
                                        <option value="8" > 8th Sem </option>

                                    </select>

                                    <!-- Subject -->
                                    <input type="text"  name="sub" data-toggle="tooltip" data-placement="top" title="Subject" placeholder="Subject" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-4 col-md-3" id="sub-0" >


                                    <!-- lec Date -->
                                    <input type="text"  name="lecDate" data-toggle="tooltip" data-placement="top" title="Lecture Date" placeholder="Lecture Date" onfocus="(this.type='date')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-2 col-md-5" id="lecDate-0" >

                                    <!-- Lec Start Time -->
                                    <input type="text"  name="lecStartTime" data-toggle="tooltip" data-placement="top" title="Lecture Start Time" placeholder="Lecture Start Time" onfocus="(this.type='time')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-2 col-md-3" id="lecStartTime-0" >

                                    <!-- Lec End Time -->
                                    <input type="text"  name="lecEndTime" data-toggle="tooltip" data-placement="top" title="Lecture End Time" placeholder="Lecture End Time" onfocus="(this.type='time')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-2 col-md-3" id="lecEndTime-0" >

                            </div>

                            <button type="button" id="lecAdjRemove-0"  class=" lecAdjRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row' > <i class="fas fa-minus mr-1"></i> Remove </button>
                            
                        </div>
                        
                        
                    </div>
                    
                    <!-- Add Row Button -->
                    <button type="button" id="lecAdjRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row' > Add Row </button>


                </div>

                <!-- Task Adjustments -->
                <div class="form-row border py-1 px-3 mb-4 rounded">

                    <h6 class=" pb-3 pt-2 col-md-12" style="color: #11101D;"> <input id="taskAdj" class="pe-auto" name="taskAdj" type="checkbox"  > Task Adjustments ( Optional )</h6>

                    <!-- Container for all taskAdj Rows -->
                    <div class="taskAdjContainer col-md-12  ">

                        <!-- taskAdj Row ( Each taskAdj ) -->
                        <div id="taskAdjItem-0" class="taskAdjItem border pb-2 pt-3 px-4  form-row flex justify-content-between align-items-center mb-3">

                            <div class="form-row col-md-10 justify-content-between mb-2 pt-3">

                                    <!-- Adjust With Email -->
                                    <select  id="taskAdjEmail-0" name="taskAdjEmail" class=" taskAdjEmail  border-top-0 border-right-0 border-left-0 border border-dark mb-4 col-md-4" data-toggle="tooltip" data-placement="top" title="Select Adjusted With"  >

                                        <option value="" > Adjust With </option>

                                        <?php

                                            $emps = Utils::getAllActiveEmployees();

                                            while( $row = mysqli_fetch_assoc( $emps ) ){

                                            echo "<option value='" .$row['employeeID']. "' > ".$row['fullName']." </option>";

                                            }
                                        
                                        ?>

                                        

                                    </select>

                                    <!-- from Date -->
                                    <input type="text"  name="taskFromDate" data-toggle="tooltip" data-placement="top" title="From Date" placeholder="From Date" onfocus="(this.type='date')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-4 col-md-3" id="taskFromDate-0" >

                                    <!-- To Date -->
                                    <input type="text"  name="taskToDate" data-toggle="tooltip" data-placement="top" title="To Date" placeholder="To Date" onfocus="(this.type='date')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark mb-4 col-md-4" id="taskToDate-0" >
                                    
                                    
                                    <!-- Task -->
                                    <textarea  name="task" data-toggle="tooltip" data-placement="top" title="Task" placeholder="Task" class=" border border-dark mb-4 col-md-12 pt-2" id="task-0" ></textarea>

                            </div>

                            <button type="button" id="lecAdjRemove-0"  class=" lecAdjRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row' > <i class="fas fa-minus mr-1"></i> Remove </button>
                            
                        </div>
                        
                        
                    </div>
                    
                    <!-- Add Row Button -->
                    <button type="button" id="taskAdjRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row' > Add Row </button>


                </div>

                
                <!-- Additional Approvals -->
                <div class="form-row border py-1 px-3 mb-4 rounded">

                    <h6 class=" pb-3 pt-2 col-md-12" style="color: #11101D;"> <input id="addApproval" name="addApproval" class="pe-auto" type="checkbox"  > Additional Approvals ( Optional )</h6>

                    <!-- Container for all Approval Rows -->
                    <div class="approvalsContainer col-md-12">

                        <!-- Approval Row ( Each Approval ) -->
                        <div id="approvalItem-0" class="approvalItem  form-row flex justify-content-between align-items-end mu-2 mb-3">

                            <!-- Approver Email -->
                            <select  id="approvalEmail-0" name="approvalEmail" class=" approvalEmail  border-top-0 border-right-0 border-left-0 border border-dark col-md-9" data-toggle="tooltip" data-placement="top" title="Select Approver Email"  >


                                <?php

                                    $emps = Utils::getAllActiveEmployees();

                                    while( $row = mysqli_fetch_assoc( $emps ) ){

                                    echo "<option value='" .$row['email']. "' disable> ".$row['fullName']." </option>";

                                    }
                                
                                ?>

                                

                            </select>
                            
                            <button type="button" id="remove-0"  class=" approvalRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row' > <i class="fas fa-minus mr-1"></i> Remove </button>
                            
                        </div>
                        
                        
                    </div>
                    
                    <!-- Add Row Button -->
                    <button type="button" id="addApprovalRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row' > Add Row </button>


                </div>

                <button type="submit" name="submit" class="btn mt-2" style="background-color: #11101D; color: white;">Apply</button>

            </form>

        </div>

    </section>


    <script>

        $(document).ready(function() {

            //* ------------------------- Additional Approvals Box --------------------------------

            //Additional Approvals Container
            $('.approvalsContainer').hide()
            $('#addApprovalRowBtn').hide()
        
            $('#addApproval').click(()=>{

                $('#addApprovalRowBtn').toggle()
                $('.approvalsContainer').toggle()

            })
            
            //Logic to add new Blank rows
            $('#addApprovalRowBtn').click(()=>{ 

                let prevValue = $('.approvalItem:last > select')[0].value 

                if( prevValue === "" ) return

                //Clone the HTML structure of row
                let approvalItem = $('.approvalItem:last').clone()
                let approvalNo = (approvalItem[0].id).split('-')[1] //get the no. of row

                approvalItem.attr('id', `approvalItem-${parseInt(approvalNo) + 1}`); //update the no.of row
                
                $('.approvalsContainer').append( approvalItem ) //append the new row
                
                let len = approvalItem[0].children.length //get all elements in row like select and buttons
                
                //for every element
                for( let i = 0 ; i < len ; i = i+1 ){
              
                    let id = (approvalItem[0].children[i].id).toString()
                    let newId = (id.replace( approvalNo , `${parseInt(approvalNo) + 1}` )) //change the id according to no.

                    approvalItem[0].children[i].id = newId;
                    
                    //for remove button add onclick function
                    if( i == len-1 ){
                        approvalItem[0].children[i].onclick =  (e)=>removeApprovalRow(e) ;
                    }

                }

            })

            //to remove for first child
            $('#remove-0').click((e)=>{
                removeApprovalRow(e)
            })

            //Function to remove approval rows
            function removeApprovalRow(e){

                let len = $('.approvalItem').length
                
                if( len == 1 )return 

                let id = (e.target.id).split('-')[1]
                $(`#approvalItem-${id}`).remove()

            }

            //* ------------------------- Lecture Adjustment Box --------------------------------

            //Additional Approvals Container
            $('.lecAdjContainer').hide()
            $('#lecAdjRowBtn').hide()
        
            $('#lecAdj').click(()=>{

                $('#lecAdjRowBtn').toggle()
                $('.lecAdjContainer').toggle()

            })
            
            //Logic to add new Blank rows
            $('#lecAdjRowBtn').click(()=>{ 
                
                
                let prevValue = $('.lecAdjItem:last')[0]
                let children = $('.lecAdjItem:last')[0].children[0].children
                let totalChildren = children.length

                for( let i = 0 ; i < totalChildren - 1 ; i++  ){

                        if( children[i].value === "" ) return

                }

                //Clone the HTML structure of row
                let lecAdjItem = $('.lecAdjItem:last').clone()
                let lecAdjNo = (lecAdjItem[0].id).split('-')[1] //get the no. of row

                lecAdjItem.attr('id', `lecAdjItem-${parseInt(lecAdjNo) + 1}`); //update the no.of row
                
                $('.lecAdjContainer').append( lecAdjItem ) //append the new row
                
                let len = lecAdjItem[0].children[0].children.length //get all elements in row like select and buttons
                
                //for every element
                for( let i = 0 ; i < len ; i++ ){
                    
                    lecAdjItem[0].children[0].children[i].value = ""
                    let id = (lecAdjItem[0].children[0].children[i].id).toString()
                    let newId = (id.replace( lecAdjNo , `${parseInt(lecAdjNo) + 1}` )) //change the id according to no.
                    
                    lecAdjItem[0].children[0].children[i].id = newId;
                    
                    //for remove button add onclick function
                    if( i == len-1 ){
                        lecAdjItem[0].children[1].onclick =  (e)=>removeLecAdjRow(e) ;
                    }

                }
                
                
                id = (lecAdjItem[0].children[1].id).toString()
                newId = (id.replace( lecAdjNo , `${parseInt(lecAdjNo) + 1}` )) //change the id according to no.
                
                lecAdjItem[0].children[1].id = newId;

            })

            //to remove for first child
            $('#lecAdjRemove-0').click((e)=>{
                removeLecAdjRow(e)
            })

            //Function to remove approval rows
            function removeLecAdjRow(e){

                console.log(e.target.id);
                let len = $('.lecAdjItem').length
                
                if( len == 1 )return 

                let id = (e.target.id).split('-')[1]
                $(`#lecAdjItem-${id}`).remove()

            }

            //* ------------------------- Task Adjustment Box --------------------------------

            //Additional Approvals Container
            $('.taskAdjContainer').hide()
            $('#taskAdjRowBtn').hide()
        
            $('#taskAdj').click(()=>{

                $('#taskAdjRowBtn').toggle()
                $('.taskAdjContainer').toggle()

            })
            
            //Logic to add new Blank rows
            $('#taskAdjRowBtn').click(()=>{ 
                
                
                let taskChildren = $('.taskAdjItem:last')[0].children[0].children
                let totalTaskChildren = taskChildren.length

                for( let i = 0 ; i < totalTaskChildren - 1 ; i++  ){

                        if( taskChildren[i].value === "" ) return

                }

                //Clone the HTML structure of row
                let taskAdjItem = $('.taskAdjItem:last').clone()
                let taskAdjNo = (taskAdjItem[0].id).split('-')[1] //get the no. of row

                taskAdjItem.attr('id', `taskAdjItem-${parseInt(taskAdjNo) + 1}`); //update the no.of row
                
                $('.taskAdjContainer').append( taskAdjItem ) //append the new row
                
                let len = taskAdjItem[0].children[0].children.length //get all elements in row like select and buttons
                
                //for every element
                for( let i = 0 ; i < len ; i++ ){
                    
                    taskAdjItem[0].children[0].children[i].value = ""
                    let id = (taskAdjItem[0].children[0].children[i].id).toString()
                    let newId = (id.replace( taskAdjNo , `${parseInt(taskAdjNo) + 1}` )) //change the id according to no.
                    
                    taskAdjItem[0].children[0].children[i].id = newId;
                    
                    //for remove button add onclick function
                    if( i == len-1 ){
                        taskAdjItem[0].children[1].onclick =  (e)=>removeTaskAdjRow(e) ;
                    }

                }
                
                
                id = (taskAdjItem[0].children[1].id).toString()
                newId = (id.replace( taskAdjNo , `${parseInt(taskAdjNo) + 1}` )) //change the id according to no.
                
                taskAdjItem[0].children[1].id = newId;

            })

            //to remove for first child
            $('#taskAdjRemove-0').click((e)=>{
                removeTaskAdjRow(e)
            })

            //Function to remove approval rows
            function removeTaskAdjRow(e){

                console.log(e.target.id);
                let len = $('.taskAdjItem').length
                
                if( len == 1 )return 

                let id = (e.target.id).split('-')[1]
                $(`#taskAdjItem-${id}`).remove()

            }

            //* ------------------------- Leave Types Box --------------------------------

            leaveTypes = {} // array to avoid duplicate leave types
            leaveTypes['final'] = {} //final object will have first fromDate to last toDate

            var isSafeToAddNewLeaveTypeRow = false //if false meanse the currrent data is not validated hence don't allow adding another row

            console.log(leaveTypes);

            //Add new Row
            $('#addleaveTypeRowBtn').click(()=>{ 
                addLeaveTypeNewRow( )
            })

            //Validate data
            $('#leaveType-0 , #fromDate-0 , #fromDateType-0 ,  #toDate-0 ,  #toDateType-0').change((e)=>{
                handleLeaveDataChange(e)
            })

            //to remove for first child
            $('#leavetyperemove-0').click((e)=>{
                removeLeaveTypeRow(e)
            })

            //Fucntion to Validate the current data and configure final object
            function handleLeaveDataChange(e){
                
                console.log(leaveTypes);

                //get the id of row
                let id = e.target.id
                id = parseInt(id.split('-')[1])
                
                let prevValue = $( `#leavetypeItem-${id}` )[0]
                let childrens = prevValue.children 
                
                //For every element in row
                for( let i = 0 ; i < childrens.length - 1 ; i++ ){
                    
                    //If input is blank
                    if ( childrens[i].value === "" ){
                        return
                    }
                    
                }

                
                let lastRow = $('.leavetypeItem:last ')[0]
                let childrensOfLastRow = lastRow.children

                //If some leave type for the rowNo already exist then remove that
                for (const key in leaveTypes) {
                    
                    if( leaveTypes[key].rowNo === id ){
                        delete leaveTypes[key];
                        break;
                    }

                }
                

                //Check if there is already some data for specific leavetype
                if( leaveTypes[ childrensOfLastRow[0].value + '' ] != undefined && leaveTypes[ childrensOfLastRow[0].value + '' ].rowNo !== id ) {

                    alert( "Cannot Select Leave Type more than Once !" )
                    return;
                }

                //create a object for that specefic leave type
                leaveTypes[ childrensOfLastRow[0].value + '' ] = {  }
                leaveTypes[ childrensOfLastRow[0].value + '' ].rowNo = id;

                
                //For every element in row i.e , select , inputs,
                for( let i = 0 ; i < childrensOfLastRow.length - 1 ; i++ ){

                        //If input is blank
                        if ( childrensOfLastRow[i].value === "" ){
                            return
                        }

                        if( childrensOfLastRow[i].name === "leaveID" ){

                                //create new object for new leave type
                                leaveTypes[ childrensOfLastRow[i].value + '' ][ childrensOfLastRow[i].name + '' ] = childrensOfLastRow[i].value
                                
                        }
                        
                        //Add all the data in leave type specific object
                        leaveTypes[ childrensOfLastRow[0].value + '' ][ childrensOfLastRow[i].name + '' ] = childrensOfLastRow[i].value

                }


                //reset final object
                leaveTypes['final'] = {}

                let validate = validateLeaveData( leaveTypes ) //Validate Data and configure final dates

                //If validations fails
                if( !validate.isValid ){ 

                    alert( validate.msg )
                    isSafeToAddNewLeaveTypeRow = false //don't allow to add new Row
                    return;
                
                }

                //Validations were fine hence allow to add new Row
                isSafeToAddNewLeaveTypeRow = true
                
            }


            //Fucntion to remove leave type Row
            function removeLeaveTypeRow(e){

                let len = $('.leavetypeItem').length
                if( len == 1 )return //there must be atleast 1 row
                
                let id = (e.target.id).split('-')[1]
                

                //Remove the leave type specific object
                for (const key in leaveTypes) {
                    
                    if( leaveTypes[key].rowNo === parseInt(id) ){
                        delete leaveTypes[key];
                        break;
                    }

                }
                
                //Remove Row
                $(`#leavetypeItem-${id}`).remove()


                //reset final object
                leaveTypes['final'] = {}

                let validate = validateLeaveData( leaveTypes ) //Validate Data and configure final dates

                //If validations fails
                if( !validate.isValid ){ 

                    alert( validate.msg )
                    isSafeToAddNewLeaveTypeRow = false //don't allow to add new Row
                    return;
                
                }

                //Validations were fine hence allow to add new Row
                isSafeToAddNewLeaveTypeRow = true

            }


            //Validate Current data and return response
            function validateLeaveData( leavedata ) {

                //For every leave in leave data
                for (const key in leavedata) {
                    
                    if( key === 'final') continue

                    //Define all data
                    let appliedLeaveData = leavedata[key]
                    let validationsRequired //LeaveType data from DB
                    let balances //Employee Balance data from DB
                    
                    //define data from db
                    for( let i = 0 ; i < leaveTypeDeatils.length ; i=i+1 ) {

                        if( leaveTypeDeatils[i].leaveID === key ){
                            validationsRequired  = leaveTypeDeatils[i];
                            break;
                        }
                    }

                    //define data from db
                    for( let i = 0 ; i < employeeBalance.length ; i=i+1 ) {

                        if( employeeBalance[i].leaveID === key ){
                            balances = employeeBalance[i];
                            break;
                        }
                    }

                    //The Time according to UTC is 5:30:00 ahead for india
                    let currTime = new Date()
                    currTime.setHours(5 , 30 , 0 ,0)

                    let fromDate =  new Date( appliedLeaveData.fromDate ) 
                    fromDate.setHours(5 , 30 , 0 ,0)
                    let toDate = new Date( appliedLeaveData.toDate )
                    toDate.setHours(5 , 30 , 0 ,0)
                    
                    let finalEndDate = new Date(leavedata.final.endDate)
                    finalEndDate.setHours(5 , 30 , 0 ,0)

                    //Validate dates sequence from above rows ( from Date of row cannot be less than toDate of prev row )
                    if( leavedata.final.endDate != undefined && ( finalEndDate.getTime() >= fromDate.getTime() )  )return { msg : `fromDate cannot be less than or equals to previous endDate!`  , isValid: false }

                    //Validate Waiting Time 
                    if(  fromDate.getTime() < ( currTime.getTime() + parseInt( validationsRequired.waitingTime ) * (24*60*60*1000) )  ) return { msg : `${validationsRequired.leaveType} needs to apply atleast ${validationsRequired.waitingTime} Days prior !`  , isValid: false }
                    
                    //ToDate Should be greater or equal to fromDate
                    if( toDate.getTime() < fromDate.getTime() ) return { msg : "toDate cannot be less than fromDate !" , isValid: false }


                    //calculate difference between days
                    let diffBtnDates = Math.floor(( toDate.getTime() - fromDate.getTime() ) / (24*60*60*1000))
                    
                    //validate Apply Limit
                    if( diffBtnDates > parseInt(validationsRequired.applyLimit) ) return { msg : `${validationsRequired.leaveType} has apply limit of ${validationsRequired.applyLimit}` , isValid: false }

                    //Check for insuffcient Balance
                    if( parseInt(balances.balance) < diffBtnDates )return { msg : `Insuffcient balance , Current ${balances.leaveType}s :  ${balances.balance} ` , isValid: false }

                    //Add totalDays to leave type specefic object
                    leavedata[key].totalDays = diffBtnDates;
    

                    //update final object
                    if( leavedata.final.startDate === undefined ){

                        leavedata.final.startDate = appliedLeaveData.fromDate
                        leavedata.final.startDateType = appliedLeaveData.fromDateType;

                    }

                    leavedata.final.endDate = appliedLeaveData.toDate;
                    leavedata.final.endDateType = appliedLeaveData.toDateType;
    
                }

                calculateTotalDays( leavedata )
                return { msg : "Validations Successfull" ,isValid : true }
            }


            //  Add new Row
            function addLeaveTypeNewRow(){

                if( !isSafeToAddNewLeaveTypeRow ) return //If it is not safe then don't add row and return

                //Clone the HTML structure of row
                let leavetypeItem = $('.leavetypeItem:last').clone() //clone of last row
                let leaveTypeNo = (leavetypeItem[0].id).split('-')[1] //get the no. of last row

                leavetypeItem.attr('id', `leavetypeItem-${parseInt(leaveTypeNo) + 1}`); //update the no.of cloned row
                
                $('.leavetypesContainer').append( leavetypeItem ) //append the new row
                
                let len = leavetypeItem[0].children.length //get all elements in row like select and buttons
                
                //for every element
                for( let i = 0 ; i < len ; i = i+1 ){
              
                    //Set Dates input as blank for cloned row
                    if( leavetypeItem[0].children[i].name === "fromDate" || leavetypeItem[0].children[i].name === "toDate" )  leavetypeItem[0].children[i].value  = ""

                    let id = (leavetypeItem[0].children[i].id).toString()
                    let newId = (id.replace( leaveTypeNo , `${parseInt(leaveTypeNo) + 1}` )) //change the id according to no.

                    leavetypeItem[0].children[i].id = newId;
                    
                    //for remove button add onclick function
                    if( i == len-1 ){
                        leavetypeItem[0].children[i].onclick =  (e)=>removeLeaveTypeRow(e) ;
                    }else{
                        leavetypeItem[0].children[i].onchange =  (e)=>handleLeaveDataChange(e) ;
                    }

                }



            }
            


            //* ------------------------- Calculate Total Days --------------------------------

            function calculateTotalDays( leavedata ){

                console.log(leavedata);
                let data = leavedata.final

                let startDate = new Date( data.startDate )
                startDate.setHours(5,30,0,0)

                let startDateType = new Date( data.startDateType )
                startDateType.setHours(5,30,0,0)

                let endDate = new Date(data.endDate)
                endDate.setHours(5,30,0,0)

                let endDateType = new Date (data.endDateType)
                endDateType.setHours(5,30,0,0)

                //Sandwich Leaves
                if( endDateType === 'SECOND HALF' ){



                }


            }

        })

        


    </script>

</body>

</html>