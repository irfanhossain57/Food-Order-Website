<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Food Ordering System</title>
    <style>
        body {
            background-color:purple; /* Background color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color:grey; /* Container background color */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .error-msg {
            color: red;
            margin-top: 10px;
        }

        h2 {
            font-weight: bold; /* Make the sign-up title bold */
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #007bff; /* Blue color for the sign-up button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue color on hover */
        }
    </style>
    <script>
        function validatePhoneNumber() {
            var phoneNumber = document.getElementById("phone").value;
            if (phoneNumber.length !== 11 || isNaN(phoneNumber)) {
                alert("Phone number must be exactly 11 digits.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="sign-up-form" onsubmit="return validatePhoneNumber()">
            <h2>Sign Up</h2>
            <?php if(isset($_GET['error']) && $_GET['error'] == 1) { ?>
                <div class="error-msg">Customer ID already exists. Please choose another one.</div>
            <?php } ?>
            <div class="form-group">
                <label for="customer_id">Customer ID:</label>
                <input type="text" id="customer_id" name="customer_id" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $customer_id = $_POST['customer_id'];
        $phone = $_POST['phone'];
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $location = $_POST['location'];

        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "food_order");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if customer ID already exists
        $check_stmt = $conn->prepare("SELECT * FROM customer WHERE Customer_id = ?");
        $check_stmt->bind_param("s", $customer_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Customer ID already exists, redirect back to sign-up page with error
            header("Location: signup.php?error=1");
            exit();
        }

        // Prepare and execute SQL query to insert new customer data
        $stmt = $conn->prepare("INSERT INTO customer (Customer_id, Phone, Name, Username, Password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $customer_id, $phone, $name, $username, $password);
        $stmt->execute();

        // Update customer location
        $location_stmt = $conn->prepare("INSERT INTO customer_location (Customer_id, Location) VALUES (?, ?)");
        $location_stmt->bind_param("ss", $customer_id, $location);
        $location_stmt->execute();

        // Redirect to user page after successful sign-up
        $_SESSION['username'] = $username; // Set session for logged-in user
        header("Location: user_page.php");
        exit();
    }
    ?>
</body>
</html>
