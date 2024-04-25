<?php
 // Ensure session start is at the top to access session variables

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fullname']) && isset($_POST['email'])) {
    // Step 1: Connect to your database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Rehbar";
    $alert_message = "";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Step 2: Sanitize and retrieve user information from the form
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $user_email = $_SESSION['email']; // Assuming the user's original email is stored in session

    // Step 3: Update user information in the database using prepared statements
    $sql = "UPDATE service_provider SET fullname=?, email=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $fullname, $email, $user_email);
        if ($stmt->execute()) {
            $alert_message = '<div class="alert success"><strong>Success!</strong> Updated successfully.</div>';
            $_SESSION['email'] = $email; // Update session email if email was changed
        } else {
            $alert_message = '<div class="alert warning"><strong>Error!</strong> Failed to update record: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    } else {
        $alert_message = '<div class="alert warning"><strong>Error!</strong> ' . $conn->error . '</div>';
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
