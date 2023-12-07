<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
    
        //include class definition
    require('../../utils/Admin/admin.class.php');

    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');

    //start session
    session_start();
    
?>



<!-- Include this to use User object -->
<?php



    //Get the User Object
    $user =  $_SESSION['user'];

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


        <div class="container" >

        <div class=" table-container bg-white rounded-lg shadow-lg mt-3">

            <div class="masterdata-container row p-5 rounded-lg shadow-lg d-flex justify-content-sm-start flex-column "
                style="transition: all all 0.5s ease; border-right:6px solid #11101D">


                <div class="col-md-12 col-sm-12 py-3">
                    <h3> Manage Departments </h3>
                </div>

                <a href="../../pages/Admin/addDept.php" class="my-3" style="width : fit-content;"  ><button class="AddBtn"> + </button></a>

                <table class="tablecontent">

                    <thead>
                        <tr>
                            <th>DEPT ID</th>
                            <th>DEPT NAME</th>
                            <th>DEPT ALIAS</th>
                            <th>DEPT HOD</th>
                            <th>EDIT</th>
                            <th>DELETE</th>
                        </tr>
                    </thead>

                    <tbody id="tbody">

                        <?php

                            $null_cycle_date = "No Date";
                            $depts = $user->getDepartments(); // Returns Array of Tuples in Database

                            foreach($depts as $row ){

                                if (  empty( $row['deptAlias'] ) ) $row['deptAlias'] = "No Alias";
                                if (  empty( $row['fullName'] ) ) $row['fullName'] = "No HOD";

                                echo "<tr>";
                                echo "<td  >" . $row['deptID'] . "</td>";
                                echo "<td  >" . $row['deptName'] . "</td>";
                                echo "<td  >" . $row['deptAlias'] . "</td>";
                                echo "<td  >" . $row['fullName'] . " </td>";
                                echo "<td><a href='../../pages/Admin/editDept.php?deptID=$row[deptID]'><i class='fa-solid fa-pen-to-square edit'></i></a></td>";
                                echo "<td> <i id='deptID=".$row['deptID']."' style='cursor: pointer;' class='fa-solid fa-trash delete deleteBtn m-0 w-100'></i></td>";
                                echo "</tr>";
                            }

                        ?>
                    </tbody>
                </table>

            </div>

        </div>

        </div>


    </section>

    <?php
    
        require('../../includes/model.php'); 

        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const buttons = document.querySelectorAll('.deleteBtn');
                buttons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const deptID = this.id;

                        document.querySelector('.modal-body').innerHTML = 'Are you sure you want to delete this department ?';
                        document.querySelector('.modal-title').innerHTML = 'WARNING';
        
                        document.querySelector('#close-btn').onclick = ()=>{
                            window.location.href = `../../pages/Admin/deleteDept.php?` + deptID
                        }
        
                        $('#myModal').modal();

                        
                    });
                });
            });
        </script>";

        if (isset($_SESSION['response_message'])) {

            $res = unserialize($_SESSION['response_message']);
            unset($_SESSION['response_message']); // Clear the message to prevent displaying it again

            if( $res[1] === "SUCCESS" ){
                echo Utils::alert(htmlspecialchars($res[0]), htmlspecialchars($res[1]) , "manageDepartment.php");
            }else{
                echo Utils::alert($res[0] , $res[1], "manageDepartment.php");
            }

    }

    ?>

</body>

</html>