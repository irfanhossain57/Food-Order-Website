<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Food Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="number"],
        input[type="submit"] {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        .delete-btn {
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        .description-btn {
            background-color: #2196f3;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .description-btn:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
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

        // Function to generate ID for food items
        function generateFoodItemID($conn) {
            $sql = "SELECT MAX(CAST(SUBSTRING(food_id, 5) AS UNSIGNED)) AS max_id FROM food_item";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $max_id = $row['max_id'];
            if (!$max_id) {
                return "food1";
            } else {
                return "food" . ($max_id + 1);
            }
        }

        // Function to generate ID for addons
        function generateAddonID($conn) {
            $sql = "SELECT MAX(CAST(SUBSTRING(add_ons_id, 6) AS UNSIGNED)) AS max_id FROM add_ons";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $max_id = $row['max_id'];
            if (!$max_id) {
                return "addon1";
            } else {
                return "addon" . ($max_id + 1);
            }
        }

        // Form to add new food item
        echo "<h2>Add Food Item</h2>";
        echo "<form method='post' action=''>";
        echo "<input type='text' name='food_item_name' placeholder='Food Item Name' required>";
        echo "<input type='number' name='food_item_price' placeholder='Price' min='0' step='0.01' required>";
        echo "<input type='text' name='food_item_type' placeholder='Food Type' required>";
        echo "<textarea name='food_item_description' placeholder='Description'></textarea>";
        echo "<input type='submit' name='add_food_item' value='Add Food Item'>";
        echo "</form>";

        // Check if add food item form is submitted
        if(isset($_POST['add_food_item'])) {
            $food_item_name = $_POST['food_item_name'];
            $food_item_price = $_POST['food_item_price'];
            $food_item_type = $_POST['food_item_type'];
            $food_item_description = $_POST['food_item_description'];
            if (!empty($food_item_name) && !empty($food_item_price) && !empty($food_item_type)) {
                $food_id = generateFoodItemID($conn);
                $sql = "INSERT INTO food_item (food_id, name, price, Food_type, description) VALUES ('$food_id', '$food_item_name', $food_item_price, '$food_item_type', '$food_item_description')";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Food item added successfully!</p>";
                } else {
                    if ($conn->errno == 1062) { // Error number for duplicate entry
                        echo "<p>Food item already exists.</p>";
                    } else {
                        echo "Error adding food item: " . $conn->error;
                    }
                }
            } else {
                echo "<p>Please fill all fields.</p>";
            }
        }

        // Form to add new addon
        echo "<h2>Add Addon</h2>";
        echo "<form method='post' action=''>";
        echo "<input type='text' name='addon_name' placeholder='Addon Name' required>";
        echo "<input type='number' name='addon_price' placeholder='Price' min='0' step='0.01' required>";
        echo "<input type='text' name='addon_food_type' placeholder='Food Type' required>";
        echo "<input type='submit' name='add_addon' value='Add Addon'>";
        echo "</form>";

        // Check if add addon form is submitted
        if(isset($_POST['add_addon'])) {
            $addon_name = $_POST['addon_name'];
            $addon_price = $_POST['addon_price'];
            $addon_food_type = $_POST['addon_food_type'];
            if (!empty($addon_name) && !empty($addon_price) && !empty($addon_food_type)) {
                $addon_id = generateAddonID($conn);
                $sql = "INSERT INTO add_ons (add_ons_id, name, price, food_type) VALUES ('$addon_id', '$addon_name', $addon_price, '$addon_food_type')";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Addon added successfully!</p>";
                } else {
                    if ($conn->errno == 1062) { // Error number for duplicate entry
                        echo "<p>Addon already exists.</p>";
                    } else {
                        echo "Error adding addon: " . $conn->error;
                    }
                }
            } else {
                echo "<p>Please fill all fields.</p>";
            }
        }

        // Display existing addons
        echo "<h2>Existing Addons</h2>";
        $sql = "SELECT add_ons_id, name, price, food_type FROM add_ons";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<li>" . $row["name"] . " - Type: " . $row["food_type"] . " (Tk " . $row["price"] . ") <form method='post' action=''><input type='hidden' name='addon_id' value='" . $row["add_ons_id"] . "'><input class='delete-btn' type='submit' name='delete_addon' value='Delete'></form></li>";
            }
            echo "</ul>";
        } else {
            echo "No addons found.";
        }

        // Check if delete addon form is submitted
        if(isset($_POST['delete_addon'])) {
            $addon_id = $_POST['addon_id'];
            $sql = "DELETE FROM add_ons WHERE add_ons_id = '$addon_id'";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Addon deleted successfully!</p>";
            } else {
                echo "Error deleting addon: " . $conn->error;
            }
        }

        // Display existing food items
        echo "<h2>Existing Food Items</h2>";
        $sql = "SELECT food_id, name, price, Food_type FROM food_item";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<li>" . $row["name"] . " - Type: " . $row["Food_type"] . " (Tk " . $row["price"] . ") <form method='post' action=''><input type='hidden' name='food_id' value='" . $row["food_id"] . "'><input class='delete-btn' type='submit' name='delete_food' value='Delete'></form>";
                echo "<form method='post' action=''><input type='hidden' name='food_id' value='" . $row["food_id"] . "'><input class='description-btn' type='submit' name='add_description' value='Description'></form>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "No food items found.";
        }

        // Check if delete food form is submitted
        if(isset($_POST['delete_food'])) {
            $food_id = $_POST['food_id'];
            $sql = "DELETE FROM food_item WHERE food_id = '$food_id'";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Food item deleted successfully!</p>";
            } else {
                echo "Error deleting food item: " . $conn->error;
            }
        }

        // Check if add description form is submitted
        if(isset($_POST['add_description'])) {
            // You can handle adding description functionality here
            // For demonstration purpose, let's just display a message
            echo "<p>Add Description functionality will be implemented here.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>


















































