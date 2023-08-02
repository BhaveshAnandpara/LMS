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

    $leaveTypes = Utils::getLeaveTypes();

?>

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

                <!-- Additional Approvals -->
                <div class="form-row border py-1 px-3 rounded">

                    <h6 class="pb-3 pt-2 col-md-12" style="color: #11101D;"> <input id="addApproval" name="addApproval" type="checkbox"  > Additional Approvals ( Optional )</h6>

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
                            
                            <button id="remove-0"  class=" approvalRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row' > <i class="fas fa-minus mr-1"></i> Remove </button>
                            
                        </div>
                        
                        
                    </div>
                    
                    <!-- Add Row Button -->
                    <button id="addApprovalRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row' > Add Row </button>


                </div>


                <!-- Leave Type Box -->
                <div class="form-row border py-1 px-3 mt-2 rounded">

                    <h6 class="pb-3 pt-2 col-md-12" style="color: #11101D;"> Choose Leave Types </h6>

                    <!-- Container for all Leave Types -->
                    <div class="leavetypesContainer col-md-12">

                        <!-- Leave Type Row ( Each Leave Type ) -->
                        <div id="leavetypeItem-0" class="leavetypeItem  form-row flex justify-content-between align-items-end mu-2 mb-3">

                            <!-- Leave Type -->
                            <select  id="leaveType-0" name="leaveID" class=" leaveType  border-top-0 border-right-0 border-left-0 border border-dark col-md-3" data-toggle="tooltip" data-placement="top" title="Select Leave Type"  >


                                <?php

                                    while( $row = mysqli_fetch_assoc( $leaveTypes ) ){

                                        echo "<option value='" .$row['leaveID']. "' disable> ".$row['leaveType']." </option>";

                                    }
                                
                                ?>

                                

                            </select>

                            <!-- From Date -->
                            <input type="text"  name="fromDate" data-toggle="tooltip" data-placement="top" title="From Date" placeholder="From Date" onfocus="(this.type='date')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark col-md-2" id="fromDate-0"  min="<?php echo date('Y-m-d') ?>"  >

                            <!-- From Date Type -->
                            <select  id="fromDateType-0" name="fromDateType" class=" fromDateType  border-top-0 border-right-0 border-left-0 border border-dark col-md-1" data-toggle="tooltip" data-placement="top" title="Select From Date Type"  >

                                <option value='FULL' disable> FULL </option>
                                <option value='HALF' disable> HALF </option>

                            </select>

                            <!-- To Date -->
                            <input type="text"  name="toDate" data-toggle="tooltip" data-placement="top" title="To Date" placeholder="To Date" onfocus="(this.type='date')" onblur="(this.type='text')" class=" border-top-0 border-right-0 border-left-0  border border-dark col-md-2" id="toDate-0"  min="<?php echo date('Y-m-d') ?>"  >

                            <!-- From Date Type -->
                            <select  id="toDateType-0" name="toDateType" class=" toDateType  border-top-0 border-right-0 border-left-0 border border-dark col-md-1" data-toggle="tooltip" data-placement="top" title="Select To Date Type"  >

                                <option value='FULL' disable> FULL </option>
                                <option value='HALF' disable> HALF </option>

                            </select>
                            
                            <button id="leavetyperemove-0"  class=" leavetypeRemoveBtn btn " style="background-color: #c62828; color:white" data-toggle="tooltip" data-placement="top" title='Remove Row' > <i class="fas fa-minus mr-1"></i> Remove </button>
                            
                        </div>
                        
                        
                    </div>
                    
                    <!-- Add Row Button -->
                    <button id="addleaveTypeRowBtn" class=" btn mb-3" style="background-color: #11101D; color:white" data-toggle="tooltip" data-placement="top" title='Add Row' > Add Row </button>


                </div>
            
                <div class="form-row">

                    <div class="form-group col-md-6">

                        <!-- Leave type -->

                        <select required id="leaveType" name="leaveType" class="form-control border-top-0 border-right-0 border-left-0 border border-dark" data-toggle="tooltip" data-placement="top" title="Select Leave Type" name="leaveType">


                        </select>

                    </div>



                </div>

                <div class="form-row">

                    <div class="form-group col-md-3">

                        <!-- //Get from date -->
                        <input type="text" required name="fromDate" data-toggle="tooltip" data-placement="top" title="Start Leave Date" placeholder="From" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="fromDateId" min="<?php echo date('Y-m-d') ?>" >

                    </div>

                    <div class="form-group col-md-2">

                        <!-- //Get from date type-->

                        <select required id="fromType" name="fromType"  class="form-control border-top-0 border-right-0 border-left-0 border border-dark">

                            <option value="" selected disable>Day Type</option>
                            <option value="First Half">First Half</option>
                            <option value="Second Half">Second Half</option>
                            <option value="Full">Full</option>

                        </select>
                
                    </div>

                    <div class="form-group col-md-3">

                        <!-- //Get to date -->
                        <input type="text" required name="toDate" data-toggle="tooltip" data-placement="top" title="End Leave Date" placeholder="To" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="toDateId" placeholder="To" min="<?php echo date('Y-m-d') ?>"  >

                    </div>

                    <div class="form-group col-md-2">

                        <!-- //Get to date type -->
                        <select required id="toType" name="toType" class="form-control border-top-0 border-right-0 border-left-0 border border-dark">

                            <option value="" selected disable>Day Type</option>
                            <option value="First Half">First Half</option>
                            <option value="Second Half">Second Half</option>
                            <option value="Full">Full</option>

                        </select>

                    </div>

                    <div class="form-group col-md-2">

                        <!-- //total days -->
                        <input type="decimal" name="totalDays" placeholder="Total Days" data-toggle="tooltip" data-placement="top" title="Total Leave Days" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="totalDaysId">

                    </div>

                </div>

                <!-- reason -->
                <div class="form-group col-md-12  p-0">

                    <input type="text" name="reason"  placeholder="Reason" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="reason">

                </div>

                <!-- Lecture Adjustment Section -->

                <div id="dynamicadd-lec" >
            
                    <!-- //To get this value in php using $_POST -->
                    <input type="hidden" id="totalLec" name="totalLec" value=0 />

                    <div class="form-row" id='lecContainer' >

                        <div class="form-group col-md-3">

                            <!-- //First Lecture Adjustment -->
                            <select id="lec-adjustedWith-$0" name="lec-adjustedWith-$0" class="form-control border-top-0 border-right-0 border-left-0 border border-dark">

                                <option selected disable>Lecture Adjust With.. </option>

                            </select>

                        </div>

                        <!-- date -->
                        <div class="form-group col-md-2">
                        <input type="text" name="lec-date-$0" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Lecture Date" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="lec-date-$0">
                        </div>

                        <!-- start Time -->
                        <div class="form-group col-md-2">
                        <input type="text" name="lec-startTime-$0" onfocus="(this.type='time')" onblur="(this.type='text')" placeholder="start Time" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="lec-startTime-$0">
                        </div>

                        <!-- start Time -->
                        <div class="form-group col-md-2">
                        <input type="time " name="lec-endTime-$0" onfocus="(this.type='time')" onblur="(this.type='text')" placeholder="End Time" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="lec-endTime-$0">
                        </div>

                        <!-- Semester -->
                        <div class="form-group col-md-1">
                        <input type="text" name="lec-sem-$0" placeholder="Sem" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="lec-sem-$0" >
                        </div>

                        <!-- Subject -->
                        <div class="form-group col-md-1">
                        <input type="text" name="lec-sub-$0" placeholder="Subject" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="lec-sub-$0">
                        </div>

                        <div class="form-group col-sm-12 col-md-1">
                        <button class=" btn" id="lec-add" name="btn[]" style="background-color: #11101D; color:white">Add</button>
                        </div>

                    </div>

                </div>

                <!-- Task Adjustment -->

                <div id="dynamicadd-task" >

                    <!-- //To get this value in php using $_POST -->
                    <input type="hidden" id="totalTask" name="totalTask" value=0 />

                    <div class="form-row" id="taskContainer" >

                        <div class="form-group col-md-3">

                            <!-- //Adjusted With -->
                            <select id="task-adjustedWith-$0" name="task-adjustedWith-$0" class="form-control border-top-0 border-right-0 border-left-0 border border-dark"> </select>

                         </div>

                         
                        <!-- task Name -->
                        <div class="form-group col-md-2">

                            <input type="text" name="task-name-$0" placeholder="Task Name" id='task-name-$0' class="form-control border-top-0 border-right-0 border-left-0  border border-dark">

                        </div>
                    
                        <!-- Date -->
                        <div class="form-group col-md-2">

                            <input type="text" name="task-date-$0" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Date" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="task-date-$0">

                        </div>

                        <!-- start Time -->
                        <div class="form-group col-md-2">

                            <input type="time " name="task-startTime-$0" onfocus="(this.type='time')" onblur="(this.type='text')" placeholder="Start Time" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="task-startTime-$0">

                        </div>

                        <!-- End Time -->
                        <div class="form-group col-md-2">

                            <input type="time " name="task-endTime-$0" onfocus="(this.type='time')" onblur="(this.type='text')" placeholder="End Time" class="form-control border-top-0 border-right-0 border-left-0  border border-dark" id="task-endTime-$0">

                        </div>

                        <div class="form-group col-sm-12 col-md-1">

                            <button class=" btn" id="task-add" name="btn1[]" style="background-color: #11101D; color:white">Add</button>

                        </div>

                    </div>

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
                console.log(id);
                $(`#approvalItem-${id}`).remove()

            }


            //* ------------------------- Leave Types Box --------------------------------

            leaveTypes = [] // array to avoid duplicate leave types

            //Logic to add new Blank rows
            $('#addleaveTypeRowBtn').click(()=>{ 

                let prevValue = $('.leavetypeItem:last ')[0]
                let childrens = prevValue.children 

                //For every element in row
                for( let i = 0 ; i < childrens.length - 1 ; i++ ){

                        //If input is blank
                        if ( childrens[i].value === "" ){
                            return
                        }

                        if( childrens[i].name === "leaveID" ){

                            //If leave type is already selected
                            if( leaveTypes.includes( childrens[i].value ) ){
                                alert( 'Cannot Choose Same Leave Type Again !' ) 
                                return;
                            }else{
                                leaveTypes.push( childrens[i].value )
                            }
                        }

                }

                //Clone the HTML structure of row
                let leavetypeItem = $('.leavetypeItem:last').clone()
                let leaveTypeNo = (leavetypeItem[0].id).split('-')[1] //get the no. of row

                leavetypeItem.attr('id', `leavetypeItem-${parseInt(leaveTypeNo) + 1}`); //update the no.of row
                
                $('.leavetypesContainer').append( leavetypeItem ) //append the new row
                
                let len = leavetypeItem[0].children.length //get all elements in row like select and buttons
                
                //for every element
                for( let i = 0 ; i < len ; i = i+1 ){
              
                    if( leavetypeItem[0].children[i].name === "fromDate" || leavetypeItem[0].children[i].name === "toDate" )  leavetypeItem[0].children[i].value  = ""

                    let id = (leavetypeItem[0].children[i].id).toString()
                    let newId = (id.replace( leaveTypeNo , `${parseInt(leaveTypeNo) + 1}` )) //change the id according to no.

                    leavetypeItem[0].children[i].id = newId;
                    
                    //for remove button add onclick function
                    if( i == len-1 ){
                        leavetypeItem[0].children[i].onclick =  (e)=>removeLeaveTypeRow(e) ;
                    }

                }



            })


            //to remove for first child
            $('#leavetyperemove-0').click((e)=>{


                removeLeaveTypeRow(e)
            })

            //to remove the leave type rows
            function removeLeaveTypeRow(e){

                let len = $('.leavetypeItem').length
                
                if( len == 1 )return 

                let id = (e.target.id).split('-')[1]
                console.log(id);
                
                let value =  document.getElementById(`leaveType-${id}`).value 

                var index = leaveTypes.indexOf(value);
                if (index !== -1) {
                    leaveTypes.splice(index, 1);
                }
                
                $(`#leavetypeItem-${id}`).remove()


            }


        
        })


    </script>

</body>

</html>