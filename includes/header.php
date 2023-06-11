<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
</head>

<body>
    <!-- Horizontal Header Bar-->
    <div class="horizontal_navbar">
        <h1 class="Heading_Heder ml-3">Bajaj Institute Technology Wardha</h1>

        <a href="#" class="notification-icon" onclick="toggleNotification()"><i class="fa-sharp fa-solid fa-bell"></i></a>

    </div>

    <!-- Div For toggleNotification -->

    <div id="notification" class="notification-content">

        <h3 mb-2 >Notifications</h3>

        <?php

            //Get Notifications
            $notifications = $user->getNotifications();

            while($row = mysqli_fetch_assoc($notifications)){

                //Get the Difference of Time and Show that
                date_default_timezone_set('Asia/Kolkata');
                $diff = round(abs( time() -  strtotime($row['dateTime'])) / 60 , 0);
                $time = 0;
                if( $diff < 60 ) $time = $diff. " Mins Ago";
                else if( $diff >= 60 && $diff < (24*60 ) ) $time = round( $diff/60 , 0 ). " Hrs Ago";
                else $time = round($diff/ 3600 , 0 ). " Days Ago";


                //Print Notifications
                echo  "
                <div class='notification-item' >
                    <span>" .$row['notification']. "</span>
                    <span class='notification-time' >" .$time. "</span>
                </div>";    
            }


        ?>
    </div>

    <!-- Script For toggleNotification div -->
    <script>
        function toggleNotification() {
            var notification = document.getElementById("notification");
            notification.style.display = (notification.style.display === "block") ? "none" : "block";
        }
    </script>
</body>

</html>