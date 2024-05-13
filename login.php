<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Food Ordering System</title>
    <style>
        body {
			background-image : url('login.jpg');
            background-color: #f0f0f0; /* Background color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: Grey; /* Container background color */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: Absolute; /* Make the container position relative */
        }

        .login-form {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .error-msg {
            color: red;
            margin-top: 10px;
        }

        h2 {
            font-weight: bold; /* Make the login title bold */
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
            <h2>Login</h2>
            <?php if(isset($_GET['error']) && $_GET['error'] == 1) { ?>
                <div class="error-msg">Invalid username or password</div>
            <?php } ?>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>

<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $conn = new mysqli("localhost","root", "", "food_order");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM customer WHERE Username=? AND Password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists with the given credentials
    if ($result->num_rows == 1) {
        // Authentication successful, set session and redirect to restaurant page
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['Username'];
        $_SESSION['customer_id'] = $row['Customer_id']; // Storing customer_id in session if needed

        header("Location: restaurant.php");
        exit();
	}
		
	// elseif ($resultAdmin->num_rows == 1) {
		// Authentication successful for admin, set session and redirect to admin page
		// $rowAdmin = $resultAdmin->fetch_assoc();
		// $_SESSION['username'] = $rowAdmin['Username'];
		// $_SESSION['admin_id'] = $rowAdmin['admin_id']; // Storing admin_id in session if needed
		// header ("Location: admin.php");
		// exit();
    // } 
	else {
        // Authentication failed, redirect back to login page with error message
        header("Location: login.php?error=1");
        exit();
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>

</body>
</html>

