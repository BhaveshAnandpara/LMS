<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>

</head>

<body>

    <? include  "./utils/Config/Config.class.php" ?>

    <div class="main">

        <h1>BIT Leave Management System</h1>

        <!-- HTML API for Sign in with Google Function -->
        <!-- //! Do not remove this part  -->

        <div id="g_id_onload" data-client_id="983317266916-01juk5ugf6rfg0fop30213s6d0k3atun.apps.googleusercontent.com"
            data-context="signin" data-ux_mode="popup" data-auto_prompt="false"
            data-callback='handleCredentialResponse'>
        </div>

        <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline"
            data-text="signin_with" data-size="large" data-locale="en-GB" data-logo_alignment="left">
        </div>

    </div>

    <!-- Required to run HTML Google API  -->
    <!-- //! Do not remove this part  -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://unpkg.com/jwt-decode/build/jwt-decode.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <!-- Script to validate emails -->
    <script>

    function base64UrlDecode(base64Url) {
            // Convert base64Url to base64 by adding padding characters
            while (base64Url.length % 4 !== 0) {
                base64Url += '=';
            }

            // Decode the base64 string
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            return atob(base64);
        }

    function decodeJwt(jwt) {
            const parts = jwt.split('.');
            if (parts.length !== 3) {
                throw new Error('Invalid JWT format');
            }

            const payload = base64UrlDecode(parts[1]);
            return JSON.parse(payload);
        }


    function handleCredentialResponse(response) {

        // decodes the credentials
        const responsePayload = decodeJwt(response.credential);
        const email = responsePayload.email

        console.log(email);
    
        // Validates College Email
        if (email.includes('@bitwardha')) {

            // Runs authentication.class.php file and return user information
            $.ajax({
                url: "./utils/authentication.class.php",
                type: "post",
                data: {
                    function: "validateUser",
                    email
                },

                success: function(response) {

                    console.log(response);
                    const userData = JSON.parse(response)


                    // Redirect User to there respective dashboards

                    if( userData.status === "INACTIVE" ) alert( "You are not authorised to access this" )
                    else if (userData.role === "ADMIN") window.location.href = './pages/Admin/dashboard.php'
                     else if (userData.role === "FACULTY") window.location.href = './pages/Staff/dashboard.php'
                    else if (userData.role === "HOD") window.location.href = './pages/HOD/dashboard.php'
                    else if (userData.role === "PRINCIPAL") window.location.href = './pages/Principal/dashboard.php'

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    alert("Error occured During LOGIN !!")
                    window.location.href = "index.php"
                }
            });

        } else {
            alert('Invalid Email ID')
            window.location.href = "index.php"
        }

    }
    </script>

</body>



</html>