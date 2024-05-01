<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in and is a service provider
if (isset($_SESSION['role']) && $_SESSION['role'] === 'service_provider') {
  $showProfileIcon = true;
} else {
  $showProfileIcon = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rehbar</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700&display=swap" rel="stylesheet" />
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Satisfy&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap");

    body {
      font-family: "Courier New", Courier, monospace;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      overflow-x: hidden;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.5rem 5%;
      /* Removed background-color */
    }

    .logo {
      font-size: 1.5rem;
      font-weight: bold;
      color: #fff;
      text-transform: uppercase;
      text-decoration: none;
    }

    .logo span {
      color: #ffa500;
    }

    .navbar {
      display: flex;
      align-items: center;
      justify-content: center;
      /* Centering navbar links */
      flex-grow: 1;
      /* Allow the navbar to grow and occupy the available space */
    }

    .navbar a {
      color: #fff;
      font-size: 1.2rem;
      text-transform: uppercase;
      text-decoration: none;
      padding: 0.5rem 1rem;
      transition: color 0.3s ease;
      margin: 0 1rem;
      /* Adjusted margin */
    }

    .navbar a:hover {
      color: #ffa500;
    }

    .icons {
      margin-left: auto;
      /* Move icons to the right side */
    }

    .icons i {
      font-size: 1.5rem;
      color: #fff;
      cursor: pointer;
      margin-left: 1rem;
      transition: color 0.3s ease;
    }

    .icons i:hover {
      color: #ffa500;
    }

    .content {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 5rem 5%;
      text-align: center;
      color: #fff;
      background-image: url("https://e1.pxfuel.com/desktop-wallpaper/478/534/desktop-wallpaper-backgrounds-for-travel-website-travel-agency.jpg");
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    .content p {
      font-size: 1rem;
      margin-bottom: 2rem;
    }

    /* Dropdown Menu Styles */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Media queries */
    @media (max-width: 768px) {
      .navbar {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        padding: 1rem 0;
        flex-direction: column;
        background-color: transparent;
        border-top: none;
        margin-left: 0;
        /* Remove margin for smaller screens */
      }

      .navbar.active {
        display: flex;
      }

      .navbar a {
        margin: 1rem 0;
      }

      .content {
        padding: 3rem 5%;
      }

      .content p {
        font-size: 0.9rem;
      }
    }

    .dashboard {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 100px;
      /* Adjust as needed */
    }

    .top-options,
    .bottom-options {
      display: flex;
      justify-content: space-around;
      align-items: center;
      text-decoration: none;
      width: 100%;
    }

    .option {
      text-align: center;
      margin-bottom: 30px;
      text-decoration: none;
    }

    .option img {
      width: 200px;
      /* Adjust image size as needed */
      height: 150px;
      /* Adjust image size as needed */
      border-radius: 10px;
    }

    .option h3 {
      font-size: 24px;
      margin-top: 10px;
    }

    /*===============Footer===================*/
    .footer {
      background-image: url("https://res.cloudinary.com/dxssqb6l8/image/upload/v1605293781/pine-tree_mq2sgp.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      width: 100%;
      height: 670px;
      position: relative;
      display: flex;
      flex-flow: row wrap;
      justify-content: center;
      align-items: center;
    }

    .footer::before {
      position: absolute;
      content: "";
      display: block;
      background-color: rgba(0, 0, 36, 0.8);
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
    }

    .footer .links {
      position: relative;
      color: #fff;
      flex: 1;
      display: flex;
      flex-flow: column;
      justify-content: center;
      align-items: center;
    }

    .links ul {
      list-style-type: none;
    }

    .links h3 {
      font-family: Barlow Condensed;
      font-weight: normal;
      font-size: 23px;
      margin-bottom: 15px;
    }

    .links li {
      font-family: Roboto;
      cursor: pointer;
      padding: 15px 0;
    }

    .links li:hover {
      color: #ffa801;
    }

    @media all and (max-width: 708px) {
      .footer {
        width: 100%;
        height: 670px;
        position: relative;
        display: flex;
        flex-flow: column wrap;
        justify-content: center;
        align-items: center;
      }
    }

    #map-container {
      position: absolute;
      top: 0;
      right: -100%;
      /* Initially off-screen to the right */
      transition: right 0.5s ease;
      /* Smooth transition for animation */
      z-index: 1;
      /* Ensure map is above other content */
    }

    /* Styling for the arrow icon */
    .content-img {
      position: relative;
      display: inline-block;
    }

    .content-img i {
      position: absolute;
      right: -600px;
      /* Adjust the position of the arrow */
      top: 50%;
      /* Align vertically */
      transform: translateY(-50%);
      cursor: pointer;
      color: #fff;
      background-color: rgba(0, 0, 0, 0.5);
      padding: 25px;
      border-radius: 50%;
      transition: background-color 0.3s ease;
    }

    .content-img i:hover {
      background-color: rgba(255, 255, 255, 0.5);
    }

    .explore-services {
      display: flex;
      justify-content: center;
      /* Center the button horizontally */
      margin-top: 10px;
      /* Space from top content */
      margin-bottom: 20px;
      /* Space from bottom content */
    }

    .explore-button {
      background-color: #0E172C;
      /* Vibrant orange */
      color: white;
      /* White text */
      font-size: 28px;
      /* Slightly larger font */
      text-decoration: none;
      /* Remove underline */
      padding: 10px 20px;
      /* Top and bottom, Left and right padding */
      border-radius: 15px;
      /* Rounded corners */
      transition: background-color 0.3s, transform 0.2s;
      /* Smooth transition for hover effects */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      /* Subtle shadow */
      display: inline-block;
      /* Allows for padding and margin to work */
      margin-bottom: 60px;
      font-weight: bold;
    }

    .explore-button:hover {
      background-color: #B8C1EC;
      /* Darker shade on hover */
      color: black;
      font-weight: bold;
      transform: scale(1.05);
      /* Slight increase in size */
      cursor: pointer;
      /* Pointer cursor on hover */
    }
  </style>
</head>

<body>
  <!-- header section starts -->
  <header>
    <a href="#" class="logo"><span>R</span>ehbar</a>
    <nav class="navbar">
      <a href="./display.php">Services</a>
      <a href="#">About</a>
      <a href="#">Contact Us</a>
      <a href="./screens/loginscreen.php">SignIn</a>
      <a href="./screens/registerscreen.php">Signup</a>
    </nav>
    <div class="icons">
      <i class="fas fa-bars" id="menu-btn"></i>
      <i class="fas fa-search" id="search-btn"></i>
      <?php if ($showProfileIcon) : ?>
        <a href="./screens/profilescreen.php" id="login-link">
          <i class="fas fa-user" id="login-btn"></i>
        </a>
      <?php endif; ?>
    </div>
  </header>
  <!-- header section ends -->

  <!-- content section -->
  <section class="content">
    <div class="content-img">
      <!-- Arrow  -->
      <button type="button" class="button" id="show-map" onClick="map()">
        <i class="fas fa-arrow-right"> </i>
      </button>
    </div>
  </section>

  <!-- Dashboard -->

  <section class="dashboard">
    <div class="explore-services">
      <a href="./display.php" class="explore-button">Explore Our Services</a>
    </div>
  </section>

  <!--===========Footer=================-->
  <div class="footer">
    <div class="links">
      <h3>Quick Links</h3>
      <ul>
        <li>Contact Us</li>
        <li>About</li>
      </ul>
    </div>

    <div class="links">
      <h3>Support</h3>
      <ul>
        <li>Frequently Asked Questions</li>
        <li>Report a Payment Issue</li>
        <li>Terms & Conditions</li>
        <li>Privacy Policy</li>
      </ul>
    </div>
  </div>
  <!-- JavaScript -->
  <script>
    function map() {
      src = "map.html";
    }
  </script>
  <script>
    let menuBtn = document.getElementById("menu-btn");
    let navbar = document.querySelector(".navbar");

    menuBtn.addEventListener("click", () => {
      navbar.classList.toggle("active");
    });

    document
      .getElementById("show-map")
      .addEventListener("click", function() {
        // Toggle the visibility of the map container by adding/removing the 'hidden' class
        document.getElementById("map-container").classList.toggle("hidden");
      });
  </script>
</body>

</html>