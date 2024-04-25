<?php

if (isset($_POST['fullname']) && isset($_POST['email'])) {
    // Step 1: Connect to your database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wonderland";
    $alert_message = "";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Step 2: Retrieve user information from the form
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $user_email = $_SESSION['email'];
    $email = $_SESSION['email'];
    $sql = "SELECT fullname, email FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $fullname = $row['fullname'];
      $email = $row['email'];
    }
    // Step 3: Update user information in the database
    $sql = "UPDATE users SET fullname='$fullname', email='$email' WHERE email='$user_email'";
    if ($conn->query($sql) === TRUE) {
        $alert_message = '<div class="alert success"><strong>Success!</strong> Updated successfully</div>';
    } else {
        $alert_message = '<div class="alert warning"><strong>Error!</strong> Failed to update record: ' . $conn->error . '</div>';
    }

    // Close the database connection
    $conn->close();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Profile Update</title>
    <!-- Add your CSS styles here -->
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
    </style>
</head>

<body>
    <!-- Your HTML content here -->

    <?php
    // Display the alert message if it's set
    if (isset($alert_message)) {
        echo $alert_message;
    }
    ?>

    <!-- Rest of your HTML content -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var alerts = document.querySelectorAll(".alert");

            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = "0";
                    setTimeout(function() {
                        alert.style.display = "none";
                    }, 600);
                }, 3000); // Fade out after 3 seconds
            });
        });
    </script>
</body>

</html>
