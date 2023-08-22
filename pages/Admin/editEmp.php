

<?php 
    //  Creates database connection 
    require "../../includes/db.conn.php";
?>



<!-- Include this to use User object -->
<?php

    //include Config Class
    require('../../utils/Utils.class.php');
    require('../../utils/Config/Config.class.php');

    //include class definition
    require('../../utils/Admin/Admin.class.php');

    //start session
    session_start();

    //Get the User Object
    $user =  $_SESSION['user'];
    $empID = $_GET['empID']

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
            $empDetails = mysqli_fetch_assoc( Utils::getEmpDetails($empID) );
        ?>

        <div class="container">

            <?php $actionUrl = "validateEditEmp.php?empID=".$empID."" ?>

            <form class=" bg-white shadow pl-5 pr-5 pb-3 pt-5 mt-5 rounded-lg" action='<?php echo $actionUrl?>'  method="POST">

                <h4 class="pb-3 pt-2" style="color: #11101D;">Edit Employee Details</h4>

                <div class="form-row">

                    <!-- Emp Email -->
                    <div class="form-group col-md-6">
                        <input type="email" maxLength="100" value="<?php echo $empDetails['email'] ?>" disabled
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder=" Employee Email" name="empEmail">
                    </div>

                    <!-- Employee Name -->
                    <div class="form-group col-md-3">
                        <input type="text" maxLength="200" value="<?php echo $empDetails['fullName'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder=" Employee Name " name="empName">
                    </div>

                    <!-- Department -->
                    <div class="form-group col-md-3">

                        <select name="dept"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark ">
                            <option value="NULL" selected disabled hidden>No Department</option>


                            <!-- Showing Departments as Options -->
                            <?php
                                $depts = Utils::getAllDepts();

                                while( $row = mysqli_fetch_assoc($depts) ){

                                    if( $row['deptID'] == $empDetails['deptID'] ){

                                        echo "<option value='" .$row['deptID']. "' selected >". $row['deptName'] ."</option>";;
                                        
                                    }else{
                                        
                                        echo "<option value='" .$row['deptID']. "'>". $row['deptName'] ."</option>";
                                    }

                                    
                                
                                }

                            ?>

                        </select>

                    </div>

                    <!-- Joining Date -->
                    <div class="form-group col-md-4"> 
                        <input type="text" onfocus="(this.type='date')" value="<?php echo $empDetails['joiningDate'] ?>" disabled
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark "
                            placeholder="Joining Date" name="joiningDate">
                    </div>

                    <!-- Type -->
                    <div class="form-group col-md-4">

                        <select name="type" 
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark ">

                            <!-- Showing Emp Types as Options -->
                            <?php

                                $types = Config::$_EMPLOYEE_TYPE;

                                foreach ($types as $key => $value) {

                                    if( $value == $empDetails['type'] ){

                                        echo "<option value='" .$key. "' selected >". $value ."</option>";
                                        
                                    }else{
                                        
                                        echo "<option value='" .$key. "'>". $value ."</option>";
                                    }
                                }

                            ?>

                        </select>

                    </div>

                    <!-- Role -->
                    <div class="form-group col-md-4">

                        <select name="role"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark ">

                            <!-- Showing Emp Roles as Options -->
                            <?php

                                $roles = Config::$_EMPLOYEE_ROLE;

                                foreach ($roles as $key => $value) {

                                    if( $value == $empDetails['role'] ){

                                        echo "<option value='" .$key. "' selected >". $value ."</option>";
                                        
                                    }else{
                                        
                                        echo "<option value='" .$key. "'>". $value ."</option>";
                                    }
                                    
                                }

                            ?>

                        </select>

                    </div>

                    <!-- Checkbox -->
                    <div class="form-group d-flex justify-content-center align-items-center mx-3 col-md-1">

                        <?php
                        
                                if( $empDetails['status'] == Config::$_EMPLOYEE_STATUS['ACTIVE'] ){

                                    echo "<input type='checkbox' disabled class='mr-3' placeholder='Enter Apply Limit' id='status' name='status' >";
                                    
                                }else{
                                    
                                    echo "<input type='checkbox' checked disabled class='mr-3' placeholder='Enter Apply Limit' id='status' name='status' >";

                                }
                        
                        ?>

                        
                        <label class="m-0" for="inactive"> INACTIVE </label>
                    </div>

                    <!-- Inactive Date -->
                    <div class="form-group col-md-4 ml-3 ">
                        <input type="text" onfocus="(this.type='date')" disabled  value="<?php echo $empDetails['deactivationDate'] ?>"
                            class="form-control bg-white border-top-0 border-right-0 border-left-0 border border-dark  "
                            placeholder="Inactivation Date " id="inactiveDate" name="inactiveDate">
                    </div>

                    
                    <div class="form-group col-md-12"> <input type="submit" value="Update" name="addLeaveSubmit" class="submitbtn"> </div>

                </div>

            </form>

        </div>


    </section>
</body>

</html>