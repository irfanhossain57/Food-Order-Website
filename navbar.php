<?php
include 'dbconnect.php';

session_start();
if(isset($_SESSION['customer_id'])) {
    // User is logged in
    $customer_id = $_SESSION['customer_id'];
} else {
    // User is not logged in, redirected to the login page
    header("Location: login.php");
    exit;
}

// $customer_id=1;

// Query to check items in the user's cart
$query = "SELECT COUNT(*) AS cart_count FROM cart_item WHERE cart_id = '$customer_id'";
$result = mysqli_query($connection, $query);

// Fetch the result
$row = mysqli_fetch_assoc($result);
$cart_count = $row['cart_count'];

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Craft-Your-Crave!</title> <!-- Webpage title. Not navigation bar's -->
    <style>
        /* Style for the navigation bar */
        body, html {
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }
        .navbar {
            background-color: #333;
            overflow: hidden; 
            position: fixed;
            top: 0;
            left:0;
            width: 100%;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 27px;
            font-family: 'Arial', sans-serif;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar a.active {
            background-color: #333;
            color: white;
        }

        .navbar-right {
            float: right;
        }

        .red-dot {
            position: relative;
        }

        .red-dot::after {
            content: '<?php echo ($cart_count > 0) ? $cart_count : ""; ?>';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            display: <?php echo ($cart_count > 0) ? "block" : "none"; ?>;
            font-size: 10px;
            line-height: 10px;
            text-align: center;
            color: white;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="restaurant.php"><img src="images/home_icon.png" alt="Home"></a>
    <a href="#" class="active">Craft-Your-Crave!</a>
    <div class="navbar-right">
        <a href="user_page.php"><img src="images/user_profile_icon.png" alt="Profile"></a>
        <a href="payment.php" class="red-dot"><img src="images/shopping_cart_icon.png" alt="Cart"></a>
    </div>
</div>

</body>
</html>