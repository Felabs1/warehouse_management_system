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
        <?php
        require "./api/crud.php";
        $crud = new Crud("localhost", "root", "Felabs@6986", "umoja");
        $fetch = $crud->fetch_data("SELECT * FROM employees WHERE id = '" . $_GET['id'] . "'");
        foreach ($fetch as $row) {
            $r = $row;
        }

        ?>

        <br><br>
        <span class="w3-large"><?php echo $row['name']; ?></span><br>
        <span><?php echo $r['role']; ?></span> |
        <span><?php echo $r['contact']; ?></span> |
        <span><?php echo $r['address']; ?></span>
        <br><br>
        <form id="frmpay">
            <div class=" w3-row-padding w3-stretch">
                <div class="w3-col l6">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">
                            <label for="">Date</label>
                        </div>
                        <div class="w3-col l6">
                            <input type="date" id="datePaid" name="datePaid" class="w3-input w3-border w3-round">
                        </div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">
                            <label for="">Amount</label>
                        </div>
                        <div class="w3-col l6">
                            <input type="number" id="amountPaid" name="amountPaid" min="500" class="w3-input w3-border w3-round">
                        </div>
                    </div>


                </div>
                <button onclick="pay()" type="button" class="w3-button w3-blue w3-round">
                    Pay
                </button>
        </form>
        <div>
            <?php
            $fetch_pay = $crud->fetch_data("SELECT * FROM payments WHERE cust_id = '" . $_GET['id'] . "'");
            ?>
            <br><br><br>
            <table class="w3-table w3-bordered">
                <tr>
                    <th>Pay Date</th>
                    <th>amount paid</th>
                    <th>Date Transacted</th>
                </tr>
                <?php
                foreach ($fetch_pay as $row2) {
                ?>
                    <tr>
                        <td><?php echo $row2['pay_date']; ?></td>
                        <td><?php echo number_format($row2['amount_paid'], 2); ?></td>
                        <td><?php echo $row2['transaction_date']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>

    </div>




    <br>
    <br>
    <div id="dataDiv"></div>
    </div>

    <script src="./js/jquery.min.js"></script>
    <script src="./js/main.js"></script>
    <script>
        const pay = () => {
            var datePaid = $("#datePaid");
            var amountPaid = $("#amountPaid");

            if (datePaid.val() === "" || amountPaid.val() === "") {
                alert('please fill in all the forms');
                return;
            }

            $.ajax({
                url: "./api/main.php?payemployee=" + <?php echo $_GET['id']; ?>,
                method: "POST",
                data: $("#frmpay").serialize(),
                success: (data) => {
                    if (data === "success") {
                        alert('data has been submitted successfully');
                        window.location.href = "./payments.php?id=<?php echo $_GET['id']; ?>";
                    } else {
                        alert('internal server error');
                        console.log(data);
                    }
                }
            })


        }
    </script>
</body>

</html>