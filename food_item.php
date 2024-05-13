<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Food Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex-grow: 1; /* Grow to fill remaining space */
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            text-decoration: none;
            color: #333;
            transition: color 0.3s;
        }
        a:hover {
            color: #4caf50;
        }
        footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            margin-top: auto; /* Push to the bottom */
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Food Menu</h2>
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

        // Fetch and display restaurant list
        $sql = "SELECT Resturant_id, name FROM resturant";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<li><a href='food_menu.php?restaurant_id=" . $row["Resturant_id"] . "'>" . $row["name"] . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "No restaurants found";
        }
        $conn->close();
        ?>
    </div>
    <footer>© 2024 Md. Irfan Hossain. All rights reserved. | Privacy</footer>
</body>
</html>












