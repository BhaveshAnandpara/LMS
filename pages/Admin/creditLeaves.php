<?php 

    //  Creates database connection 
    require "../../includes/db.conn.php";
    
        //include class definition
    require('../../utils/Admin/admin.class.php');

    //include Config Class
    require('../../utils/Config/Config.class.php');
    require('../../utils/Utils.class.php');
    
    session_start();

    
?>



<!-- Include this to use User object -->
<?php

    //Get the User Object
    $user =  $_SESSION['user'];

?>


<?php

    function leaveStatusButton( $status ){

        if( $status == Config::$_MASTERADTA_STATUS['ACTIVE'] ) return Config::$_MASTERADTA_STATUS['INACTIVE'];
        return Config::$_MASTERADTA_STATUS['ACTIVE'];

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
                    <h3> Credit Leaves </h3>
                </div>

                <table class="tablecontent">

                    <thead>
                        <tr>
                            <th>LEAVE ID</th>
                            <th>LEAVE NAME</th>
                            <th>LEAVE INTERVAL</th>
                            <th>CYCLE DATE</th>
                            <th>LEAVE INCREMENT</th>
                            <th>TIME REMAINING</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody id="tbody">

                        <?php

                            $masterData = $user->getMasterData(); // Returns Array of Tuples in Database
                            
                            foreach($masterData as $row ){
                                   
                                $cycle_date = "No Date";
                                $statusStyle = "text-black-50";
                                $btnStyle = "disabledbtn";
                                
                                if ( $row['cycleDate'] != '0000-00-00' ){
                                    $statusStyle = "text-black-100";
                                    $cycle_date = date( 'd-m-Y' , strtotime($row['cycleDate']) );
                                }

                                if (  empty( $row['leaveInterval'] ) ) $row['leaveInterval'] = 0;
                                if (  empty( $row['increment'] ) ) $row['increment'] = 0;
                                
                                echo "<tr>";
                                echo "<td class='$statusStyle' >" . $row['leaveID'] . "</td>";
                                echo "<td class='$statusStyle' >" . $row['leaveType'] . "</td>";
                                echo "<td id='interval-" . $row['leaveID'] ."' class='$statusStyle' >" . $row['leaveInterval'] . " Months</td>";
                                echo "<td class='cycleDate date-".$row['leaveID']." $statusStyle' >" . $cycle_date . "</td>";
                                echo "<td class=' $statusStyle' >" . $row['increment'] . " Leaves</td>";
                                echo "<td id='time-". $row['leaveID'] ."' class=' time $statusStyle' > Loding... </td>";
                                echo "<td> <a href='#' id='clickOnIncrement-". $row['leaveID'] ."' > <button id='credit-". $row['leaveID'] ."' class='".$btnStyle." m-0 w-100'> INCREMENT </button></a>";
                                if (  empty( $row['carryForwardInto'] ) ){
                                    echo "<td><button id='credit-". $row['leaveID'] ."' class='disabledbtn m-0 w-100'> CARRY FORWARD </button>";
                                }else{
                                    echo "<td> <a href='./validateIncrementLeaves.php?leaveID=". $row['leaveID'] ."&carryforward=true' > <button id='credit-". $row['leaveID'] ."' class='submitbtn m-0 w-100'> CARRY FORWARD </button> </a> ";
                                }
                            "</td>";

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

        if (isset($_SESSION['response_message'])) {

            $res = unserialize($_SESSION['response_message']);
            unset($_SESSION['response_message']); // Clear the message to prevent displaying it again

            if( $res[1] === "SUCCESS" ){
                echo Utils::alert(htmlspecialchars($res[0]), htmlspecialchars($res[1]) , "manageMasterData.php");
            }else{
                echo Utils::alert($res[0] , $res[1], "manageMasterData.php");
            }

    }

    ?>


    <script>

        dates={}

        let listOfLeaves = document.getElementsByClassName('cycleDate')

        for (const key in listOfLeaves) {

            try{

                let id = listOfLeaves[key].className.split(' ')[1]
                id = id.split('-')[1]

                let date = listOfLeaves[key].innerHTML

                if( date === 'No Date' ){
                    document.getElementById(`time-${id}`).innerHTML = "0 D 00:00:00";
                }else{

                    date = date.split('-');

                    var countDownDate = new Date()
                    countDownDate.setDate( date[0] );
                    countDownDate.setMonth( parseInt(date[1])-1 );
                    countDownDate.setYear( date[2] );

                    var now = <?php echo time() ?> * 1000;

                    countDownDate.setHours( 0 );
                    countDownDate.setMinutes( 0 );
                    countDownDate.setSeconds( 0 );
                    
                    var countDownDateTime = countDownDate.getTime();
                    
                    let interval = parseInt( document.getElementById( `interval-${id}` ).innerHTML ) ;

                    dates[id] = countDownDate;

                    if( countDownDateTime < now ){  
                        document.getElementById(`credit-${id}`).classList.add('creditBtn');


                    while( countDownDateTime < now ){
                    
                        if( (countDownDate.getMonth() + interval) > 11 ){
                            
                            countDownDate.setMonth( (countDownDate.getMonth() + interval)%12 );
                            countDownDate.setFullYear( countDownDate.getFullYear() + 1 );
                            
                        }else{
                            countDownDate.setMonth( countDownDate.getMonth() + interval);
                        }
                        
                        countDownDateTime = countDownDate.getTime();
                    
                    }


                        let newDate = countDownDate.getFullYear() + '-' + (countDownDate.getMonth() + 1) + '-' + countDownDate.getDate();
                        document.getElementById(`clickOnIncrement-${id}`).href = `./validateIncrementLeaves.php?leaveID=${id}&newDate=${newDate}`

                    } 

                    

                    

                }

            }catch(err){
                break;
            }

        }

        
        setInterval(function() { for (const key in dates) { updateCountdown( dates[key] , key ) } }, 1000);

        function updateCountdown( date  , id ){

                    now = now + 1000;

                    // Find the distance between now an the count down date
                    let distance = date.getTime() - now;

                    // Time calculations for days, hours, minutes and seconds
                    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Output the result in an element with id="demo"
                    document.getElementById( `time-${id}` ).innerHTML = days + " D " + hours + ":" +
                        minutes + ":" + seconds;

                    // If the count down is over, write some text 
                    if (distance < 0) {
                        document.getElementById(`time-${id}` ).innerHTML = "EXPIRED";
                    }


        }

 
    </script>


</body>

</html>