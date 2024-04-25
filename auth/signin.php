<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Rehbar";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['uname'] ?? '';
    $password = $_POST['psw'] ?? '';
    $category = $_POST['select'] ?? '';

    $table_name = "";
    if ($category === "tourist") {
        $table_name = "tourist_users";
        $redirect_page = "../home.html"; // Redirect to tourist dashboard
    } elseif ($category === "service-provider") {
        $table_name = "service_provider_users";
        $redirect_page = "../services/sp_dashboard.php"; // Redirect to service provider dashboard
    }

    $sql = "SELECT * FROM $table_name WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
                                                            
    if ($result->num_rows > 0) {
        // User found, set session variables and redirect to appropriate dashboard
        $_SESSION['username'] = $username;
        $_SESSION['category'] = $category;
        header("Location: $redirect_page"); // Redirect to appropriate dashboard page
        exit();
    } else {
        // User not found, show error message
        $error_message = "Invalid username or password";
    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Rehbar</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 16px;
      background-color: #f4f4f4;
    }

    /* Container styles */
    .login-container {
      background-color: #FFFFFF;
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

    .login-container {
      background-color: rgba(255, 255, 255, 0.6);
      /* Transparent white background */
      border: 1px solid #ccc;
      border-radius: 8px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      width: 300px;
      text-align: left;
      padding: 30px;
      padding-right: 3%;
    }

    .login-heading {
      text-align: center;
      font-size: 30px;
      font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
      margin-bottom: 20px;
      font-weight: bold;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: 600;
      display: block;
      margin-bottom: 8px;
      color: #333;
    }

    .form-input,
    .form-select {
      width: 108%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }

    .form-group input[type="text"],
    .form-group input[type="password"],
    .form-group input[type="email"],
    .form-group input[type="tel"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }

    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
      font-size: 14px;
    }

    .remember-me label {
      color: #555;
    }

    .forgot-password {
      color: #555;
    }

    .login-button {
      background-color: #003366;
      color: white;
      border: none;
      padding: 12px 0;
      border-radius: 4px;
      cursor: pointer;
      width: 108%;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .login-button:hover {
      background-color: #001f3f;
    }
  </style>
</head>

<body>

  <div class="login-container">
    <div class="background-image"></div>
    <div class="login-form">
      <h1 class="login-heading">Login</h1>
      <form id="loginForm" method="POST">
        <div class="form-group">
          <label for="uname">Username</label>
          <input type="text" placeholder="Enter Username" name="uname" required>
        </div>
        <div class="form-group">
          <label for="psw">Password</label>
          <input type="password" placeholder="Enter Password" name="psw" required>
        </div>
        <div class="form-group">
          <label for="category">Category</label>
          <select class="form-input" name="select" id="select">
            <option value="select category">Select a Category</option>
            <option value="tourist">Tourist</option>
            <option value="service-provider">Service Provider</option>
          </select>
        </div>
        <?php if (isset($error_message)) {
          echo '<div class="error-message">' . $error_message . '</div>';
        } ?>
        <button class="login-button" type="submit" name="submit">Login</button>
      </form>
      <div class="remember-forgot">
        <label class="remember-me">
          <input type="checkbox" checked="checked" name="remember"> Remember me
        </label>
        <a class="forgot-password" href="#">Forgot password?</a>
      </div>
    </div>
  </div>

 

</body>

</html>