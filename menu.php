<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #08B5D5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding-top: 20px;
        }

        .food-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            border: 10px solid #063F2A;
        }

        .food-details {
            flex: 1;
            text-align: left;
            padding-left: 20px;
        }

        .food-img {
            max-width: 200px;
            max-height: 200px;
        }

        .addons {
            display: none;
        }

        .addons.active {
            display: block;
        }

        .addons-list {
            list-style-type: none;
            padding: 0;
        }

        .addons-list li {
            margin-bottom: 20px;
        }

        .food-details button[type='submit'] {
            margin-top: 10px;
            background-color: #063F2A;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .toggle-addons {
            background-color: #063F2A;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .show-cart-button {
            position: fixed;
            bottom: 20px;
            left: calc(90% - 80px); /* Adjust the value as needed */
            background-color: #063F2A;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Menu</h1>

        <!-- Search Form -->
        <form action="" method="GET" style="margin-left: 450px;">
            <input type="hidden" name="restaurant_name" value="<?php echo $_GET['restaurant_name']; ?>">
            <input type="text" name="search" placeholder="Search for food items" style="padding: 10px 20px;">
            <button type="submit" style="padding: 10px 20px;">Search</button>
        </form>

        <?php
        
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "food_order";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Function to fetch add-ons based on food Type
        if (!function_exists('fetchAddons')) {
            function fetchAddons($foodId, $conn) {
                $sql = "SELECT * FROM add_ons WHERE Food_Type = (SELECT Food_Type FROM food_item WHERE Food_id = '$foodId')";
                $result = $conn->query($sql);

                $addons = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $addons[] = $row;
                    }
                }
                return $addons;
            }
        }

        // Function to generate cart item ID
        function generateCartItemID($conn) {
            $sql = "SELECT MAX(CAST(SUBSTRING(Cart_item_id, 5) AS UNSIGNED)) AS max_id FROM cart_item";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $max_id = $row['max_id'];
            if (!$max_id) {
                return "item1";
            } else {
                return "item" . ($max_id + 1);
            }
        }

        // Function to get the quantity of an item in the cart from the database
        function getCartItemQuantity($itemName, $conn) {
            $sql = "SELECT Amount FROM cart_item WHERE Items = '$itemName'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['Amount'];
            } else {
                return 0;
            }
        }
        
        // Assuming customer/cart ID is hardcoded for all items
        //$customer_id = "customer1"; 
		session_start();
		if(isset($_SESSION['customer_id'])) {
			// User is logged in
			$customer_id = $_SESSION['customer_id'];
		} else {
			// User is not logged in, redirect to the login page
			header("Location: login.php");
			exit;
		}
		
		//geting the resturant_id for the selected resturant
        $sql = "SELECT Resturant_id FROM resturant WHERE Name = '" . $_GET['restaurant_name'] . "'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Fetching the restaurant_id from the result
            $row = $result->fetch_assoc();
            $restaurant_id = $row['Resturant_id'];

            
        } else {
            // If no matching restaurant found, you can handle it accordingly
            echo "No restaurant found with the provided name.";
        }
        
        
        
        
        

        // Handling form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          
            if (isset($_POST['add_to_cart'])) {   
                $item_name = $_POST['item_name'];
                $item_price = $_POST['item_price'];
                $flag = "Food Item"; // Set flag for food items

                // Check if the item is already in the cart
                $sql_check_item = "SELECT * FROM cart_item WHERE Items = '$item_name'";
                $result_check_item = $conn->query($sql_check_item);

                if ($result_check_item->num_rows > 0) {
                    // If the item is already in the cart, the quantity will increase and the price will also change
                    $row = $result_check_item->fetch_assoc();
                    $quantity = $row['Amount'] + 1;
                    $total_price = $quantity * $item_price; 
                    $sql_update_quantity_price = "UPDATE cart_item SET Amount = '$quantity', Price = '$total_price' WHERE Items = '$item_name'";
                    if ($conn->query($sql_update_quantity_price) === TRUE) {
                        echo "Quantity updated successfully!";
                    } else {
                        echo "Error updating quantity: " . $conn->error;
                    }
                } else {
                    // If the item is not in the cart, insert a new row
                    $cart_item_id = generateCartItemID($conn);

                    $sql = "INSERT INTO cart_item (Cart_item_id, cart_id, Items, Amount, Price, Flag, Restaurant_id) 
                            VALUES ('$cart_item_id', '$customer_id', '$item_name', '1', '$item_price', '$flag', '$restaurant_id')";
                    if ($conn->query($sql) === TRUE) {
                        echo "Item added to cart successfully!";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            } elseif (isset($_POST['add_addon_to_cart'])) {
                $addon_name = $_POST['addon_name'];
                $addon_price = $_POST['addon_price'];
                $flag = "Add On Item"; // Set flag for add-ons

                // Check if the add-on item is already in the cart
                $sql_check_item = "SELECT * FROM cart_item WHERE Items = '$addon_name'";
                $result_check_item = $conn->query($sql_check_item);

                if ($result_check_item->num_rows > 0) {
                    // If the add-on item is already in the cart, update the quantity
                    $row = $result_check_item->fetch_assoc();
                    $quantity = $row['Amount'] + 1;
                    $total_price = $quantity * $addon_price; 
                    $sql_update_quantity_price = "UPDATE cart_item SET Amount = '$quantity', Price = '$total_price' WHERE Items = '$addon_name'";
                    if ($conn->query($sql_update_quantity_price) === TRUE) {
                        echo "Quantity updated successfully!";
                    } else {
                        echo "Error updating quantity: " . $conn->error;
                    }
                } else {
                    // If the add-on item is not in the cart, insert a new row
                    $cart_item_id = generateCartItemID($conn);

                    $sql = "INSERT INTO cart_item (Cart_item_id, cart_id, Items, Amount, Price, Flag, Restaurant_id) 
                            VALUES ('$cart_item_id', '$customer_id', '$addon_name', '1', '$addon_price', '$flag', '$restaurant_id')";
                    if ($conn->query($sql) === TRUE) {
                        echo "Add-on added to cart successfully!";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
        }

        // Fetching food items for the selected restaurant
        if(isset($_GET['search'])) {
            $search = $_GET['search'];
            $restaurantName = $_GET['restaurant_name']; 

            
            $sql = "SELECT * FROM food_item WHERE resturant_name = '$restaurantName' AND (Name LIKE '%$search%' OR Description LIKE '%$search%')";
        } else {
            $restaurantName = $_GET['restaurant_name'];
            $sql = "SELECT * FROM food_item WHERE resturant_name = '$restaurantName'";
        }
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='food-items'>";
            while($row = $result->fetch_assoc()) {
                echo "<div class='food-item'>";
                echo "<img class='food-img' src='image_restraunt/{$row['Food_id']}.png' alt='{$row['Name']}'>";
                echo "<div class='food-details'>";
                echo "<h3>{$row['Name']}</h3>";
                echo "<p>{$row['Description']}</p>";
                echo "<p>Price: {$row['Price']}</p>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='item_name' value='{$row['Name']}'>";
                echo "<input type='hidden' name='item_price' value='{$row['Price']}'>";
                $quantity = getCartItemQuantity($row['Name'], $conn);
                echo "<button type='submit' name='add_to_cart'>Add to Cart ({$quantity})</button>";
                echo "</form>";

                echo "<div style='margin-top: 10px;'></div>";
                
                // Button to show add-ons
                $addons = fetchAddons($row['Food_id'], $conn);
                if (!empty($addons)) {
                    echo "<button class='toggle-addons'>Add-ons</button>";
                    echo "<div class='addons'>";
                    echo "<h4>Add-ons</h4>";
                    echo "<ul class='addons-list'>";
                    foreach ($addons as $addon) {
                        echo "<li>{$addon['Name']} - {$addon['Price']} <form action='' method='post'><input type='hidden'
                        name='addon_name' value='{$addon['Name']}'><input type='hidden' name='addon_price' value='{$addon['Price']}'>
                        <button type='submit' name='add_addon_to_cart'>Add to Cart (".getCartItemQuantity($addon['Name'], $conn).")</button></form></li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }

                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "No food items found for this search.";
        
        }

        $conn->close();
        ?>
    </div>

    <!-- Show Cart button -->
    <form id="show-cart-form" action="payment.php" method="get">
        <button class="show-cart-button">Show Cart</button>
    </form>

    <script>
        document.querySelector('.show-cart-button').addEventListener('click', function() {
            document.getElementById('show-cart-form').submit();
        });

        document.querySelectorAll('.toggle-addons').forEach(function(button) {
            button.addEventListener('click', function() {
                button.nextElementSibling.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
