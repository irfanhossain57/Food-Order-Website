<?php
// Retrieve the delivery fee from payment.php
$delivery_fee = isset($_GET['delivery_fee']) ? $_GET['delivery_fee'] : 0;
$payment_method = isset($_GET['payment_method']) ? $_GET['payment_method'] : 'Online'; // Default to 'Online' if not provided

include 'navbar.php';
include 'dbconnect.php';

session_start();
if(isset($_SESSION['customer_id'])) {
    // User is logged in
    $customer_id = $_SESSION['customer_id'];
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}

// $customer_id=1;

// Fetch cart items for the logged-in user
$query = "SELECT * FROM cart_item WHERE cart_id = '$customer_id'"; // Select all cart items for the logged-in user
$result = mysqli_query($connection, $query); // Execute the query

// Initialize an array to store cart items
$cart_items = array();

// Fetch cart items into the array
while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
}

// Calculate total price including delivery fee
function calculateTotalPrice($cart_items, $delivery_fee) {
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['Amount'] * $item['Price'];
    }
    return $total_price + $delivery_fee;
}

// Calculate total price including delivery fee
$total_price = calculateTotalPrice($cart_items, $delivery_fee);

// Insert payment details into payment_history table
foreach ($cart_items as $item) {
    $order_id_query = "SELECT MAX(Order_id) AS max_order_id FROM payment_history WHERE Customer_id = '$customer_id'";
    $max_order_result = mysqli_query($connection, $order_id_query);
    $max_order_row = mysqli_fetch_assoc($max_order_result);
    $order_id = ($max_order_row['max_order_id'] != null) ? $max_order_row['max_order_id'] + 1 : 1;
    $item_name = $item['Items'];
    $amount = $item['Amount'];
    $price = $item['Price'];
    $insert_query = "INSERT INTO payment_history (Customer_id, Order_id, Items, Amount, Price, Payment_method) VALUES ('$customer_id', '$order_id', '$item_name', '$amount', '$price', '$payment_method')";
    mysqli_query($connection, $insert_query);
}

// Delete cart items from cart table
$delete_query = "DELETE FROM cart_item WHERE cart_id = '$customer_id'";
mysqli_query($connection, $delete_query);

mysqli_close($connection); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery</title>
    <style>
        /* Style for the delivery page */
        .delivery-container {
            margin-top: 60px;
            padding: 20px;
            font-size: 18px;
            background-color: #f0f8ff; /* light blue */
            text-align: center; 
        }

        .thank-you-message {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .food-list {
            margin-bottom: 20px; 
        }

        /* Style for the food preparation GIF */
        .food-gif {
            display: block;
            margin: 0 auto; /* Center align GIF */
        }
    </style>
</head>
<body>

<div class="delivery-container">
    <!-- Thank you message -->
    <div class="thank-you-message">
        Thank you for your order. Hang tight while the restaurant processes the food!
    </div>

    <!-- Food preparation GIF -->
    <img src="images/food-preparing.gif" alt="Food Preparation GIF" class="food-gif">

    <!-- Food list -->
    <div class="food-list">
        <h3>Your Order</h3>
        <!-- Cart items -->
        <?php foreach($cart_items as $item): ?>
            <div class="item-container">
                <span>Food Item: <?php echo $item['Items']; ?></span>
                <span>Quantity: <?php echo $item['Amount']; ?></span>
                <span>Unit Price: <?php echo $item['Price']; ?></span>
            </div>
        <?php endforeach; ?>

        <!-- Delivery fee -->
        <div class="delivery-fee">
            Delivery Fee: $<?php echo $delivery_fee; ?>
        </div>

        <!-- Total price -->
        <div class="total-price">
            Total Price: $<?php echo $total_price; ?>
        </div>
    </div>
</div>

</body>
</html>
