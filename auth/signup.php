<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "Rehbar";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $fname = $_POST['fname'] ?? '';
  $lname = $_POST['lname'] ?? '';
  $username = $_POST['email'] ?? ''; 
  $password = $_POST['pswd'] ?? ''; 
  $category = $_POST['select'] ?? '';

  $name = $fname . ' ' . $lname;

  // Determine the table based on the selected category
  $table_name = "";
  if ($category === "tourist") {
    $table_name = "tourist_users";
  } elseif ($category === "service-provider") {
    $table_name = "service_provider_users";
  }

  // Insert user data into the appropriate table
  $sql = "INSERT INTO $table_name (name, username, password) VALUES ('$name', '$username', '$password')";

  if ($conn->query($sql) === TRUE) {
    // Redirect to a success page
    header("Location: signin.php");
    exit();
  } else {
    // Redirect to an error page
    header("Location: signup.php");
    exit();
  }

  $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup</title>
  <style>
    .alert {
      padding: 15px;
      background-color: #f44336;
      color: white;
      opacity: 1;
      transition: opacity 0.6s;
      margin-bottom: 15px;
    }

    .alert.success {
      background-color: #04AA6D;
    }

    .alert.info {
      background-color: #2196F3;
    }

    .alert.warning {
      background-color: #ff9800;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 16px;
      background-color: #f4f4f4;
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
      padding-right: 3%;
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
      width: 104%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .form-select {
      background-color: #f9f9f9;
    }

    .form-checkbox {
      display: flex;
      align-items: center;
      margin-top: 10px;
    }

    .form-checkbox input {
      margin-right: 8px;
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

    .form-button:hover {
      background-color: #002555;
    }

    .already-account {
      text-align: center;
      margin-top: 10px;
    }

    .already-account a {
      color: #003366;
      text-decoration: none;
    }
  </style>

</head>

<body>

  <div class="form-container">
    <h1 class="form-headings">Signup</h1>
    <form action="./signup.php" method="POST">
      <div class="form-section">
        <label class="form-label" for="fname">First Name</label>
        <input class="form-input" type="text" placeholder="Enter First Name" name="fname" required>
      </div>
      <div class="form-section">
        <label class="form-label" for="lname">Last Name</label>
        <input class="form-input" type="text" placeholder="Enter Last Name" name="lname" required>
      </div>
      <div class="form-section">
        <label class="form-label" for="username">Username</label>
        <input class="form-input" type="username" placeholder="Enter Username" name="email" required>
      </div>
      <div class="form-section">
        <label class="form-label" for="paswd">Password</label>
        <input class="form-input" type="password" placeholder="Enter Password" name="pswd" required>
      </div>
      <label class="form-label" for="category">Category</label>
      <select class="form-input" name="select" id="select" onChange="openForm(this.value)">
        <option value="tourist">Tourist</option>
        <option value="service-provider">Service Provider</option>
      </select>
      <div class="form-section">
        <div class="form-checkbox">
          <input type="checkbox" id="termsCheckbox" required>
          <label for="termsCheckbox">I agree to the <a href="#">Terms and Policies</a></label>
        </div>
      </div>
      <div class="form-buttons">
        <button class="form-button" type="submit" id="signupButton" name="submit">Sign Up</button>
      </div>
      <div class="already-account">
        Already have an account? <a href="./signin.html" id="loginLink">Login</a> </div>
    </form>
  </div>
  <script>
    // Wait for the document to be fully loaded before adding the event listeners
    document.addEventListener("DOMContentLoaded", function() {
      // Get a reference to the signup button using its id
      const signupButton = document.getElementById("signupButton");


      // Get a reference to the login hyperlink using its id
      const loginLink = document.getElementById("loginLink");

      // Add a click event listener to the login hyperlink
      loginLink.addEventListener("click", function() {
        // Redirect the user to the login page
        window.location.href = "rehbaaar.html";
      });
    });
  </script>
  
</body>

</html>
