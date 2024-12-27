<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    // User is logged in
    if (isset($_POST['logout'])) {
        // Log out the user by destroying the session
        session_destroy();
        // Redirect to the login page
        header('Location: index.php');
        exit();
    }
    // Fetch the username from the session variable
    $username = $_SESSION['username'];
    $postedBy = $_SESSION['uid'];
    $servername = "localhost";
    $dbusername = "root";
    $password = "";
    $database = "oddjobs";

    $pdo = new PDO("mysql:host=$servername;dbname=$database", $dbusername, $password);
    // Fetch the username from the session variable
    $username = $_SESSION['username'];

    // Fetch user image from database
    $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $profileImage = $user['profile_image'] ?? 'default_profile.png';
} else {
    // User is not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - OddJobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./logo/logo.png">
    <style>
        /* Basic CSS for navbar */
        body {
            font-family: 'Open Sans', sans-serif;
            /* Apply Open Sans font */
            margin: 0;
            padding: 0;
            position: relative;
            /* Ensure the footer is at the bottom of the page */
            background-color: #04061a;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0);
            /* Adjusted opacity */
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            /* Set position to fixed */
            top: 0;
            /* Position it at the top */
            left: 0;
            /* Align it to the left */
            right: 0;
            /* Align it to the right */
            z-index: 1000;
            /* Ensure the navbar stays on top of other content */
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        .navbar-left {
            display: flex;
            align-items: center;
            font-family: 'Open Sans', sans-serif;
            /* Apply Open Sans font */
        }

        .navbar-logo img {
            height: 32px;
            margin-right: 10px;
            cursor: pointer;
            /* Add cursor pointer to indicate clickable */
            transition: transform 0.3s ease;
            /* Smooth transition for size increase */
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .navbar-logo img:hover {
            transform: scale(1.2);
            /* Increase size on hover */
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .navbar-left a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            transition: all 0.3s ease;
            /* Smooth transition for hover effect */
        }

        .navbar-left a:hover {
            background-color: #555;
            border-radius: 6px;
            transform: scale(1.1);
            /* Increase size on hover */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            /* Add glowing effect */
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .navbar-right input[type="text"],
        .navbar-right button {
            margin-left: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            background-color: #555;
            color: #fff;
            cursor: text;
        }

        .navbar-right input[type="text"] {
            width: 200px;
        }

        .navbar-right .notification-link,
        .navbar-right .profile-link {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .navbar-right .notification-link img {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            /* Make the image circular */
            margin-left: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
            /* Smooth transition for size increase */
        }

        .navbar-right .profile-link img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            /* Make the image circular */
            margin-left: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
            /* Smooth transition for size increase */
        }

        .navbar-right .notification-link img:hover,
        .navbar-right .profile-link img:hover {
            transform: scale(1.1);
            /* Increase size on hover */
        }

        /* Basic CSS for popup */
        .popup {
            display: none;
            position: absolute;
            top: 50px;
            /* Adjust the top position */
            right: 20px;
            /* Adjust the right position */
            background-color: #fff;
            color: #333;

            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 999;
            padding: 20px;
            max-width: 300px;
            width: 250px;
        }

        .popup.active {
            display: block;
        }

        .profile-info,
        .notification-info {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-top: -29px;
        }

        .profile-details,
        .notification-details {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .popup-profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .username {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: -20px;
        }

        .your-profile {
            color: blue;
            /* Adjust link color as needed */
            text-decoration: underline;
        }

        .sign-out,
        .clear-notification {

            color: #010101;
            border: none;

            background-color: #ffffff00;
            cursor: pointer;
            padding: 10px;
            margin-top: -10px;
            margin-right: -20px;
        }

        .sign-out:hover,
        .clear-notification:hover {
            background-color: #555;
            color: #fff;
        }

        /* CSS for the notification message */
        .notification-info {
            border-bottom: 1px solid #ccc;
            /* Add border bottom */
            padding-bottom: 10px;
            /* Add some padding */

        }

        .no-notification {
            margin-top: 10px;
            /* Add some top margin */
            color: #666;
            /* Adjust text color */
            text-align: center;
            /* Center text */
            width: 100%;
            /* Set width to 100% */
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="navbar-left" id="logoContainer">
            <div class="navbar-logo">
                <img src="./logo/logo.png" alt="OddJobs">
            </div>
            <a href="./dashboard.php">Find Job</a>
            <a href="./dashboard.php">Post Job</a>
            <a href="./dashboard.php">Buy & Sell</a>
            <a href="./contact.php">Contact</a>
            <a href="./about.php">About</a>
        </div>
        <div class="navbar-right">
            <form id="searchForm" action="#">
                <input id="searchInput" type="text" placeholder="Search OddJobs">
            </form>
            <!-- Notification Icon -->
            <a class="notification-link" href="#">
                <img src="./icons/bell.png" title="bell icons">
            </a>
            <!-- Profile Image -->
            <a class="profile-link" href="#">
                <img src="./images/<?php echo $profileImage; ?>" alt="Profile Image" id="profileImg">
            </a>
        </div>
    </div>

    <div class="popup" id="profilePopup">
        <div class="profile-info">
            <h3>OddJobs</h3>
            <form method="post">
                <button type="submit" name="logout" class="sign-out">Sign Out</button>
            </form>
        </div>
        <div class="profile-details">
            <img src="./images/<?php echo $profileImage; ?>" alt="Profile Image" class="popup-profile-img">
            <div>
                <p class="username"><?php echo $username; ?></p>
                <a href="profile.php" class="your-profile">Your Profile</a>
            </div>
        </div>
    </div>

    <div class="popup" id="notificationPopup">
        <div class="notification-info">
            <h3>Notifications</h3>
            <button class="clear-notification" onclick="clearNotifications()">Clear</button>
        </div>
        <!-- <hr> Add horizontal line -->
        <div class="notification-details">
            <p class="no-notification">No notifications</p> <!-- Add no-notification message -->
        </div>
    </div>

    <script>
        // Toggle profile popup
        document.getElementById('profileImg').addEventListener('click', function(event) {
            event.stopPropagation();
            const profilePopup = document.getElementById('profilePopup');
            const notificationPopup = document.getElementById('notificationPopup');
            notificationPopup.classList.remove('active'); // Close notification popup if open
            profilePopup.classList.toggle('active');
            if (profilePopup.classList.contains('active')) {
                document.addEventListener('click', closePopups);
            }
        });

        // Toggle notification popup
        document.querySelector('.notification-link').addEventListener('click', function(event) {
            event.stopPropagation();
            const notificationPopup = document.getElementById('notificationPopup');
            const profilePopup = document.getElementById('profilePopup');
            profilePopup.classList.remove('active'); // Close profile popup if open
            notificationPopup.classList.toggle('active');
            if (notificationPopup.classList.contains('active')) {
                document.addEventListener('click', closePopups);
            }
        });

        function closePopups(event) {
            const profilePopup = document.getElementById('profilePopup');
            const notificationPopup = document.getElementById('notificationPopup');

            if (!profilePopup.contains(event.target) && !document.getElementById('profileImg').contains(event.target)) {
                profilePopup.classList.remove('active');
            }
            if (!notificationPopup.contains(event.target) && !document.querySelector('.notification-link').contains(event.target)) {
                notificationPopup.classList.remove('active');
            }

            if (!profilePopup.classList.contains('active') && !notificationPopup.classList.contains('active')) {
                document.removeEventListener('click', closePopups);
            }
        }

        // Sign out function
        function signOut() {
            // Your sign out logic here
            alert('Signing out...');
        }

        // Clear notifications function
        function clearNotifications() {
            // Your clear notifications logic here
            alert('Clearing notifications...');
        }
    </script>

</body>

</html>