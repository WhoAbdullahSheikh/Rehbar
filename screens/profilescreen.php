<?php
session_start();
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

// Attempt to retrieve user information
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['uploadHotel'])) {
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $folder = "./image/" . $filename;
    $email = $conn->real_escape_string($_SESSION['email']);

    // Move uploaded file
    if (move_uploaded_file($tempname, $folder)) {
      $stmt = $conn->prepare("INSERT INTO hotel (filename, email, name, location, price) VALUES (?, ?, ?, ?, ?)");
      if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
      }


      $stmt->bind_param("ssssd", $filename, $email, $name, $location, $price);

      if ($stmt->execute()) {
        $_SESSION['message'] = "Updated successfully";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
      } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
    }
  } elseif (isset($_POST['uploadRestaurant'])) {
    // Extracting and sanitizing inputs
    $email = $_SESSION['email'] ?? '';
    $restaurant = $_POST['restaurant'] ?? '';
    $location = $_POST['location'] ?? '';
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "./image/" . $filename;

    if (move_uploaded_file($tempname, $folder)) {
      // Prepare the SQL statement
      $stmt = $conn->prepare("INSERT INTO restaurant (email, restaurant, location, filename) VALUES (?, ?, ?, ?)");
      if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
      }

      // Binding the parameters and executing the statement
      $stmt->bind_param("ssss", $email, $restaurant, $location, $filename);
      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      } else {
        $_SESSION['message'] = "Restaurant added successfully";
        header("Location: " . $_SERVER['PHP_SELF'] . "?section=addedItems");
        exit();
      }
    } else {
      echo "Failed to upload file.";
    }
  } elseif (isset($_POST['uploadTransport'])) {
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $transport = $_POST['transport'];
    $location = $_POST['location'];
    $service_type = $_POST['service_type'];  // Assuming you have a field to specify the type of transport service
    $folder = "./image/" . $filename;
    $email = $conn->real_escape_string($_SESSION['email']);

    // Move uploaded file
    if (move_uploaded_file($tempname, $folder)) {
      $stmt = $conn->prepare("INSERT INTO transport (filename, email, transport, location, service_type) VALUES (?, ?, ?, ?, ?)");
      if (!$stmt) {
        die('MySQL prepare error: ' . $conn->error);
      }
      $stmt->bind_param("sssss", $filename, $email, $transport, $location, $service_type);
      if ($stmt->execute()) {
        $_SESSION['message'] = "Transport service added successfully";
        header("Location: " . $_SERVER['PHP_SELF'] . "?section=transportServices"); // Redirect to a specific section if needed
        exit();
      } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
    } else {
      echo "Failed to upload file.";
    }
  } elseif (isset($_POST['uploadGuide'])) {
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $fullname = $_POST['fullname'];
    $location = $_POST['location'];
    $guide_mail = $_POST['guide_mail'];
    $guide_area = $_POST['guide_area'];  // Assuming you have a field to specify the type of transport service
    $folder = "./image/" . $filename;
    $email = $conn->real_escape_string($_SESSION['email']);

    // Move uploaded file
    if (move_uploaded_file($tempname, $folder)) {
      $stmt = $conn->prepare("INSERT INTO tourguide (filename, email, guide_mail, fullname, location, guide_area) VALUES (?, ?, ?, ?, ?, ?)");
      if (!$stmt) {
        die('MySQL prepare error: ' . $conn->error);
      }
      $stmt->bind_param("ssssss", $filename, $email, $guide_mail, $fullname, $location, $guide_area);
      if ($stmt->execute()) {
        $_SESSION['message'] = "Tour Guide added successfully";
        header("Location: " . $_SERVER['PHP_SELF'] . "?section=tourguide"); // Redirect to a specific section if needed
        exit();
      } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
    } else {
      echo "Failed to upload file.";
    }
  }
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && isset($_GET['type'])) {
  $id = $_GET['id'];
  $type = $_GET['type'];

  // Determine which table to delete from
  $table = '';
  switch ($type) {
    case 'hotel':
      $table = 'hotel';
      break;
    case 'restaurant':
      $table = 'restaurant';
      break;
    case 'transport':
      $table = 'transport';
      break;
    case 'tourguide':
      $table = 'tourguide';
      break;
    default:
      echo 'Invalid service type.';
      exit;
  }

  // SQL to delete the record
  $query = "DELETE FROM $table WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    header("Location: profilescreen.php"); // Redirect after deletion
  } else {
    echo "Error deleting record: " . $conn->error;
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rehbar</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./ecommerce.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" />
  <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Raleway", sans-serif;
      box-sizing: border-box;
    }

    .btn-delete {
      padding: 8px 16px;
      background-color: #ff4d4d;
      /* Red color for delete button to indicate caution */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease-in-out, transform 0.2s ease;
    }

    .btn-delete:hover {
      background-color: #ff6666;
      /* Lighter red when hovered */
      transform: translateY(-2px);
      /* Slight lift effect */
    }

    .btn-delete:active {
      transform: translateY(1px);
      /* Pushed effect when clicked */
      background-color: #cc0000;
      /* Darker red to simulate being pressed */
    }

    .category-heading {
      text-align: center;
      font-size: 10px;
      padding: 1%;
    }

    .refresh-button {
      margin-left: auto;
      /* Pushes the button to the right */
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 14px;
      font-size: 16px;
      border-radius: 15px;
      box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.6);
      transition: all 0.5s ease;
      cursor: pointer;
    }

    .refresh-button:hover {
      background-color: #71CD75;
      color: black;
      box-shadow: 7px 7px 15px rgba(0, 0, 0, 0.6);
    }

    .refresh-button i.fa-refresh {
      animation: spin 5s infinite linear;
    }

    @keyframes spin {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    header {
      position: fixed;
      z-index: 1000;
      display: flex;
      justify-content: space-evenly;
      align-items: center;
      height: 60px;
      width: 100%;
      background: #0E172C;

    }



    .heading {
      display: flex;
      justify-content: flex-end;
      /* Align items to the right */
      align-items: center;
      height: 60px;
      width: 100%;
      background: #0E172C;
      padding-right: 3%;
    }

    .heading1 {
      opacity: 1;
      bottom: 8px;
    }

    .heading ul {
      display: flex;
    }

    .logo {
      margin: 5%;
    }

    .logo a {
      color: white;
      transition-duration: 1s;
      font-weight: 800;
    }

    .logo a:hover {
      color: #B8C1EC;
      transition-duration: 1s;
    }

    .heading ul li {
      list-style-type: none;
    }

    .heading ul li a {
      margin: 5px;
      text-decoration: none;
      color: black;
      font-weight: 500;
      position: relative;
      color: white;
      margin: 2px 14px;
      font-size: 10px;
      transition-duration: 1s;
    }

    .heading ul li a:active {
      color: red;
    }

    .heading ul li a:hover {
      color: #B8C1EC;
      transition-duration: 1s;
    }

    .heading ul li a::before {
      content: "";
      height: 2px;
      width: 0px;
      position: absolute;
      left: 0;
      bottom: 0;
      background-color: white;
      transition-duration: 1s;
    }

    .heading ul li a:hover::before {
      width: 100%;
      transition-duration: 1s;
      background-color: rgb(243, 168, 7);
    }

    #input {
      height: 30px;
      width: 300px;
      text-decoration: none;
      border: 0px;
      padding: 5px;
    }

    .logo a {
      color: white;
      font-size: 35px;
      font-weight: 500;
      text-decoration: none;
    }

    ion-icon {
      width: 30px;
      height: 30px;
      background-color: white;
      color: black;
    }

    ion-icon:hover {
      cursor: pointer;
    }

    .search a {
      display: flex;
    }

    header a ion-icon {
      position: relative;
      right: 3px;
    }

    .heading1 {
      opacity: 0;
    }

    .search {
      display: flex;
      position: relative;
    }

    .section1 {
      width: 100%;
      overflow: hidden;
      justify-content: center;
      align-items: center;
      margin: 0px auto;
    }

    footer {
      margin-top: 3%;
      padding-top: 3%;
      display: flex;
      flex-direction: column;
      background-color: black;
      align-items: center;
      color: white;
    }

    .footer1 {
      display: flex;
      flex-direction: column;
      align-items: center;
      color: white;
      margin-top: 15px;
    }

    .social-media {
      display: flex;
      justify-content: center;
      color: white;
      flex-wrap: wrap;
    }

    .social-media a {
      color: white;
      margin: 20px;
      border-radius: 5px;
      margin-top: 10px;
      color: white;
    }

    .social-media a ion-icon {
      color: white;
      background-color: black;
    }

    .social-media a:hover ion-icon {
      color: rgb(243, 168, 7);
      transform: translateY(5px);
    }

    .footer2 {
      display: flex;
      width: 100%;
      justify-content: space-evenly;
      align-items: center;
      text-decoration: none;
      flex-wrap: wrap;
    }

    .footer0 {
      font-weight: 1200;
      transition-duration: 1s;
    }

    .footer0:hover {
      color: rgb(243, 168, 7);
    }

    .footer2 .heading-start {
      font-weight: 900;
      font-size: 18px;
    }

    .footer3 {
      margin-top: 60px;
      margin-bottom: 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    .footer2 .heading:hover {
      color: rgb(243, 168, 7);
    }

    .footer2 .div:hover {
      transform: scale(1.05);
    }

    .footer3 h4 {
      margin: 0 10px;
    }

    .footer2 div {
      margin: 10px;
    }

    .menu {
      visibility: hidden;
    }

    .heading1 .ham:active {
      color: red;
    }

    .items {
      overflow: hidden;
    }

    .ham,
    .close {
      cursor: pointer;
    }

    @media screen and (max-width: 1250px) {
      .heading ul li {
        display: none;
      }

      .items {
        transform: scale(0.9);
      }

      .img-slider img {
        height: 60vw;
        width: 80vw;
      }

      .ham:active {
        color: red;
      }

      .menu {
        display: block;
        flex-direction: column;
        align-items: center;
      }

      .menu a ion-icon {
        position: absolute;
      }

      @keyframes slide1 {
        0% {
          left: 0vw;
        }

        15% {
          left: 0vw;
        }

        20% {
          left: -80vw;
        }

        32% {
          left: -80vw;
        }

        35% {
          left: -160vw;
        }

        47% {
          left: -160vw;
        }

        50% {
          left: -240vw;
        }

        63% {
          left: -240vw;
        }

        66% {
          left: -320vw;
        }

        79% {
          left: -320vw;
        }

        82% {
          left: -400vw;
        }

        100% {
          left: 0vw;
        }
      }

      .menu ul {
        display: flex;
        flex-direction: column;
        position: absolute;
        width: 100vw;
        height: 70vh;
        background-color: rgb(0, 0, 0, 0.8);
        left: 0;
        top: 0;
        z-index: 11;
        align-items: center;
        justify-content: center;
      }

      .close {
        z-index: 34;

        color: white;
        background-color: black;
      }

      .close:active {
        color: red;
      }

      .menu ul li {
        list-style: none;
        margin: 20px;
        border-top: 3px solid white;
        width: 80%;
        text-align: center;

        padding-top: 10px;
      }

      .menu ul li a {
        text-decoration: none;
        padding-top: 10px;
        color: white;
        font-weight: 90;
      }

      .menu ul li a:hover {
        color: rgb(240, 197, 6);
      }

      .img-slider {
        display: flex;
        float: left;
        position: relative;
        width: 100%;
        height: 100%;
        animation-name: slide1;
        animation-duration: 10s;
        animation-iteration-count: infinite;
        transition-duration: 2s;
      }

      .section1 {
        width: 100%;
        overflow: hidden;
        justify-content: center;
        align-items: center;
        margin: 0px auto;
      }

      .heading1 {
        opacity: 1;
        bottom: 8px;
      }

      .search a {
        display: flex;
        flex-wrap: nowrap;
      }

      .heading1 .ham {
        background-color: black;
        color: white;
      }

      #input {
        width: 200px;
        flex-shrink: 2;
      }

      header {
        height: 150px;
      }
    }

    @media screen and (max-width: 550px) {
      .heading ul li {
        display: none;
      }

      .heading1 {
        opacity: 1;

        bottom: 8px;
      }

      header {
        height: 250px;
        flex-wrap: wrap;
        display: flex;
        flex-direction: column;
      }

      #input {
        width: 150px;
      }

      .close {
        z-index: 34;
      }

      .search a {
        display: flex;
        flex-wrap: nowrap;
      }
    }

    .section-break {
      padding-top: 2%;
      text-align: center;
      margin: 5px 0;
      padding-bottom: 1%;
    }

    .section-break hr {
      width: 85%;
      /* Adjust the width as needed */
      border: none;
      height: 1px;
      margin: 0 auto;
      /* Center the line horizontally */
      background-color: #333;
    }

    .section-break-2 {
      padding-top: 2%;
      text-align: center;
      margin: 5px 0;
      padding-bottom: 1%;
    }

    .section-break-2 hr {
      width: 95%;
      /* Adjust the width as needed */
      border: none;
      height: 1px;
      margin: 0 auto;
      /* Center the line horizontally */
      background-color: #333;
    }

    .section2 {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      max-width: 1200px;
      padding: 20px;
    }

    .category-heading {
      text-align: center;
      font-size: 30px;
      padding: 1%;
    }

    .sidenav {
      height: 100%;
      width: 250px;
      position: fixed;
      z-index: 1;
      top: 60px;
      left: 0;
      background-color: #384669;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 60px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .sidenav a {
      padding: 8px 16px;
      text-decoration: none;
      font-size: 17px;
      color: #818181;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      transition: color 0.3s, background-color 0.3s;
      margin-left: 5%;
      margin-bottom: 10px;
      /* Added to create a gap between buttons */
      border-radius: 5px;
      width: 90%;
    }

    .sidenav a i {
      font-size: 20px;
      color: white;
      margin-right: 10px;
    }

    .sidenav a span {
      color: white;
    }

    .sidenav a:hover {
      color: #f1f1f1;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .sidenav hr {
      width: 90%;
      border: none;
      height: 1px;
      margin: 0 auto;
      background-color: #818181;
      margin-top: 20px;
      margin-bottom: 20px;
    }



    hr {
      width: 90%;
      border: none;
      height: 1px;
      margin: 0 auto;
      /* Center the line horizontally */
      background-color: #818181;
      margin-top: 50px;
      margin-bottom: 10px;
    }

    .profile-container {
      border: 0px solid #ccc;
      border-radius: 40px;
      padding: 20px;
      margin-left: 10%;
      margin-right: 10%;
      margin-top: 5%;
      padding-top: 2%;
      padding-bottom: 3%;
      box-shadow: 0 0 70px rgba(0, 0, 0, 0.7);
      opacity: 0;
      /* Initially hide the container */
      animation: fadeIn 0.5s forwards;

      /* Apply fade-in animation */
    }

    /* Define the fade-in animation */
    @keyframes fadeIn {
      from {
        opacity: 0;
        /* Start with opacity 0 */
      }

      to {
        opacity: 1;
        /* End with opacity 1 */
      }
    }




    .profile-container h2 {
      margin-bottom: 10px;
      font-size: 40px;
    }

    .profile-container div {
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      font-size: 20px;
    }

    input[type="text"],
    input[type="email"] {
      width: 30%;
      padding: 10px;
      border: 1px solid black;
      border-radius: 10px;
      font-size: 15px;
    }

    button[type="submit"] {
      width: 30%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background-color: rgb(240, 197, 6);
      color: rgb(0, 0, 0);
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: rgb(243, 168, 7);
    }

    .section-break {
      padding-top: 3px;
      text-align: center;
      margin: 5px 0;
      padding-bottom: 1%;
    }

    .section-break hr {
      width: 100%;
      /* Adjust the width as needed */
      border: none;
      height: 1px;
      margin: 0 auto;
      /* Center the line horizontally */
      background-color: #333;
    }



    .price-wrapper {
      width: 50%;
      align-items: center;
      border: 1px solid black;
      border-radius: 10px;
      padding: 2px;
    }

    .currency-prefix {
      background-color: #f0f0f0;
      padding: 10px;
      border-right: 1px solid black;
      border-top-left-radius: 8px;
      border-bottom-left-radius: 8px;
      font-size: 15px;
      color: #333;

    }

    input[type="number"] {
      flex-grow: 1;
      border: none;
      width: 90%;
      /* Removes focus outline */
      border-radius: 8px;
      padding: 10px;
      font-size: 15px;
      border: none;
      /* Removes border inside the input */
      outline: none;
    }


    #display-image {
      justify-content: center;
      /* Center content horizontally */
      padding: 5px;
      margin: 15px auto;
      /* Centers the div horizontally */

    }

    img {
      margin: 5px;
      width: 330px;

      height: 450px;
    }

    .product-item {

      border: 1px solid #ccc;
      border-radius: 5px;
      margin: 10px;
      padding: 10px;
      width: calc(33.333% - 20px);
      /* Three items per row, with margin */
      display: inline-block;
      vertical-align: top;
    }

    .product-item img {
      width: 330px;
      height: 50px;
      object-fit: cover;

    }

    @media (max-width: 1200px) {
      .product-item {
        width: 100%;
        /* Full width on smaller screens */
      }

      .product-item img {
        width: 100%;
        /* Image takes full width of its container */
        height: auto;
        /* Maintain aspect ratio */
      }
    }

    .product-gallery {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      align-items: flex-start;
    }

    .product-card {
      flex: 1 1 300px;
      /* Adjust basis to your preference */
      border: 1px solid #ccc;
      margin: 10px;
      display: flex;
      flex-direction: row;
      /* Align children (image and details) in a row */
      align-items: center;
      /* Align items vertically in the center */
      cursor: pointer;
      transition: box-shadow 0.3s ease;
    }

    .product-card:hover {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    }

    .product-image-container {
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-basis: 30%;
      /* Adjust image container basis */
    }

    .product-image {
      width: 400px;
      height: 300px;
      object-fit: cover;
    }

    .product-details {
      padding: 10px;
      flex-grow: 1;
      font-size: 20px;
      /* Allow details to fill the remaining space */
    }

    .product-info p {
      margin: 5px 0;
      line-height: 1.5;
    }


    .maintenance-message {
      text-align: center;
      margin-top: 10%;
      font-size: 24px;
      color: rgb(0, 0, 0);
      font-weight: bold;
      padding-bottom: 13%;
    }

    .form-group select {
      width: 40%;

      padding: 10px;

      border: 1px solid #ccc;

      border-radius: 5px;

      background-color: #fff;
    }

    .form-group select:focus {
      border-color: black;
      /* Adds focus style similar to other input fields */
    }

    #itemsSection {
      display: flex;
      justify-content: space-between;
      padding: 20px;
      background-color: white;
      color: black;
    }

    .profile-container {
      flex: 1;
      padding-right: 20px;
      /* Adjust spacing between form and map */
    }


    .form-container {
      flex: 1;
      margin-right: 20px;

      /* Adjust space between form and map */
    }

    #map {
      height: 500px;
      width: 100%;
      /* Adjust based on your layout needs */
    }
  </style>
</head>

<body>
  <header>
    <div id="mySidenav" class="sidenav">
      <a href="#" id="profileButton" onclick="toggleSections('profile')">
        <i class="fas fa-user"></i>
        <span>Profile</span>
      </a>
      <a href="#" id="itemsButton" onclick="toggleSections('items')">
        <i class="	fas fa-bed"></i>
        <span>Hotel</span>
      </a>
      <a href="#" id="addedProductsButton" onclick="toggleSections('addedItems')">
        <i class="material-icons">restaurant</i>
        <span>Restaurant</span>
      </a>
      <a href="#" id="transportButton" onclick="toggleSections('transportServices')">
        <i class="fa fa-bus"></i>
        <span>Transport</span>
      </a>
      <a href="#" id="tourguideButton" onclick="toggleSections('tourguide')">
        <i class="fas fa-map-marked-alt"></i>
        <span>Tour Guide</span>
      </a>
      <hr>
      <a href="./logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span>Signout</span>
      </a>
    </div>

    <div class="logo">
      <a href="../home.php">Rehbar</a>
    </div>

    <div class="heading">
      <ul>
        <li><a href="../home.php" class="under">HOME</a></li>
        <li><a href="../display.php" class="under">SERVICES</a></li>

      </ul>
    </div>

    <div class="heading1">
      <ion-icon name="menu" class="ham"></ion-icon>
      <div class="menu">
        <a href="#">
          <ion-icon name="close" class="close"></ion-icon>
        </a>

        <ul>
          <li><a href="#" class="under">HOME</a></li>
          <li><a href="#" class="under">SHOP</a></li>
          <li><a href="#" class="under">OUR PRODUCTS</a></li>
          <li><a href="#" class="under">LOGIN/REGISTER</a></li>
          <li><a href="#" class="under">ABOUT US</a></li>
        </ul>
      </div>
    </div>
  </header>

  <div id="contentContainer">
    <div id="profileSection" style="background-color: white; color: black; padding: 20px; padding-left: 15%; ">
      <div class="profile-container">
        <h2>Personal Information
          <button onclick="location.reload();" style="margin-left: 700px; cursor: pointer;" class="refresh-button">
            <i class="fa fa-refresh fa-spin"></i>
          </button>
        </h2>
        <div class="section-break">
          <hr />
        </div>
        <div>
          <?php include './components/updateprofile.php'; ?>
          <form action="profilescreen.php" method="post">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>">
            <br>
            <br>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            <br>
            <br>
            <br>
            <button type="submit">Update</button>
          </form>
        </div>
      </div>
    </div>
    <div id="itemsSection" style="background-color: white; color: black; padding: 20px; padding-left: 15%;">
      <div class="profile-container">
        <h2>Hotel Details
          <button onclick="location.reload();" style="margin-left: 850px; cursor: pointer;" class="refresh-button">
            <i class="fa fa-refresh fa-spin"></i>
          </button>
        </h2>
        <div class="section-break">
          <hr />
        </div>

        <form action="./profilescreen.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <div class="input-container" ;>
              <label for="category">Category</label>
              <input type="text" id="category" name="category" value="Hotel">
            </div>
          </div>
          <div class="input-container" ;>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
          </div>
          <div class="input-container">
            <label for="name">Hotel Name:</label>
            <input type="text" id="name" name="name" required>
          </div>

          <div class="input-container">
            <label for="location">Location:</label>
            <textarea id="location" name="location" required style="border-radius: 10px; height: 100px; width: 40%; font-size: 15px; padding: 10px"></textarea>
          </div>

          <div id="map" style="flex: 1; height: 500px;">
            <!-- Map will be loaded here -->
          </div>
          <div id="form-container" style="height: fit-content; ">
            <div class="input-container" style="width: 70%;">
              <input type="text" id="start" name="start" placeholder="Start Location">
            </div>
            <div class="input-container" style="width: 70%;">
              <input type="text" id="end" name="end" placeholder="Enter Destination">
            </div>
            <div class="input-container">
              <button onClick="getDirections()" class="btn btn-primary" type="submit" style="width: 20%;">Search
              </button>
            </div>
          </div>
          <div class="input-container">
            <label for="price">Price: (Per Room)</label>
            <div class="price-wrapper">
              <span class="currency-prefix">Rs.</span>
              <input type="number" step="10" id="price" name="price" required style="border-radius: 10px; padding: 10px; font-size: 15px; ">
            </div>
          </div>
          <div class="input-container image-upload">
            <input type="file" name="uploadfile" value="" />
            <br>
            <br>
            <button class="btn btn-primary" type="submit" name="uploadHotel">Submit</button>
          </div>
          <br>
          <div class="section-break">
            <hr />
          </div>
          <div class="category-heading">
            <h2>Your Added Hotels</h2>
          </div>
          <div id="display-image" class="product-gallery">
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
            $result = $conn->query("SELECT * FROM hotel WHERE email = '" . $conn->real_escape_string($_SESSION['email']) . "'");
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<div class="product-image-container" onclick="toggleDetails(this.parentElement)">';
                echo '<img class="product-image" src="./image/' . htmlspecialchars($row['filename']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '</div>';
                echo '<div class="product-details">';
                echo '<div class="product-info">';
                echo '<p><strong>Name: </strong> ' . htmlspecialchars($row['name']) . '</p>';
                echo '<p><strong>Location: </strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '<p><strong>Price: </strong> Rs. ' . htmlspecialchars($row['price']) . ' (Per Room)</p>';
                echo '<button onclick="deleteHotel(' . $row['id'] . ')" class="btn-delete">Delete</button>';
                echo '</div>';
                echo '</div>';  // Close product-details
                echo '</div>';  // Close product-card
              }
            } else {
              echo "<p>No Hotel found.</p>";
            }
            $conn->close();
            ?>
          </div>


        </form>
      </div>

    </div>

    <div id="itemsAdded" style="background-color: white; color: black; padding: 20px; padding-left: 15%;">
      <div class="profile-container">
        <h2>Restaurant Details
          <button onclick="location.reload();" style="margin-left: 750px; cursor: pointer;" class="refresh-button">
            <i class="fa fa-refresh fa-spin"></i>
          </button>
        </h2>
        <div class="section-break">
          <hr />
        </div>

        <form action="./profilescreen.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <div class="input-container" ;>
              <label for="category">Category</label>
              <input type="text" id="category" name="category" value="Restaurant">
            </div>
          </div>
          <div class="input-container" ;>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
          </div>
          <div class="input-container">
            <label for="restaurant">Restaurant Name:</label>
            <input type="text" id="restaurant" name="restaurant" required>
          </div>

          <div class="input-container">
            <label for="location">Location:</label>
            <textarea id="location" name="location" required style="border-radius: 10px; height: 100px; width: 40%; font-size: 15px; padding: 10px"></textarea>
          </div>



          <div class="input-container image-upload">
            <input type="file" name="uploadfile" value="" />
            <br>
            <br>
            <button class="btn btn-primary" type="submit" name="uploadRestaurant">Submit</button>
          </div>
          <br>
          <div class="section-break">
            <hr />
          </div>
          <div class="category-heading">
            <h2>Your Added Restaurants</h2>
          </div>
          <div id="display-image" class="product-gallery">
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
            $email = $conn->real_escape_string($_SESSION['email']);
            $query = "SELECT * FROM restaurant WHERE email = '$email'";
            $result = $conn->query($query);

            if (!$result) {
              echo "Error: " . $conn->error;
            } else {
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo '<div class="product-card">';
                  echo '<div class="product-image-container" onclick="toggleDetails(this.parentElement)">';
                  echo '<img class="product-image" src="./image/' . htmlspecialchars($row['filename']) . '" alt="' . htmlspecialchars($row['restaurant']) . '">';
                  echo '</div>';
                  echo '<div class="product-details">';
                  echo '<div class="product-info">';
                  echo '<p><strong>Restaurant Name: </strong> ' . htmlspecialchars($row['restaurant']) . '</p>';
                  echo '<p><strong>Location: </strong> ' . htmlspecialchars($row['location']) . '</p>';
                  echo '<button onclick="deleterestaurant(' . $row['id'] . ')" class="btn-delete">Delete</button>';
                  echo '</div>';
                  echo '</div>';  // Close product-details
                  echo '</div>';  // Close product-card
                }
              } else {
                echo "<p>No Restaurant found.</p>";
              }
            }
            $conn->close();
            ?>
          </div>


        </form>
      </div>
    </div>

    <div id="transportServices" style="background-color: white; color: black; padding: 20px; padding-left: 15%; display: none;">
      <div class="profile-container">
        <h2>Transport Services
          <button onclick="location.reload();" style="margin-left: 750px; cursor: pointer;" class="refresh-button">
            <i class="fa fa-refresh fa-spin"></i>
          </button>
        </h2>
        <div class="section-break">
          <hr />
        </div>
        <div class="input-container" ;>
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?php echo $email; ?>">
        </div>
        <form action="./profilescreen.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="transport">Transport Service Name:</label>
            <input type="text" id="transport" name="transport" required>
          </div>
          <div class="form-group">
            <label for="service_type">Service Type:</label>
            <input type="text" id="service_type" name="service_type" required>
          </div>
          <div class="form-group">
            <label for="location">Location:</label>
            <textarea id="location" name="location" required style="border-radius: 10px; height: 100px; width: 40%; font-size: 15px; padding: 10px"></textarea>
          </div>
          <div class="input-container image-upload">
            <label for="uploadfile">Upload Image:</label>
            <input type="file" id="uploadfile" name="uploadfile" required>
            <br>
            <br>
            <button type="submit" name="uploadTransport" class="btn btn-primary">Submit</button>
          </div>
          <div class="section-break">
            <hr />
          </div>
          <div class="category-heading">
            <h2>Your Added Transport</h2>
          </div>
          <div id="display-image" class="product-gallery">
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
            $result = $conn->query("SELECT * FROM transport WHERE email = '" . $conn->real_escape_string($_SESSION['email']) . "'");
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<div class="product-image-container" onclick="toggleDetails(this.parentElement)">';
                echo '<img class="product-image" src="./image/' . htmlspecialchars($row['filename']) . '" alt="' . '">';
                echo '</div>';
                echo '<div class="product-details">';
                echo '<div class="product-info">';
                echo '<p><strong>Name: </strong> ' . htmlspecialchars($row['transport']) . '</p>';
                echo '<p><strong>Location: </strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '<p><strong>Service Type: </strong>  ' . htmlspecialchars($row['service_type']) . '</p>';
                echo '<button onclick="deletetransport(' . $row['id'] . ')" class="btn-delete">Delete</button>';
                echo '</div>';
                echo '</div>';  // Close product-details
                echo '</div>';  // Close product-card
              }
            } else {
              echo "<p>No Services found.</p>";
            }
            $conn->close();
            ?>
          </div>
        </form>
      </div>
    </div>

    <div id="tourguide" style="background-color: white; color: black; padding: 20px; padding-left: 15%; display: none;">
      <div class="profile-container">
        <h2>Tour Guide Services
          <button onclick="location.reload();" style="margin-left: 750px; cursor: pointer;" class="refresh-button">
            <i class="fa fa-refresh fa-spin"></i>
          </button>
        </h2>
        <div class="section-break">
          <hr />
        </div>

        <form action="./profilescreen.php" method="POST" enctype="multipart/form-data">
          <div class="input-container" ;>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
          </div>
          <div class="input-container" ;>
            <label for="guide_mail">Guide Email</label>
            <input type="email" id="guide_mail" name="guide_mail" required placeholder="Email">
          </div>
          <div class="form-group">
            <label for="fullname">Guide Name:</label>
            <input type="text" id="fullname" name="fullname" required placeholder="Name">
          </div>
          <div class="form-group">
            <label for="guide_area">Tour Guide (From-To):</label>
            <input type="text" id="guide_area" name="guide_area" required placeholder="From - To">
          </div>
          <div class="form-group">
            <label for="location">Location (Precise):</label>
            <textarea id="location" name="location" required placeholder="City, State" style="border-radius: 10px; height: 100px; width: 40%; font-size: 15px; padding: 10px"></textarea>
          </div>
          <div class="input-container image-upload">
            <label for="uploadfile">Upload Image of Guide:</label>
            <input type="file" id="uploadfile" name="uploadfile" required>
            <br>
            <br>
            <button type="submit" name="uploadGuide" class="btn btn-primary">Submit</button>
          </div>
          <div class="section-break">
            <hr />
          </div>
          <div class="category-heading">
            <h2>Your Added Guides</h2>
          </div>
          <div id="display-image" class="product-gallery">
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
            $result = $conn->query("SELECT * FROM tourguide WHERE email = '" . $conn->real_escape_string($_SESSION['email']) . "'");
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<div class="product-image-container" onclick="toggleDetails(this.parentElement)">';
                echo '<img class="product-image" src="./image/' . htmlspecialchars($row['filename']) . '" alt="' . '">';
                echo '</div>';
                echo '<div class="product-details">';
                echo '<div class="product-info">';
                echo '<p><strong>Name: </strong> ' . htmlspecialchars($row['fullname']) . '</p>';
                echo '<p><strong>Tour Guide Area (From-To): </strong> ' . htmlspecialchars($row['Guide_area']) . '</p>';
                echo '<p><strong>Location: </strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '<p><strong>Email: </strong>  ' . htmlspecialchars($row['guide_mail']) . '</p>';
                echo '<button onclick="deleteguide(' . $row['id'] . ')" class="btn-delete">Delete</button>';
                echo '</div>';
                echo '</div>';  // Close product-details
                echo '</div>';  // Close product-card
              }
            } else {
              echo "<p>No Guides found.</p>";
            }
            $conn->close();
            ?>
          </div>
        </form>
      </div>
    </div>


  </div>

  <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
  <script src="./JS/cartscreen.js"></script>
  <script>
    function toggleDetails(element) {
      var details = element.querySelector(".product-details");
      if (details.style.display === "none" || details.style.display === "") {
        details.style.display = "block";
      } else {
        details.style.display = "none";
      }
    }
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      function toggleSections(section) {
        var profileSection = document.getElementById("profileSection");
        var itemsSection = document.getElementById("itemsSection");
        var itemsAdded = document.getElementById("itemsAdded");
        var transportServices = document.getElementById("transportServices");
        var tourguide = document.getElementById("tourguide");

        if (section === 'profile') {
          profileSection.style.display = "block";
          itemsSection.style.display = "none";
          itemsAdded.style.display = "none";
          transportServices.style.display = "none";
          tourguide.style.display = "none";
        } else if (section === 'items') {
          profileSection.style.display = "none";
          itemsSection.style.display = "block";
          itemsAdded.style.display = "none";
          transportServices.style.display = "none";
          tourguide.style.display = "none";

        } else if (section === 'addedItems') {
          profileSection.style.display = "none";
          itemsSection.style.display = "none";
          itemsAdded.style.display = "block";
          transportServices.style.display = "none";
          tourguide.style.display = "none";

        } else if (section === 'transportServices') {
          profileSection.style.display = "none";
          itemsSection.style.display = "none";
          itemsAdded.style.display = "none";
          transportServices.style.display = "block";
          tourguide.style.display = "none";
        } else if (section === 'tourguide') {
          profileSection.style.display = "none";
          itemsSection.style.display = "none";
          itemsAdded.style.display = "none";
          transportServices.style.display = "none";
          tourguide.style.display = "block";
        }
        localStorage.setItem("lastOpenedSection", section);
      }

      // Function to restore the section visibility from local storage or default to profile
      function restoreSections() {
        var lastOpenedSection = localStorage.getItem("lastOpenedSection");
        if (lastOpenedSection === 'items') {
          toggleSections('items');
        } else if (lastOpenedSection === 'profile') {
          toggleSections('profile');
        } else if (lastOpenedSection === 'addedItems') {
          toggleSections('addedItems');
        } else if (lastOpenedSection === 'transportServices') {
          toggleSections('transportServices');
        } else {
          toggleSections('tourguide');
        }
      }


      // Restore sections on page load based on saved state or default to profile
      restoreSections();

      // Event listeners for menu buttons
      document.getElementById("profileButton").addEventListener("click", function() {
        toggleSections('profile');
      });

      document.getElementById("itemsButton").addEventListener("click", function() {
        toggleSections('items');
      });

      document.getElementById("addedProductsButton").addEventListener("click", function() {
        toggleSections('addedItems');
      });
      document.getElementById("transportButton").addEventListener("click", function() {
        toggleSections('transportServices');
      });
      document.getElementById("tourguideButton").addEventListener("click", function() {
        toggleSections('tourguide');
      });
    });
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDq7kf2-sAqWNjLPQh8Ye-Nx0pHBSbZ2eM&callback=initMap&libraries=&v=weekly" async defer></script>
  <script>
    function initMap() {
      var mapOptions = {
        center: new google.maps.LatLng(-34.397, 150.644),
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      var map = new google.maps.Map(document.getElementById("map"), mapOptions);
    }
  </script>
  <script src="./components/mapdata.js"></script>

  <script>
    function deleteHotel(id) {
      if (confirm('Are you sure you want to delete this service?')) {
        window.location.href = './profilescreen.php?type=hotel&id=' + id;
      }

    }
  </script>
  <script>
    function deleterestaurant(id) {
      if (confirm('Are you sure you want to delete this service?')) {
        window.location.href = './profilescreen.php?type=restaurant&id=' + id;
      }

    }
  </script>
  <script>
    function deletetransport(id) {
      if (confirm('Are you sure you want to delete this service?')) {
        window.location.href = './profilescreen.php?type=transport&id=' + id;
      }

    }
  </script>
  <script>
    function deleteguide(id) {
      if (confirm('Are you sure you want to delete this service?')) {
        window.location.href = './profilescreen.php?type=tourguide&id=' + id;
      }

    }
  </script>
</body>

</html>