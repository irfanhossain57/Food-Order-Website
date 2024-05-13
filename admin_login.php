<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Food Ordering System</title>
    <style>
        body {
            background-color: #f0f0f0; /* Background color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff; /* Container background color */
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
            font-weight: bold; /* Make the login title bold */
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
            background-color: #007bff; /* Blue color for the login button */
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
</head>
<body>
    <div class="container">
        <!-- Admin Login Form -->
        <form action="admin_login.php" method="post">
            <h2>Admin Login</h2>
            <?php if(isset($_GET['error']) && $_GET['error'] == 1) { ?>
                <div class="error-msg">Invalid admin ID or password</div>
            <?php } ?>
            <div class="form-group">
                <label for="admin_id">Admin ID:</label>
                <input type="text" id="admin_id" name="admin_id" required>
            </div>
            <div class="form-group">
                <label for="admin_password">Password:</label>
                <input type="password" id="admin_password" name="admin_password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $Admin_id = $_POST['admin_id'];
    $Password = $_POST['admin_password'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "food_order");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL query to check admin credentials
    $stmt = $conn->prepare("SELECT * FROM admin WHERE Admin_id=? AND Password=?");
    $stmt->bind_param("ss", $Admin_id, $Password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists with the given credentials
    if ($result->num_rows == 1) {
        // Authentication successful, set session and redirect to admin dashboard
        $_SESSION['admin_id'] = $admin_id;
        header("Location: admin.php");
        exit();
    } else {
        // Authentication failed, redirect back to admin login page with error message
        header("Location: admin_login.php?error=1");
        exit();
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
