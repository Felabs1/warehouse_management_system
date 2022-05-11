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
    <?php
    require "./api/crud.php";
    $crud = new Crud('localhost', 'root', 'Felabs@6986', 'umoja');
    $fetch_products = $crud->fetch_data("SELECT * FROM products");
    ?>
    <div class="w3-auto">
        <span class="w3-large">Make Sales</span><br><br>
        <form id="frmSales">
            <div class="w3-row-padding w3-stretch">
                <div class="w3-col l4">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l4">
                            Product Name
                        </div>
                        <div class="w3-col l6">
                            <select name="productName" onfocus="fillStuff(this.value)" onchange="fillStuff(this.value)" id="productName" class="w3-select w3-border w3-round">
                                <?php
                                foreach ($fetch_products as $row) {
                                ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>

                                <?php
                                }

                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l4">
                            buying Price
                        </div>
                        <div class="w3-col l6">
                            <input type="number" readonly id="buyingPrice" name="buyingPrice" class="w3-input w3-border w3-round">
                        </div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l4">
                            Selling Price
                        </div>
                        <div class="w3-col l6">
                            <input type="number" readonly id="sellingPrice" name="sellingPrice" class="w3-input w3-border w3-round">
                        </div>
                    </div>
                </div>
                <div class="w3-col l4">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l5">
                            Customer Name
                        </div>
                        <div class="w3-col l6">
                            <input type="text" id="customerName" name="customerName" class="w3-input w3-border w3-round">
                        </div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l5">
                            email
                        </div>
                        <div class="w3-col l6">
                            <input type="text" id="customerEmail" name="customerEmail" class="w3-input w3-border w3-round">
                        </div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l5">
                            Address
                        </div>
                        <div class="w3-col l6">
                            <input type="text" id="customerAddress" name="customerAddress" class="w3-input w3-border w3-round">
                        </div>
                    </div>

                </div>
                <div class="w3-col l4">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l5">
                            Contact
                        </div>
                        <div class="w3-col l6">
                            <input type="text" id="customerContact" name="customerContact" class="w3-input w3-border w3-round">
                        </div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l5">
                            Quantity
                        </div>
                        <div class="w3-col l6">
                            <input type="number" min="1" onchange="makeCalc()" onkeyup="makeCalc()" id="quantity" name="quantity" class="w3-input w3-border w3-round">
                        </div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l5">
                            Total Price
                        </div>
                        <div class="w3-col l6">
                            <input type="number" readonly id="total" name="total" class="w3-input w3-border w3-round">
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="w3-center">
                <button type="button" onclick="makeSales()" class="w3-button w3-blue w3-round">Make Sales</button>
                <button type="reset" class="w3-button w3-red w3-round">Cancel</button>
            </div>

        </form>
        <br>
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
                    </tr>
                <?php
                }
                ?>
            </table>

        </div>

    </div>

    <script src="./js/jquery.min.js"></script>
    <script src="./js/main.js"></script>
    <script>
        const makeCalc = () => {
            var unitPrice = $("#sellingPrice").val();
            var quantity = $("#quantity").val();
            var result = parseFloat(unitPrice) * parseFloat(quantity);
            $("#total").val(result.toFixed(2));
        }

        const fillStuff = (x) => {
            $.get('./api/main.php?stuff=' + x, (data) => {
                var result = JSON.parse(data);
                var buyingPrice = $("#buyingPrice");
                buyingPrice.val(result[0].buyingprice);
                console.log(result[0]);
                var sellingPrice = 1.30 * parseFloat(buyingPrice.val());
                $("#sellingPrice").val(sellingPrice.toFixed(2));
            })
        }

        const makeSales = () => {
            var productName = $("#productName");
            var buyingPrice = $("#buyingPrice");
            var sellingPrice = $("#sellingPrice");
            var customerName = $("#customerName");
            var customerEmail = $("#customerEmail");
            var customerAddress = $("#customerAddress");
            var customerContact = $("#customerContact");
            var quantity = $("#quantity");
            var total = $("#total");

            if (productName.val() === "" || buyingPrice.val() === "" || sellingPrice.val() === "" || customerName.val() === "" || customerEmail.val() === "" || customerAddress.val() === "" || customerContact.val() === "" || quantity.val() === "" || total.val() === "") {
                alert("please fill in all the forms");
                return;
            }

            $.ajax({
                url: "./api/main.php?makesales=true",
                method: "POST",
                data: $("#frmSales").serialize(),
                success: (data) => {
                    if (data === "success") {
                        alert("Sold successfully");
                        window.location.href = './customers.php';
                    } else if (data === "stock_out") {
                        alert('inadequate stock in inventory');
                    } else {
                        alert("internal server error");
                        console.log(data);
                    }
                }
            });
        }
    </script>
</body>

</html>