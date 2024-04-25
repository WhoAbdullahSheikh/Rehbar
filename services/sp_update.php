<?php
// Start or resume a session
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";  // Update your password
$database = "rehbar";  // Update your database name

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted using a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    // Retrieve the service provider's ID from the URL
    $id = intval($_GET['id']);

    // Gather all form data
    $category = $conn->real_escape_string($_POST['category']);
    $name = $conn->real_escape_string($_POST['name']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);
    $features = $conn->real_escape_string($_POST['features']);

    // Determine the table to update based on the category
    $table = ($category == "hotel") ? "services_hotel" : "services_restaurant";

    // Prepare an UPDATE SQL query
    $sql = "UPDATE $table SET 
                name = ?, 
                location = ?, 
                description = ?, 
                features = ? 
            WHERE id = ?";

    // Prepare and bind parameters to the SQL query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssi", $name, $location, $description, $features, $id);
        if ($stmt->execute()) {
            echo "Record updated successfully.";
            // Optionally, redirect or perform other actions upon successful update
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Service Provider Details</title>

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

        <form action="./sp_update.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="hotel" <?php echo ($providerData['category'] == 'hotel') ? 'selected' : ''; ?>>Hotel</option>
                <option value="restaurant" <?php echo ($providerData['category'] == 'restaurant') ? 'selected' : ''; ?>>Restaurant</option>
            </select>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($providerData['name']); ?>" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($providerData['location']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($providerData['description']); ?></textarea>

            <label for="features">Features:</label>
            <textarea id="features" name="features" required><?php echo htmlspecialchars($providerData['features']); ?></textarea>

            <button type="submit">Update Details</button>
            </div>
        </form>
    </div>

    <div class="dashboard">
        <h2>Update Service Provider Details</h2>
        <form method="POST" action="update.php?id=<?php echo $providerId; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required><br><br>

            <input type="submit" name="update" value="Update">
        </form>
    </div>
</body>

</html>