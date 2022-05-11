<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMOJA</title>
    <link rel="stylesheet" type="text/css" href="./css/w3.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
</head>

<body>
    <div class="w3-bar w3-blue">
        <a href="#" class="w3-bar-item w3-wide">UMOJA WAREHOUSE MANAGEMENT SYSTEM</a>

    </div><br><br>
    <div class="w3-auto w3-border" style="width: 30rem;">
        <div class="w3-container w3-padding w3-blue">
            <span class="w3-large">Log In</span>
        </div>
        <form id="frmlogin" class="w3-container w3-white"><br>
            <label for="">email</label>
            <input type="text" id="email" name="email" class="w3-input w3-border w3-round">
            <label for="">password</label>
            <input type="password" id="password" name="password" class="w3-input w3-border w3-round">
            <br>
            <button type="button" onclick="login()" class="w3-button w3-blue w3-round w3-block">Log in</button><br>
        </form>
    </div>


    <script src="./js/jquery.min.js"></script>
    <script src="./js/main.js"></script>
    <script>
        "use strict";

        const login = () => {
            let email = $("#email");
            let password = $("#password");

            if (email.val() === "" || password.val() === "") {
                alert('please fill in the form');
                return;
            }

            $.ajax({
                url: "./api/main.php?login=true",
                method: "POST",
                data: $("#frmlogin").serialize(),
                success: function(data) {
                    if (data === "invalid_user") {
                        alert("the username is invalid");
                    } else if (data === "incorrect_password") {
                        alert("the password is incorrect");
                    } else if (data === "login_success") {
                        alert("login successfull redirecting...");
                        window.location.href = './index.php';
                    } else {
                        alert('internal server error');
                        console.log(data);
                    }
                }
            });
        }
    </script>
</body>

</html>