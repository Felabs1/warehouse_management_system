<?php

session_start();

require "./crud.php";

$crud = new Crud("localhost", "root", "Felabs@6986", "umoja");

if (isset($_GET['addEmployee'])) {
    $fetch = $crud->fetch_data("SELECT * FROM employees WHERE email = '" . $_POST['empEmail'] . "'");
    if (count($fetch) > 0) {
        echo "email_exist";
    } else {
        $pass_hash = password_hash($_POST['empPassword'], PASSWORD_DEFAULT);
        $insert = $crud->insert_data("employees", [
            "name" => $_POST['empName'],
            "email" => $_POST['empEmail'],
            "contact" => $_POST['empContact'],
            "address" => $_POST['empAddress'],
            "dob" => $_POST['empDob'],
            "password" => $pass_hash,
            "role" => $_POST['empRole']
        ]);


        if ($insert) {
            echo "success";
        } else {
            echo $crud->conn->error;
        }
    }
}

if (isset($_GET['employeedata'])) {
    $fetch = $crud->fetch_data("SELECT * FROM employees");
?>
    <hr>
    <span class="w3-large">Employees</span>
    <table class="w3-table w3-bordered">
        <tr>
            <th>#</th>
            <th>name</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php
        foreach ($fetch as $row) {
        ?>
            <tr>
                <td><?php echo $row['id'] ?></td>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['contact'] ?></td>
                <td><?php echo $row['address'] ?></td>
                <td><?php echo $row['role'] ?></td>
                <td><button type="button" onclick="window.location.href='./payments.php?id=<?php echo $row['id']; ?>'" class="w3-button w3-blue w3-small w3-round">Payments</button>&nbsp;<button class="w3-button w3-blue w3-small w3-round" onclick="edit(<?php echo $row['id']; ?>)">Edit</button>&nbsp;<button class="w3-button w3-blue w3-small w3-round" onclick="deleteEmp(<?php echo $row['id']; ?>)">Delete</button></td>
            </tr>
        <?php
        }
        ?>

    </table>
<?php

}

if (isset($_GET['editEmployee'])) {

    $pass_hash = password_hash($_POST['empPassword'], PASSWORD_DEFAULT);
    $update = $crud->update_data("employees", [
        "name" => $_POST['empName'],
        "email" => $_POST['empEmail'],
        "contact" => $_POST['empContact'],
        "address" => $_POST['empAddress'],
        "dob" => $_POST['empDob'],
        "password" => $pass_hash,
        "role" => $_POST['empRole']
    ], ["id" => $_GET['editEmployee']]);


    if ($update) {
        echo "success";
    } else {
        echo $crud->conn->error;
    }
}

if (isset($_GET['deleteEmp'])) {
    $delete = $crud->delete_data("DELETE FROM employees WHERE id = '" . $_GET['deleteEmp'] . "'");
    if ($delete) {
        echo "success";
    } else {
        echo $crud->conn->error;
    }
}

if (isset($_GET['addproduct'])) {
    // echo $_POST['productName'];
    $fetch = $crud->fetch_data("SELECT * FROM products WHERE `name` = '" . $_POST['productName'] . "'");
    if (count($fetch) > 0) {
        echo "product_exist";
    } else {
        $insert = $crud->insert_data("products", ["name" => $_POST['productName']]);
        if ($insert) {
            echo "success";
        } else {
            echo $crud->conn->error;
        }
    }
}

if (isset($_GET['savesupplies'])) {
    // echo "success";
    $insert = $crud->insert_data("suppliers", [
        "supplierName" => $_POST['supplierName'],
        "mobileNumber" => $_POST['mobileNo'],
        "email" => $_POST['supplierEmail'],
        "productName" => $_POST['supplierProductName'],
        "unitCost" => $_POST['unitCost'],
        "quantity" => $_POST['quantity'],
        "total" => $_POST['totalPrice']
    ]);

    if ($insert) {
        $fetch = $crud->fetch_data("SELECT * FROM products WHERE id = '" . $_POST['supplierProductName'] . "'");
        foreach ($fetch as $row) {
            $initial_quantity = $row['quantity'];
        }
        $next_quantity = $initial_quantity + $_POST['quantity'];
        $update = $crud->update_data("products", ["quantity" => $next_quantity, "buyingprice" => $_POST['unitCost']], ["id" => $_POST['supplierProductName']]);
        if ($update) {
            echo "success";
        }
    }
}

if (isset($_GET['stuff'])) {
    $fetch = $crud->fetch_data("SELECT * FROM products WHERE id = '" . $_GET['stuff'] . "'");
    echo json_encode($fetch);
}

if (isset($_GET['makesales'])) {
    $fetch = $crud->fetch_data("SELECT * FROM products WHERE id = '" . $_POST['productName'] . "'");
    foreach ($fetch as $row) {
        $initial_quantity = $row['quantity'];
    }
    $result = $initial_quantity - $_POST['quantity'];
    if ($result < 0) {
        echo "stock_out";
    } else {
        $insert = $crud->insert_data("sales", [
            "productName" => $_POST['productName'],
            "sellingPrice" => $_POST['sellingPrice'],
            "customerName" => $_POST['customerName'],
            "customerEmail" => $_POST['customerEmail'],
            "customerAddress" => $_POST['customerAddress'],
            "customerContact" => $_POST['customerContact'],
            "quantity" => $_POST['quantity'],
            "total" => $_POST['total']
        ]);
        if ($insert) {

            $update = $crud->update_data("products", ["quantity" => $result], ["id" => $_POST['productName']]);
            if ($update) {
                echo "success";
            } else {
                $crud->conn->error;
            }
        }
    }
}

if (isset($_GET['cashin'])) {
    $fetch = $crud->fetch_data("SELECT SUM(total) AS cash_in FROM sales");
    foreach ($fetch as $row) {
        echo number_format($row['cash_in'], 2);
    }
}

if (isset($_GET['stock'])) {
    $fetch = $crud->fetch_data("SELECT SUM(quantity) AS total_quantity FROM products");
    foreach ($fetch as $row) {
        echo $row['total_quantity'];
    }
}

if (isset($_GET['payemployee'])) {
    $insert = $crud->delete_data("INSERT INTO payments (cust_id, pay_date, amount_paid) VALUES ('" . $_GET['payemployee'] . "', '" . $_POST['datePaid'] . "', '" . $_POST['amountPaid'] . "')");
    if ($insert) {
        echo "success";
    } else {
        // echo $crud->conn->error;
        echo $_POST['datePaid'];
        echo $_POST['amountPaid'];
    }
}

if (isset($_GET['cashout'])) {
    $fetchSupply = $crud->fetch_data("SELECT SUM(total) as total_sold FROM suppliers");
    $fetchSalary = $crud->fetch_data("SELECT SUM(amount_paid) as total_amount_paid FROM payments");
    foreach ($fetchSupply as $row1) {
        $r1 = $row1;
    }

    foreach ($fetchSalary as $row2) {
        $r2 = $row2;
    }

    $total = $r1['total_sold'] + $r2['total_amount_paid'];
    echo number_format($total, 2);
}

if (isset($_GET['balance'])) {
    $fetchSupply = $crud->fetch_data("SELECT SUM(total) as total_sold FROM suppliers");
    $fetchSalary = $crud->fetch_data("SELECT SUM(amount_paid) as total_amount_paid FROM payments");
    foreach ($fetchSupply as $row1) {
        $r1 = $row1;
    }

    foreach ($fetchSalary as $row2) {
        $r2 = $row2;
    }

    $cash_out = $r1['total_sold'] + $r2['total_amount_paid'];

    $fetch = $crud->fetch_data("SELECT SUM(total) AS cash_in FROM sales");
    foreach ($fetch as $row) {
        $cash_in = $row['cash_in'];
    }

    $account = $cash_in - $cash_out;
    echo number_format($account, 2);
}

if (isset($_GET['login'])) {
    $fetch = $crud->fetch_data("SELECT * FROM employees WHERE email = '" . $_POST['email'] . "'");
    if (count($fetch) === 1) {
        foreach ($fetch as $row) {
            if (password_verify($_POST['password'], $row['password'])) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['usertype'] = $row['usertype'];
                echo "login_success";
            } else {
                echo "incorrect_password";
            }
        }
    } else {
        echo "invalid_user";
    }
}

if (isset($_GET['date1'])) {
}

?>