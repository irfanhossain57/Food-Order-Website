<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "food_order");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user's information from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM customer WHERE Username = '$username'";
$result = $conn->query($sql);

// Fetch user data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customer_id = $row['Customer_id'];
    $phone = $row['Phone']; // Display the phone number exactly as entered by the user
    $name = $row['Name'];
    $location = "";

    // Get location from customer_location table
    $location_sql = "SELECT Location FROM customer_location WHERE Customer_id = '$customer_id'";
    $location_result = $conn->query($location_sql);
    if ($location_result->num_rows > 0) {
        $location_row = $location_result->fetch_assoc();
        $location = $location_row['Location'];
    }
} else {
    echo "User not found.";
    exit();
}

// Handle form submission for updating user information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve new form data
    $new_name = $_POST['name'];
    $new_phone = $_POST['phone'];
    $new_location = $_POST['location'];
    $new_username = $_POST['username'];

    // Update user information in the database
    $update_sql = "UPDATE customer SET Name='$new_name', Phone='$new_phone', Username='$new_username' WHERE Username='$username'";
    if ($conn->query($update_sql) === TRUE) {
        $name = $new_name; // Update displayed name
        $phone = $new_phone; // Update displayed phone number
        $_SESSION['username'] = $new_username; // Update session username
        $username = $new_username; // Update username variable
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Update user location if provided
    if (!empty($new_location)) {
        $location_update_sql = "UPDATE customer_location SET Location='$new_location' WHERE Customer_id='$customer_id'";
        if ($conn->query($location_update_sql) !== TRUE) {
            echo "Error updating location: " . $conn->error;
        } else {
            $location = $new_location; // Update displayed location
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            justify-content: center;
            background-color: indigo; 
        }

        .container {
            width: 80%;
            margin: 50px auto;
            justify-content: center;
            background-color: khaki;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-info {
            margin-bottom: 20px;
        }

        .user-info label {
            font-weight: bold;
        }

        .user-info p {
            margin: 5px 0;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #007bff;
            /* Blue color for the submit button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            /* Make the button text bold */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validatePhone()">
            <div class="user-info">
                <label for="customer_id">Customer ID:</label>
                <p><?php echo $customer_id; ?></p>
            </div>
            <div class="user-info">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div class="user-info">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>
            <div class="user-info">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
            </div>
            <div class="user-info">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo $location; ?>">
            </div>
            <button type="submit">Save Changes</button>
        </form>
    </div>
    <script>
        function validatePhone() {
            var phoneInput = document.getElementById("phone").value;
            if (phoneInput.length !== 11) {
                alert("Phone number must be 11 digits long.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
