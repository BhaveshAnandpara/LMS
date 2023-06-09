<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!-- Horizontal Header Bar-->
    <div class="horizontal_navbar">
        <h1 class="Heading_Heder ml-3">Bajaj Institute Technology Wardha</h1>
        <a href="#" class="notification-icon" onclick="toggleNotification()"><i class="fa-sharp fa-solid fa-bell"></i></a>
    </div>
    <!-- Div For toggleNotification -->
    <div id="notification" class="notification-content">
        <h3>New Notification</h3>
        <p>This is a sample notification.</p>
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