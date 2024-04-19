<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Provider Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .dashboard {
            max-width: 1000px;
            height: #FFFFFF;
            margin: 50px auto;
            padding: 30px;
            background-color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .provider-info {
            margin-bottom: 30px;
        }

        .provider-info h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .provider-info p {
            font-size: 18px;
            margin: 10px 0;
            color: #000;
        }

        .actions {
            display: flex;
            flex-direction: row; /* Display children horizontally */
            justify-content: space-between;
            gap: 20px; /* Add space between the action boxes */
        }

        .action-box {
            flex: 1;
            padding: 20px;
            background-color: #CCCCCC; /* Light blue background */
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .action-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .action-box h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #000066;
        }

        .action-box h3 span {
            display: block;
            border-bottom: 2px solid #007bff; /* Blue underline */
            margin-bottom: 10px;
            padding-bottom: 5px;
            font-weight: bold;
        }

        .action-box p {
            font-size: 16px;
            color: #333; /* Dark text color */
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="provider-info">
            <h2> Service Provider Information</h2>
            <p><strong>Name:</strong>Abdullah</p>
            <p><strong>ID:</strong> RftY875Bc</p>
            <p><strong>Email:</strong> abdullah@gmail.com</p>
            <p><strong>Phone:</strong> 123-456-7890</p>
        </div>
        <div class="actions">
            <div class="action-box create" id="createButton">
                <h3>Create</h3>
                <span></span>
                <p>Create new service</p>
            </div>
            <div class="action-box update">
                <h3>Update</h3>
                <span></span>
                <p>Update existing service</p>
            </div>
            <div class="action-box delete">
                <h3>Delete</h3>
                <span></span>
                <p>Delete existing service</p>
            </div>
            <div class="action-box block">
                <h3>Block</h3>
                <span></span>
                <p>Block user access</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('createButton').addEventListener('click', function() {
            window.location.href = './sp_register.html'; // Redirect to create page
        });
    </script>
</body>
</html>
