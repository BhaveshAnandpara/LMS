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

    <div id="notification" class="notification-content shadow-lg">

        <h3 mb-2 >Notifications</h3>

        <?php

            //Get Notifications
            $notifications = $user->getNotifications();


            while($row = mysqli_fetch_assoc($notifications)){

                $time = Utils::getTimeDiff( $row['dateTime'] );


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
            var container = document.querySelector(".container")
            notification.style.display = (notification.style.display === "block") ? "none" : "block";
            container.style.filter = (container.style.filter === "blur(5px)") ? "none" : "blur(5px)";
        }
    </script>
</body>

</html>