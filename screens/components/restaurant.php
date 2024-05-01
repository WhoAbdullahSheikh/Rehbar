<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
  header("Location: loginscreen.php");
  exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rehbar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
  $email = $_SESSION['email'];
  $sql = "SELECT fullname, email FROM service_provider WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullname = $row['fullname'];
    $email = $row['email'];
  }

  $filename = $_FILES["uploadfile"]["name"];
  $tempname = $_FILES["uploadfile"]["tmp_name"];
  $restaurant = $_POST['restaurant'];
  $location = $_POST['location'];

  $folder = "./image/" . $filename;
  $email = $conn->real_escape_string($_SESSION['email']);

  // Move uploaded file
  if (move_uploaded_file($tempname, $folder)) {
    $stmt = $conn->prepare("INSERT INTO restaurant (filename, email, restaurant, location) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
      die('MySQL prepare error: ' . $conn->error);
    }


    $stmt->bind_param("ssssd", $filename, $email, $restaurant, $location);

    if ($stmt->execute()) {
      $_SESSION['message'] = "Updated successfully";
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } else {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
  } else {
    echo "Failed to upload file.";
  }
}
$conn->close();
