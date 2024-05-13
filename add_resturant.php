<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Restaurants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="submit"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }
        input[type="text"] {
            width: 300px;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .restaurant-list {
            list-style-type: none;
            padding: 0;
        }
        .restaurant-list li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .restaurant-list li:hover {
            background-color: #e9e9e9;
        }
        .restaurant-list li form {
            display: inline;
        }
        .restaurant-list li form input[type="submit"] {
            background-color: #f44336;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .restaurant-list li form input[type="submit"]:hover {
            background-color: #d32f2f;
        }
        footer {
            text-align: center;
            margin-top: auto;
            padding: 20px 0;
            background-color: #fff;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Restaurant</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" name="name" placeholder="Restaurant Name" required>
            <input type="text" name="city" placeholder="City" required> <!-- Add input for city -->
            <input type="submit" name="add_restaurant" value="Add Restaurant">
        </form>

        <h2>Restaurant List</h2>
        <ul class="restaurant-list">
            <?php
            // Function to generate restaurant ID
            function generateRestaurantID($conn) {
                $sql = "SELECT MAX(CAST(SUBSTRING(Resturant_id, 5) AS UNSIGNED)) AS max_id FROM resturant";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $max_id = $row['max_id'];
                if (!$max_id) {
                    return "rest1";
                } else {
                    return "rest" . ($max_id + 1);
                }
            }

            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "food_order";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if the add restaurant form is submitted
            if(isset($_POST['add_restaurant'])){
                $restaurant_name = $_POST['name'];
                $restaurant_city = $_POST['city']; // Get city from form
                if(!empty($restaurant_name) && !empty($restaurant_city)){ // Check if both name and city are provided
                    $restaurant_id = generateRestaurantID($conn); // Generate restaurant ID
                    // Prepare and bind parameters
                    $stmt = $conn->prepare("INSERT INTO resturant (Resturant_id, name, city) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $restaurant_id, $restaurant_name, $restaurant_city);

                    // Execute the statement
                    if ($stmt->execute()) {
                        echo "<p>Restaurant added successfully!</p>";
                    } else {
                        echo "Error adding restaurant: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "<p>Please enter both restaurant name and city.</p>";
                }
            }

            // Check if the delete button is clicked
            if(isset($_POST['delete'])){
                $restaurant_id = $_POST['delete'];
                $sql = "DELETE FROM resturant WHERE Resturant_id='$restaurant_id'";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Restaurant deleted successfully!</p>";
                } else {
                    echo "Error deleting record: " . $conn->error;
                }
            }

            // Check if the add rating form is submitted
            if(isset($_POST['add_rating'])){
                $restaurant_id = $_POST['restaurant_id'];
                $rating_option = $_POST['rating_option'];
                if(!empty($restaurant_id) && !empty($rating_option)){ // Check if both restaurant ID and rating option are provided
                    // Update the restaurant table to add the rating
                    $sql = "UPDATE resturant SET rating='$rating_option' WHERE Resturant_id='$restaurant_id'";
                    if ($conn->query($sql) === TRUE) {
                        echo "<p>Rating added successfully!</p>";
                    } else {
                        echo "Error adding rating: " . $conn->error;
                    }
                } else {
                    echo "<p>Please enter both restaurant ID and rating option.</p>";
                }
            }

            // Check if the delete rating form is submitted
            if(isset($_POST['delete_rating'])){
                $restaurant_id = $_POST['restaurant_id'];
                // Update the restaurant table to remove the rating
                $sql = "UPDATE resturant SET rating=NULL WHERE Resturant_id='$restaurant_id'";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Rating deleted successfully!</p>";
                } else {
                    echo "Error deleting rating: " . $conn->error;
                }
            }

            // Fetch and display restaurant list
            $sql = "SELECT Resturant_id, name, city, rating FROM resturant";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li>" . $row["name"] . " - " . $row["city"] . " - Rating: ";
                    if (!empty($row["rating"])) {
                        echo $row["rating"];
                    } else {
                        echo "Not available";
                    }
                    // Add form to add rating option
                    echo "<form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' style='display:inline;'>";
                    echo "<input type='hidden' name='restaurant_id' value='".$row["Resturant_id"]."'>";
                    echo "<input type='text' name='rating_option' placeholder='Rating'>";
                    echo "<input type='submit' name='add_rating' value='Add Rating'>";
                    echo "</form>";
                    echo "<form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' style='display:inline;'>";
                    echo "<input type='hidden' name='restaurant_id' value='".$row["Resturant_id"]."'>";
                    echo "<input type='submit' name='delete_rating' value='Delete Rating'>";
                    echo "</form>";
                    echo "<form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' style='display:inline;'>";
                    echo "<input type='hidden' name='delete' value='".$row["Resturant_id"]."'>";
                    echo "<input type='submit' name='delete_restaurant' value='Delete'>";
                    echo "</form>";
                    echo "</li>";
                }
            } else {
                echo "<li>No restaurants found</li>";
            }
            $conn->close();
            ?>
        </ul>
    </div>
    <footer>
        <p>&copy; 2024 Md. Irfan Hossain. All rights reserved. | Privacy</p>
    </footer>
</body>
</html>









