<?php
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

// Fetch cart items for the user
$query = "SELECT * FROM cart_item WHERE cart_id = '$customer_id'"; // Select all cart items for the user
$result = mysqli_query($connection, $query);

$cart_items = array();

// Put cart items into the array
while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
}

// Function to calculate total price
function calculateTotalPrice($cart_items) {
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['Amount'] * $item['Price'];
    }
    return $total_price;
}

// Update quantity if the quantity adjustment form is submitted
if(isset($_POST['cart_item_id']) && isset($_POST['new_quantity'])) {
    $cart_item_id = $_POST['cart_item_id'];
    $new_quantity = $_POST['new_quantity'];
    
    // Update quantity in the database
    $update_query = "UPDATE cart_item SET Amount = $new_quantity WHERE Cart_item_id = '$cart_item_id'"; 
    mysqli_query($connection, $update_query);

    // If new quantity is 0, remove the item from the database
    if ($new_quantity == 0) {
        $delete_query = "DELETE FROM cart_item WHERE Cart_item_id = '$cart_item_id'";
        mysqli_query($connection, $delete_query);
    }

    // Redirect back to the payment page to reflect the changes
    header("Location: payment.php");
    exit;
}

// Fetch locations associated with the customer
$location_query = "SELECT location FROM customer_city WHERE Customer_id = '$customer_id'";
$location_result = mysqli_query($connection, $location_query);

$locations = array();

// Fetch locations into the array
while ($row = mysqli_fetch_assoc($location_result)) {
    $locations[] = $row['location'];
}

// Determine delivery fee based on selected location
$delivery_fee = 100;
if(isset($_POST['selected_location'])) {
    $selected_location = $_POST['selected_location'];

    // Fetch city from the database for the selected location
    $city_query = "SELECT City FROM customer_city WHERE Customer_id = '$customer_id' AND Location = '$selected_location'";
    $city_result = mysqli_query($connection, $city_query);
    $selected_city = mysqli_fetch_assoc($city_result)['City'];

    // Fetch restaurant city from the database
    $restaurant_query = "SELECT City FROM resturant WHERE Resturant_id = (SELECT Restaurant_id FROM cart_item WHERE Cart_id = $customer_id LIMIT 1)";
    $restaurant_result = mysqli_query($connection, $restaurant_query); 
    $restaurant_city = mysqli_fetch_assoc($restaurant_result)['City']; 

    // Determine delivery fee
    $delivery_fee = ($selected_city === $restaurant_city) ? 50 : 100;
}


$total_price = calculateTotalPrice($cart_items) + $delivery_fee;
mysqli_close($connection); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        /* Style for the payment page */
        .payment-container {
            margin-top: 60px;
            padding: 20px;
            font-size: 18px;
            background-color: #f0f8ff; /* Light blue */
        }

        .item-container {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .item-container span {
            margin-bottom: 5px;
            margin-right: 10px;
        }

        .item-container select {
            font-size: 16px;
            padding: 5px;
            margin-left: 10px;
        }

        .total-price {
            margin-top: 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .delivery-fee {
            margin-bottom: 10px;
        }

        .payment-options {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .payment-options button {
            padding: 10px 20px;
            font-size: 16px;
            margin-right: 10px;
            border: none;
            cursor: pointer;
        }

        .payment-options button.selected {
            background-color: #4CAF50; /* Change background color when selected */
            color: white;
        }

        /* Hide credit card input fields by default */
        .credit-card-fields {
            display: none;
        }

        /* Show credit card input fields when "Credit Card" option is selected */
        .credit-card-fields.active {
            display: block;
            margin-top: 20px;
        }

        .credit-card-fields input {
            margin-bottom: 10px;
            padding: 5px;
            font-size: 16px;
            width: 100%;
        }

        /* Mobile banking options */
        .mobile-banking-options {
            display: none;
            margin-top: 20px;
        }

        .mobile-banking-options.active {
            display: block;
        }

        .mobile-banking-options label {
            display: block;
            margin-bottom: 10px;
        }

        /* Error message style */
        #error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h2>Payment</h2>

    <!-- Cart items -->
    <?php foreach($cart_items as $item): ?>
        <div class="item-container"> <!-- show the items on screen-->
            <span>Food Item: <?php echo $item['Items']; ?></span>
            <span>Quantity: <?php echo $item['Amount']; ?></span>
            <span>Unit Price: <?php echo $item['Price']; ?></span>
            <form method="POST"> <!-- when (+) or (-) button pressed, refresh the page -->
                <input type="hidden" name="cart_item_id" value="<?php echo $item['Cart_item_id']; ?>">
                <button type="submit" name="new_quantity" value="<?php echo $item['Amount'] + 1; ?>">+</button>
                <button type="submit" name="new_quantity" value="<?php echo max(0, $item['Amount'] - 1); ?>">-</button>
            </form>
        </div>
    <?php endforeach; ?>

    <!-- Location options -->
    <div class="location-options">
        <?php foreach($locations as $location): ?>
            <button class="location-option" onclick="selectLocationOption(this, '<?php echo $location; ?>')"><?php echo $location; ?></button>
        <?php endforeach; ?>
        <form id="location-form" method="POST" style="display: none;"> <!-- when a location is selected, save & refresh the page -->
            <input type="hidden" name="selected_location" id="selected_location">
        </form>
    </div>

    <!-- Delivery fee -->
    <div class="delivery-fee">
        Delivery Fee: $<?php echo $delivery_fee; ?>
    </div>

    <!-- Total price -->
    <div class="total-price">
        Total Price: $<?php echo $total_price; ?>
    </div>

    <!-- Payment options -->
    <div class="payment-options">
        <button class="payment-option" onclick="selectPaymentOption(this, 'cash')">Cash on Delivery</button>
        <button class="payment-option" onclick="selectPaymentOption(this, 'credit')">Credit Card</button>
        <button class="payment-option" onclick="selectPaymentOption(this, 'mobile')">Mobile Banking</button>
    </div>

    <!-- Credit card input fields -->
    <div class="credit-card-fields">
        <input type="text" name="cc_number" placeholder="Credit Card Number">
        <input type="text" name="cc_cvv" placeholder="CVV/CVC">
    </div>

    <!-- Mobile banking options -->
    <div class="mobile-banking-options">
        <label><input type="radio" name="mobile_banking_option" value="Bkash"> Bkash</label>
        <label><input type="radio" name="mobile_banking_option" value="Nagad"> Nagad</label>
    </div>

    <!-- Error message -->
    <div id="error-message"></div>

    <!-- Proceed to payment button -->
    <button onclick="proceedToPayment()">Proceed to Payment</button>

</div>

<script>
    function proceedToPayment() {
        // Check if a payment option is selected
        var selectedPaymentOption = document.querySelector('.payment-options .selected');
        if (!selectedPaymentOption) {
            document.getElementById('error-message').innerText = "Please choose a payment option.";
            return;
        }
        var paymentMethod = selectedPaymentOption.innerText; // Get the text of the selected button

        // Check credit card input fields if credit card option is selected
        if (selectedPaymentOption.innerText === "Credit Card") {
            var ccNumber = document.querySelector('.credit-card-fields input[name="cc_number"]').value;
            var ccCvv = document.querySelector('.credit-card-fields input[name="cc_cvv"]').value;
            if (!isValidCreditCardNumber(ccNumber) || !isValidCCV(ccCvv)) { //checks if ccNumber & ccCVV are invalid or not
                document.getElementById('error-message').innerText = "Please enter a valid credit card number and CCV.";
                return;
            }
        }

        // Check mobile banking options if mobile banking option is selected
        if (selectedPaymentOption.innerText === "Mobile Banking") {
            var selectedMobileBankingOption = document.querySelector('.mobile-banking-options input[name="mobile_banking_option"]:checked');
            if (!selectedMobileBankingOption) {
                document.getElementById('error-message').innerText = "Please choose a mobile banking option.";
                return;
            }
        }

        // If all validations pass, proceed to payment
        document.getElementById('error-message').innerText = ""; // Clear error message
        // Redirect to delivery.php with the delivery fee
        if (paymentMethod = "cash") {
            <?php $method = "cash_on_delivery";?>
            window.location.href = "delivery.php?payment_method=<?php echo $method; ?>&delivery_fee=<?php echo $delivery_fee; ?>";
        } else if (paymentMethod = "credit") {
            <?php $method = "credit_card";?>
            window.location.href = "delivery.php?payment_method=<?php echo $method; ?>&delivery_fee=<?php echo $delivery_fee; ?>";
        } else if (paymentMethod = "mobile") {
            <?php $method = "mobile_banking";?>
            window.location.href = "delivery.php?payment_method=<?php echo $method; ?>&delivery_fee=<?php echo $delivery_fee; ?>";
        }
    }

    function selectPaymentOption(button, option) {
        // Remove 'selected' class from all buttons
        var buttons = document.querySelectorAll('.payment-options button');
        buttons.forEach(btn => btn.classList.remove('selected'));

        // Add 'selected' class to the clicked button
        button.classList.add('selected');

        // Show/hide credit card fields based on selected option
        var creditCardFields = document.querySelector('.credit-card-fields');
        if (option === 'credit') {
            creditCardFields.classList.add('active');
        } else {
            creditCardFields.classList.remove('active');
        }

        // Show/hide mobile banking options based on selected option
        var mobileBankingOptions = document.querySelector('.mobile-banking-options');
        if (option === 'mobile') {
            mobileBankingOptions.classList.add('active');
        } else {
            mobileBankingOptions.classList.remove('active');
        }
    }

    function selectLocationOption(button, location) {
        // Remove 'selected' class from all buttons
        var buttons = document.querySelectorAll('.location-options button');
        buttons.forEach(btn => btn.classList.remove('selected'));

        // Add 'selected' class to the clicked button
        button.classList.add('selected');

        // Set the selected location in the form
        document.querySelector('input[name="selected_location"]').value = location;

        // Submit the form to recalculate delivery fee
        document.getElementById('location-form').submit();
    }

    function isValidCreditCardNumber(ccNumber) {
        // Check if ccNumber contains only digits and has a length of 16
        return /^\d{16}$/.test(ccNumber);
    }

    function isValidCCV(ccCvv) {
        // Check if ccCvv contains only digits and has a length of 3
        return /^\d{3}$/.test(ccCvv);
    }
</script>

</body>
</html>
