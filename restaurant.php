
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Search</title>
    <style>
        body {
            background-image: url('image_restraunt/bg.png');
            background-size: contain;
            background-position: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding-left: 0px;
        }

        .search-container {
            float: right;
            margin-bottom: 20px;
        }

        .restaurant-list {
            list-style-type: none;
            padding: 0;
        }

        .restaurant-item {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .restaurant-img {
            width: 280px;
            height: 280px;
            margin-right: 20px;
        }

        .restaurant-details {
            text-align: left;
        }

        .restaurant-link {
            text-decoration: none;
            color: #333;
        }

        .restaurant-link:hover {
            color: #007bff;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="margin-top: 80px;">Select a Restaurant</h1>
        <div class="search-container">
            <form method="get">
                <input type="text" name="search" placeholder="Search Restaurants..." style="padding: 10px 20px;">
                <input type="submit" value="Search" style="padding: 10px 20px;">
            </form>
        </div>
        <div class="restaurant-list">
            <?php
			include 'navbar.php';
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "food_order";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
			
            // Fetching restaurants from database based on search query
            if(isset($_GET['search'])) {
                $search = $_GET['search'];
                $sql = "SELECT resturant_id, name, city, Rating FROM resturant WHERE name LIKE '%$search%' OR city LIKE '%$search%'";
            } else {
                $sql = "SELECT resturant_id, name, city, Rating FROM resturant";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<ul>";
                while($row = $result->fetch_assoc()) {
                    echo "<li class='restaurant-item'>";
                    echo "<div class='restaurant-img'><img width='275' height='200' src='image_restraunt/{$row['resturant_id']}.png' alt='{$row['name']}'></div>";
                    echo "<div class='restaurant-details'>";
                    echo "<a href='menu.php?restaurant_name={$row['name']}' class='restaurant-link'>{$row['name']}</a>";
                    echo "<p>City: {$row['city']}</p>";
					echo "<p>Rating: {$row['Rating']}</p>";
                    echo "</div>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "No restaurants found.";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
