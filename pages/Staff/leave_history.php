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
    <title>Document</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>

    <!-- all common links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">

    <!-- all common Script -->
    <script src="https://kit.fontawesome.com/65712a75e6.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>


    <!-- DataTables library to implement optimal search functinality ---- light weight library -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>

    <!--CSS LINK -->
    <link rel="stylesheet" href="../../css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/Admin/manageMasterData.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/leaveHistory.css">

</head>

<body>
    <!-- including navbar -->
    <?php
    include "../../includes/Staff/sidenavbar.php";
    ?>

    <section class="home-section">

        <!--Including header -->

        <?php
            include "../../includes/header.php";
        ?>

        <div class="container">
            <!-- Leave History -->
            <div class=" row p-4 bg-white  rounded-lg shadow-lg d-flex justify-content-sm-center  pl-5 pr-5 pb-3 pt-4 my-5 ml-2 "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D">

                <div class="col-md-12 col-sm-12 py-3">
                    <h3> Elapsed Applications</h3>
                </div>

                <!-- Incoming Applications -->
                <br>
                <div class="w-100">
                    <input type="text" class="form-control bg-white p-4 my-3 " id="searchInput" placeholder="Search...">

                    <!-- //Filters -->
                    <div class="col-md-12 col-sm-12 py-3 border my-3 " id='filter-box'>

                        <!-- Leave Filter -->
                        <div class="my-2 col-md-12 ">

                            <!-- Showing Departments as Options -->
                            <?php

                                $depts = Utils::getLeaveTypes();

                                while( $row = mysqli_fetch_assoc($depts) ){
                                        $leaveTypes = $row['leaveType'];
                                        echo "<input type='checkbox' class='check-inp inp-leave' checked value='$leaveTypes' >";
                                        echo "<label class=' mr-4 ml-2  ' >$leaveTypes</label>";
                                }

                            ?>

                        </div>

                        <!-- Extension Filter -->
                        <div class="my-2 col-md-5 ">

                            <input type='checkbox' id="inp-ext" class='check-inp '  value='extension' >
                            <label class=' mr-4 ml-2 ' > Extension </label>

                        </div>
                        <!-- Status Filter -->
                        <div class="my-2 col-md-6 ">
                            <!-- Showing Status as Options -->
                            <?php

                                $status = Config::$_APPLICATION_STATUS;

                                foreach( $status as $key => $value ){
                                        echo "<input type='checkbox' class='check-inp inp-status' checked value='$key' >";
                                        echo "<label class=' mr-4 ml-2 ' >$value</label>";
                                }
                            ?>
                        </div>
                    </div>

                    <!-- Elapsed Applications -->

                    <table id="elapsedApp" class="tablecontent ">

                        <thead>
                            <tr>
                                <th>APPLICATION DATE</th>
                                <th>LEAVE TYPE</th>
                                <th>FROM</th>
                                <th>TO</th>
                                <th>TOTAL DAYS</th>
                                <th>STATUS</th>
                                <th>VIEW</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                            <?php

                                $data =  $user->elapsedApplications( );
                                $statusStyle = "";

                                while ($row = mysqli_fetch_assoc($data)) { 
                                
                                $leaveTypes = $user->getAppLeaveTypes( $row['applicationID'] );
                                
                            ?>
                            <tr>
                                <?php

                        $hod_status;
                        $principal_status;

                        if( empty($row["hodApproval"]) ) {
                            $hod_status = Config::$_HOD_STATUS['PENDING'];
                        }else{
                        
                            if( $row["status"] == Config::$_APPLICATION_STATUS['APPROVED_BY_HOD'] ){
                                $hod_status = Config::$_HOD_STATUS['APPROVED'];
                            }
                            else{
                                $hod_status = Config::$_HOD_STATUS['REJECTED'];
                            }
                        
                        }
                    
                    
                        if( empty($row["principalApproval"]) ) {
                            $principal_status = Config::$_PRINCIPAL_STATUS['PENDING'];
                        }else{
                        
                            if( $row["status"] == Config::$_APPLICATION_STATUS['APPROVED_BY_PRINCIPAL'] ){
                                $principal_status = Config::$_PRINCIPAL_STATUS['APPROVED'];
                            }
                            else{
                                $principal_status = Config::$_PRINCIPAL_STATUS['REJECTED'];
                            }
                        
                        }

                            // For Extension
                            $extension;
                            if( empty( $row['extension'] ) ){
                                $extension = "false";
                            }
                            else $extension = "true";
                        ?>
                                <td  ><?php echo date( 'd-m-Y' ,strtotime($row["dateTime"]) ) ?></td>
                                <td  ><?php echo $leaveTypes ?></td>
                                <td  ><?php echo date( 'd-m-Y' ,strtotime($row["startDate"]) ) ?></td>
                                <td  ><?php echo date( 'd-m-Y' ,strtotime($row["endDate"]) ) ?></td>
                                <td  ><?php echo $row["totalDays"] ?></td>
                                <td  class="font-weight-bold "><?php echo $row['status'] ?></td>
                                <td  class="text-end "> <a href="./viewDetails.php?id=<?php echo $row['applicationID'] ?> &reason=<?php echo $row["reason"]; ?>"><i class="fas fa-eye"></i></a> </td>

                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
    // script for filter 
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#elapsedApp').DataTable(
            {
                paging: false,
                ordering: false,
                info: true
            }
        );

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {


            //Take all Types of Filters
            let leaveInps = document.querySelectorAll('.inp-leave')
            let statusInps = document.querySelectorAll('.inp-status')
            let extInps = document.getElementById('inp-ext')

            //Create Arrays
            let leaves = []
            let status = []


            //For Leaves
            leaveInps.forEach(element => {

                if (element.checked) {

                    leaves.push(element.value);
                }

            });

            //For Status
            statusInps.forEach(element => {

                if (element.checked) {

                    status.push(element.value);
                }

            });


            let tableLeaves = data[1];
            let tableStatus = data[9];
            let tableExt = data[5];

            tableLeaves = tableLeaves.split(' + ')[0]

            //Filter Logic
            if( leaves.includes( tableLeaves ) && status.includes( tableStatus ) && extInps.checked+'' == tableExt ) return true;
            else return false;




        });

        //On Filter Change load the table
        $('.check-inp').change(() => {
            table.draw();
        })


        // Add event listener for the search input
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });


    });
    </script>


</body>

</html>