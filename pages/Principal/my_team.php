<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>



<!-- Include this to use User object -->
<?php

    //include class definition
    require('../../utils/Principal/Principal.class.php');

    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');

    //start session
    session_start();

    //Get the User Object
    $user =  $_SESSION['user'];

?>

<?php

    function empStatusButton( $status ){

        if( $status == Config::$_EMPLOYEE_STATUS['ACTIVE'] ) return Config::$_EMPLOYEE_STATUS['INACTIVE'];
        return Config::$_EMPLOYEE_STATUS['ACTIVE'];

    }

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

        <!-- DataTables library to implement optimal search functinality ---- light weight library -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>

</head>

<body>


    <!--Including sidenavbar -->
    <?php
     include "../../includes/Principal/sidenavbar.php";
    ?>


    <section class="home-section">

        <!--Including header -->
        <?php
            include "../../includes/header.php";
        ?>


        <div class="container">

            <div class=" table-container bg-white rounded-lg shadow-lg mt-3">

                <div class="masterdata-container row p-5 rounded-lg shadow-lg d-flex justify-content-sm-start flex-column "
                    style="transition: all all 0.5s ease; border-right:6px solid #11101D">


                        <div class="col-md-12 col-sm-12 py-3">
                            <h3> My Team </h3>
                        </div>


                            <input type="text" class="form-control bg-white p-4 my-3 " id="team-searchInput" placeholder="Search...">

                            <!-- //Filters -->
                            <div class="col-md-12 col-sm-12 py-3 border my-3 " id='filter-box'>

                                <!-- Department -->
                                <div class="my-2 col-md-12 ">

                                    <!-- Showing Dept as Options -->
                                    <?php

                                        $dept =  Utils::getAllDepts() ;

                                        while($row = mysqli_fetch_assoc($dept)) {

                                            $name = $row['deptName'];

                                            echo "<input type='checkbox' class=' dept-check-inp dept-inp-status' checked name=$name value='".$row['deptID']."' >";
                                            echo "<label class=' mr-4 ml-2  ' >".$name."</label>";
                                        }


                                    ?>

                                </div>

                                <!-- Status Filter -->
                                <div class="my-2 col-md-5 ">
                                    <!-- Showing Status as Options -->
                                    <?php

                                        $status = Config::$_EMPLOYEE_STATUS;

                                        foreach( $status as $key => $value ){
                                                echo "<input type='checkbox' class='check-inp inp-status' checked value='$key' >";
                                                echo "<label class=' mr-4 ml-2 ' >$value</label>";
                                        }

                                    ?>

                                </div>



                    <table id="teamTable" class="tablecontent">

                        <thead>
                            <tr>
                                <th>EMPLOYEE ID</th>
                                <th>EMPLOYEE NAME</th>
                                <th>EMPLOYEE EMAIL</th>
                                <th>DEPARTMENT</th>
                                <th>ROLE</th>
                                <th>STATUS</th>
                                <th>VIEW</th>
                            </tr>
                        </thead>

                        <tbody id="tbody">

                            <?php

                            $employees = Utils::getAllEmployees(); // Returns Array of Tuples in Database
                            
                            foreach($employees as $row ){
                                
                                if( $row['employeeID'] === $user->employeeId ) continue;

                                $statusBtnValue = empStatusButton( $row['status'] );

                                $statusStyle = "text-black";
                                $employeeID = $row['employeeID'];
                                if( $row['status'] == Config::$_EMPLOYEE_STATUS['INACTIVE'] ) $statusStyle = "text-black-50";
                                
                                echo "<tr>";
                                echo "<td class='$statusStyle' >" . $row['employeeID'] . "</td>";
                                echo "<td class='$statusStyle' >" . $row['fullName'] . "</td>";
                                echo "<td class='$statusStyle' >" . $row['email'] . "</td>";
                                echo "<td class='$statusStyle' >" . $row['deptName'] . " </td>";
                                echo "<td class='$statusStyle' >" . $row['role'] . " </td>";
                                echo "<td class='$statusStyle' >" . $row['status'] . " </td>";
                                echo "<td ><a href='../../pages/Principal/viewDetailedEmp.php?empID=$employeeID'><i class='fa-solid fa-eye edit $statusStyle'></i></a></td>";
                            }

                        ?>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

    <script>

        // script for filter 
        $(document).ready(function() {


        // Initialize DataTable
        var table = $('#teamTable').DataTable({
            paging: false,
            ordering: false,
            info: false
        });

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

            //Take all Types of Filters
            let deptInps = document.querySelectorAll('.dept-inp-status')
            let statusInps = document.querySelectorAll('.inp-status')

            //Create Arrays
            let depts = []
            let status = []

            //For Department
            deptInps.forEach(element => {

                if (element.checked) {

                    depts.push(element.name);
                }

            });

            //For Status
            statusInps.forEach(element => {

                if (element.checked) {

                    status.push(element.value);
                }

            });

            let tableDept = (data[3].split(" "))[0];
            let tableStatus = data[5];

            //Filter Logic
            if( depts.includes( tableDept ) && status.includes( tableStatus ) ) return true;
            else return false;


        });

        //On Filter Change load the table
        $('.check-inp').change(() => {
            table.draw();
        })


        //On Filter Change load the table
        $('.dept-check-inp').change(() => {
            table.draw();
        })


        // Add event listener for the search input
        $('#team-searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

      });
    </script>


    </section>
</body>

</html>