<?php
$servername = "localhost"; // your server name
$username = "root";    // your database username
$password = "";    // your database password
$database = "rehbar"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $category = $_POST['category'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $features = $_POST['features'];

    // Prepare SQL based on category
    if ($category == "hotel") {
        $sql = "INSERT INTO services_hotel (name, location, description, features) VALUES (?, ?, ?, ?)";
    } else if ($category == "restaurant") {
        $sql = "INSERT INTO services_restaurant (name, location, description, features) VALUES (?, ?, ?, ?)";
    }

    // Prepare and execute SQL statement
    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $location, $description, $features);
        if ($stmt->execute()) {
            echo "Record added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Provider Form</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 16px;
      background-color: #f4f4f4;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group select,
    .form-group input[type="text"],
    .form-group textarea {
      width: 104%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .form-group textarea {
      height: 50px;
    }

    .form-group input[type="file"] {
      padding: 0;
    }

    .form-group input[type="submit"] {
      background-color: #003366;
      color: #fff;
      padding: 12px 0;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      width: 40%;
      transition: background-color 0.3s ease;
    }

    .form-group input[type="submit"]:hover {
      background-color: #002555;
    }

    .form-container {
      background-color: rgba(255, 255, 255, 0.6);
      border: 1px solid #ccc;
      border-radius: 8px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      width: 500px;
      padding: 25px;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .form-headings {
      text-align: center;
      font-size: 30px;
      font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
      margin-bottom: 20px;
      font-weight: bold;
    }

    .form-heading {
      font-size: 24px;
      font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
      margin-bottom: 20px;
    }

    .form-section {
      margin-bottom: 20px;
    }

    .form-label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
      color: #333;
    }

    .form-input,
    .form-select {
      width: 99%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }

    .form-select {
      background-color: #f9f9f9;
    }

    .form-buttons {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .form-button {
      background-color: #003366;
      color: white;
      border: none;
      padding: 12px 0;
      border-radius: 4px;
      cursor: pointer;
      width: 40%;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }
  </style>
</head>

<body>
  <div class="form-container">
    <h1 class="form-headings">Service Provider Form</h1>

    <form action="./sp_register.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="category">Category:</label>
        <select id="category" name="category" required>
          <option value="hotel">Hotel</option>
          <option value="restaurant">Restaurant</option>
        </select>
      </div>
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
      </div>
      <div class="form-group">
        <label for="features">Features:</label>
        <textarea id="features" name="features" required></textarea>
      </div>
    
      <div class="form-group">
        <div class="form-buttons">
          <input type="submit" value="Submit">
        </div>
      </div>
    </form>
  </div>

</body>

</html>