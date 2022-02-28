<?php
session_start();

if (!isset($_SESSION['email']) && $_SESSION['email'] !== true) {
    header('location: ./login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMOJA</title>
    <link rel="stylesheet" type="text/css" href="./css/w3.css" />
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
</head>

<body>
    <div class="w3-bar w3-blue">
        <a href="./index.php" class="w3-bar-item w3-wide">UMOJA</a>
        <a href="./suppliers.php" class="w3-bar-item ">Suppliers</a>
        <a href="./customers.php" class="w3-bar-item ">Customers</a>
        <a href="./employees.php" class="w3-bar-item ">Employees</a>
        <a href="./logout.php" class="w3-bar-item ">Log Out</a>
    </div>
    <div class="w3-auto">

        <br><br>
        <span class="w3-large">New Employee</span><br><br>
        <form id="frmEmployee">
            <div class="w3-row-padding">
                <div class="w3-col m6">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l2">Name</div>
                        <div class="w3-col l6"><input type="text" name="empName" id="empName" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l2">DOB</div>
                        <div class="w3-col l6"><input type="date" name="empDob" id="empDob" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l2">email</div>
                        <div class="w3-col l6"><input type="text" name="empEmail" id="empEmail" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l2">Contact</div>
                        <div class="w3-col l6"><input type="text" name="empContact" id="empContact" class="w3-input w3-border w3-round"></div>
                    </div>
                </div>
                <div class="w3-col m6">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Password</div>
                        <div class="w3-col l6"><input type="password" name="empPassword" id="empPassword" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Confirm Password</div>
                        <div class="w3-col l6"><input type="password" name="empConfirmPassword" id="empConfirmPassword" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Address</div>
                        <div class="w3-col l6"><input type="text" name="empAddress" id="empAddress" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Role</div>
                        <div class="w3-col l6"><select name="empRole" id="empRole" class="w3-select w3-border w3-round">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select></div>
                    </div>
                </div>
            </div><br>
            <div class="w3-center">
                <button type="button" onclick="addEmployee()" class="w3-button w3-blue w3-round">Add</button>
                <button class="w3-button w3-red w3-round">Cancel</button>

            </div>

        </form>
        <br>
        <br>
        <div id="dataDiv"></div>
    </div>

    <script src="./js/jquery.min.js"></script>
    <script src="./js/main.js"></script>
    <script>
        const addEmployee = () => {
            var name = $("#empName");
            var dob = $("#empDob");
            var email = $("#empEmail");
            var contact = $("#empContact");
            var password = $("#empPassword");
            var confirmPassword = $("#empConfirmPassword");
            var address = $("#empAddress");

            if (name.val() === "" || dob.val() === "" || email.val() === "" || contact.val() === "" || password.val() === "" || confirmPassword.val() === "" || address.val() === "") {
                alert('please fill in all the data before submitting');
                return;

            }

            if (password.val() !== confirmPassword.val()) {
                alert("check on your passwords and make sure they are matching");
                return;
            }

            $.ajax({
                url: "./api/main.php?addEmployee=true",
                method: "POST",
                data: $("#frmEmployee").serialize(),
                success: (data) => {
                    // console.log(data);
                    if (data === "success") {
                        alert("employee inserted succesfully");
                        name.val("");
                        dob.val("");
                        email.val("");
                        contact.val("");
                        password.val("");
                        confirmPassword.val("");
                        address.val() = "";
                    } else if (data === "email_exist") {
                        alert("This email is allready being used");
                        email.focus();
                    } else {
                        alert('internal server error');
                        console.log(data);
                    }
                }
            })
        }

        $.get('./api/main.php?employeedata=true', function(data) {
            $("#dataDiv").html(data);
        });

        const deleteEmp = (id) => {
            if (window.confirm("are you sure") === true) {
                $.get("./api/main.php?deleteEmp=" + id, (data) => {
                    if (data === "success") {
                        alert('deleted successfully');
                        window.location.href = "./employees.php";
                    }
                })
            }

        }

        const edit = (id) => {
            window.location.href = "./editemployee.php?id=" + id;
        }
    </script>
</body>

</html>