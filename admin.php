<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        .button-container {
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin!</h2>
        <div class="button-container">
            <button onclick="window.location.href = 'add_resturant.php';">Manage Restaurant</button>
            <button onclick="window.location.href = 'food_item.php';">Manage Food Menu</button>
        </div>
    </div>
    <footer>© 2024 Md. Irfan Hossain. All rights reserved. | Privacy</footer>
</body>
</html>



