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
        $crud = new Crud('localhost', 'root', 'Felabs@6986', 'umoja');
        $fetch_products = $crud->fetch_data("SELECT * FROM products");
        ?>

        <br><br>
        <span class="w3-large">New Supplier</span>
        <div class="w3-right"><button class="w3-button w3-border w3-round" onclick="openModal('id01')">Add New Product</button>
            <div id="id01" class="w3-modal">
                <div class="w3-modal-content" style="width: 30rem">
                    <div class="w3-white w3-padding">
                        <span class="w3-large">Add A Product</span>
                        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
                        <hr>
                        <form id="frmProduct">
                            <input type="text" name="productName" id="productName" placeholder="productName" class="w3-input w3-border w3-round"><br>
                            <button class="w3-button w3-blue w3-round" type="button" onclick="addProduct()">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div><br><br>
        <form id="frmSupplier">
            <div class="w3-row-padding w3-stretch">
                <div class="w3-col l4">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Supplier Name</div>
                        <div class="w3-col l6"><input type="text" name="supplierName" id="supplierName" class="w3-input w3-border w3-round"></div>
                    </div>
                </div>
                <div class="w3-col l4">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Mobile No</div>
                        <div class="w3-col l6"><input type="text" name="mobileNo" id="mobileNo" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Email</div>
                        <div class="w3-col l6"><input type="text" name="supplierEmail" id="supplierEmail" class="w3-input w3-border w3-round"></div>
                    </div>
                </div>
                <div class="w3-col l4">
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Product Name</div>
                        <div class="w3-col l6">
                            <select name="supplierProductName" id="supplierProductName" class="w3-select w3-border w3-round">
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
                        <div class="w3-col l3">Unit Cost</div>
                        <div class="w3-col l6"><input type="number" min="1" name="unitCost" onchange="updateTotal()" onkeyup="updateTotal()" id="unitCost" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">quantity</div>
                        <div class="w3-col l6"><input type="number" min="1" name="quantity" onchange="updateTotal()" onkeyup="updateTotal()" id="quantity" class="w3-input w3-border w3-round"></div>
                    </div>
                    <div class="w3-row-padding w3-stretch">
                        <div class="w3-col l3">Total</div>
                        <div class="w3-col l6"><input readonly type="number" name="totalPrice" id="totalPrice" class="w3-input w3-border w3-round"></div>
                    </div>
                </div>
            </div>
            <div class="w3-center">
                <button class="w3-button w3-blue w3-round" onclick="receiveProduct()" type="button">Receive Product</button>
                <button class="w3-button w3-red w3-round" type="reset">Cancel</button>
            </div>
        </form>
        <br><br>
        <div id="datadiv">
            <?php
            // require "./api/crud.php";
            // $crud = new Crud("localhost", "root", "Felabs@6986", "umoja");
            $fetch = $crud->fetch_data("SELECT * FROM suppliers");
            ?>
            <table class="w3-table w3-bordered">
                <tr>
                    <th>Supplier Name</th>
                    <th>Product Name</th>
                    <th>Unit Cost</th>
                    <th>Quantity</th>
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
        const updateTotal = () => {
            var quantity = $("#quantity").val();
            var unitCost = $("#unitCost").val();
            var total = parseFloat(quantity) * parseFloat(unitCost);
            $("#totalPrice").val(total.toFixed(2));
        }

        const receiveProduct = () => {
            var supplierName = $("#supplierName");
            var mobileNumber = $("#mobileNo");
            var email = $("#supplierEmail");
            var productName = $("#supplierProductName");
            var unitCost = $("#unitCost");
            var quantity = $("#quantity");
            var total = $("#totalPrice");

            if (supplierName.val() === "" || mobileNumber.val() === "" || email.val() === "" || productName.val() === "" || unitCost.val() === "" || quantity.val() === "" || total.val() === "") {
                alert("please fill  in all the forms");
                return;

            }

            $.ajax({
                url: "./api/main.php?savesupplies=true",
                method: "POST",
                data: $("#frmSupplier").serialize(),
                success: (data) => {
                    // console.log(data);
                    if (data === "success") {
                        alert('bought Item Successfully');
                        supplierName.val("");
                        mobileNumber.val("");
                        email.val("");
                        productName.val("");
                        unitCost.val("");
                        quantity.val("");
                        total.val("");
                        window.location.href = './suppliers.php';
                    } else {
                        alert('internal server error')
                    }
                }
            })


        }

        const addProduct = () => {
            var name = $("#productName");
            if (name.val() === "") {
                alert("please fill in the field");
                return;
            }

            var obj = {
                name: name.val()
            };



            console.log(JSON.stringify(obj));

            $.ajax({
                url: "./api/main.php?addproduct=true",
                method: "POST",
                data: $("#frmProduct").serialize(),
                success: (data) => {
                    // console.log(data);
                    // data === "success" ? alert("product successfully inserted") : ;
                    if (data === "success") {
                        alert("product successfully inserted");
                    } else if (data === "product_exist") {
                        alert("the product exists");
                    } else {
                        alert("internal server error");
                        console.log(data);
                    }
                }
            })
        }
    </script>
</body>

</html>