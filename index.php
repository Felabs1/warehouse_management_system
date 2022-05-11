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
    </div><br>
    <div class="w3-auto">
        <div class="w3-row-padding w3-stretch">
            <div class="w3-col l3">
                <div class="w3-container w3-round" style="background-color: #efb082;">
                    <div class="w3-container w3-padding w3-border-bottom">
                        <span class="w3-large w3-text-white">Cash In</span>
                    </div>
                    <div class="w3-container w3-padding">
                        <span class="w3-xlarge w3-text-white" id="cashIn">44,000</span>
                    </div>
                </div>
            </div>
            <div class="w3-col l3">
                <div class="w3-container w3-round" style="background-color: #d8c4de;">
                    <div class="w3-container w3-padding w3-border-bottom">
                        <span class="w3-large w3-text-white">Cash Out</span>
                    </div>
                    <div class="w3-container w3-padding">
                        <span class="w3-xlarge w3-text-white" id="cashout">48,000</span>
                    </div>
                </div>
            </div>
            <div class="w3-col l3">
                <div class="w3-container w3-round" style="background-color: #86a5d2;">
                    <div class="w3-container w3-padding w3-border-bottom">
                        <span class="w3-large w3-text-white">Current Stock</span>
                    </div>
                    <div class="w3-container w3-padding">
                        <span class="w3-xlarge w3-text-white" id="stock">144</span>
                    </div>
                </div>
            </div>
            <div class="w3-col l3">
                <div class="w3-container w3-round" style="background-color: #e283ac;">
                    <div class="w3-container w3-padding w3-border-bottom">
                        <span class="w3-large w3-text-white">Account</span>
                    </div>
                    <div class="w3-container w3-padding">
                        <span class="w3-xlarge w3-text-white" id="account">-4,000</span>
                    </div>
                </div>
            </div>

        </div>

        <br><br>
        <div id="printable">
            <span class="w3-large">Supplies</span>
            <div id="datadiv">
                <?php
                require "./api/crud.php";
                $crud = new Crud("localhost", "root", "Felabs@6986", "umoja");
                $fetch = $crud->fetch_data("SELECT * FROM suppliers");
                ?>
                <table class="w3-table w3-bordered">
                    <tr>
                        <th>Supplier Name</th>
                        <th>Product Name</th>
                        <th>Unit Cost</th>
                        <th>Quantity</th>
                        <th>date Supplied</th>
                    </tr>
                    <?php
                    foreach ($fetch as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['supplierName']; ?></td>
                            <td><?php
                                $fetch = $crud->fetch_data("SELECT * FROM products WHERE id = '" . $row['productName'] . "'");
                                foreach ($fetch as $row2) {
                                    echo $row2['name'];
                                }
                                ?></td>
                            <td><?php echo $row['unitCost']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['transaction_date']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>

            </div>
            <br>
            <span class="w3-large">Sales</span>
            <div>
                <?php
                $fetch_products = $crud->fetch_data("SELECT * FROM sales");
                ?>
                <table class="w3-table w3-bordered">
                    <tr>
                        <th>Product Name</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>quantity</th>
                        <th>Unit Cost</th>
                        <th>total</th>
                        <th>Date Sold</th>
                    </tr>
                    <?php
                    foreach ($fetch_products as $row) {
                    ?>
                        <tr>
                            <td><?php echo $row['productName']; ?></td>
                            <td><?php echo $row['customerName']; ?></td>
                            <td><?php echo $row['customerEmail']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['sellingPrice']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td><?php echo $row['date_bought']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>

            </div>
        </div>

        <br>
        <div class="w3-center" style="display: flex;">
            <br>
            <label for="">Filter</label>&nbsp;&nbsp;
            <input type="date" id="date1" style="width: 200px;" class="w3-input w3-border w3-round">&nbsp;
            <input type="date" id="date2" style="width: 200px;" class="w3-input w3-border w3-round">
            &nbsp;<button type="button" onclick="filter()" class="w3-button w3-border w3-round">fiter</button>
        </div><br>
        <div class="w3-center">
            <button class="w3-button w3-blue w3-round" onclick="printDocument('printable')">Print</button>
        </div>
    </div>

    <script src="./js/jquery.min.js"></script>
    <script src="./js/main.js"></script>
    <script>
        setInterval(() => {
            $.get('./api/main.php?cashin=true', (data) => {
                $("#cashIn").html(data);
            });
            $.get('./api/main.php?stock=true', (data) => {
                $("#stock").html(data);
            });
            $.get('./api/main.php?cashout=true', (data) => {
                $("#cashout").html(data);
            });
            $.get('./api/main.php?balance=true', (data) => {
                $("#account").html(data);
            });
        }, 2000);

        const filter = () => {
            var date1 = $("#date1").val();
            var date2 = $("#date2").val();

            if (date1 === "" || date2 === "") {
                alert('please fill in the dates to filter');
                return;
            }

            $.get(`./api/main.php?date1=${date1}&date2=${date2}`, (data) => {
                console.log(data);
                $("#datadiv").html(data);
            })
        }
    </script>
</body>

</html>