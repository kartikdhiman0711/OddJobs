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
    <title>OddJobs - Buy & Sell</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./logo/logo.png">
    <link rel="stylesheet" href="css/buy_sell_style.css">
</head>

<body>
    <video class="video-background" autoplay loop muted>
        <!-- <source src="./images/background2.mp4" type="video/mp4">    -->
        <source src="https://videos.pexels.com/video-files/2611250/2611250-uhd_3840_2160_30fps.mp4" type="video/mp4">
    </video>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-left" id="logoContainer">
            <div class="navbar-logo">
                <img src="./logo/logo.png" alt="OddJobs">
            </div>
            <a href="./dashboard.php">Dashboard</a>
        </div>
        <div class="navbar-right">
            <form id="searchForm" action="#">
                <input id="searchInput" type="text" placeholder="Search OddJobs">
            </form>
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
        <div class="notification-details">
            <p class="no-notification">No notifications</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <div class="message">
            <h1>Buy & Sell</h1>
            <p>Coming Soon</p>
            <button class="go-back-button" onclick="goBack()">Go Back</button>
        </div>
    </div>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>What we do.</h3>
                <p>we provide a place to find and post jobs.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links.</h3>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./about.php">About</a></li>
                    <li><a href="./index.php">Services</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us.</h3>
                <p>CR12, Central University Himachal Pradesh</p>
                <p>Email: we.oddjobs.info@gmail.com</p>
                <p>Phone: +123-456-7890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 OddJobs. All rights reserved.</p>
        </div>
    </footer>

    <script>
    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
    var navbar = document.querySelector('.navbar');
    var scrollPosition = window.scrollY;

    window.onscroll = function() {
        var currentScrollPos = window.scrollY;

        if (scrollPosition > currentScrollPos) {
            navbar.style.backgroundColor = "rgba(0, 0, 0, 0)";
        } else {
            navbar.style.backgroundColor = "#2e2c2c"; // Change to your desired background color
        }

        scrollPosition = currentScrollPos;
    }

    document.getElementById('profileImg').addEventListener('click', function(event) {
        event.stopPropagation();
        const profilePopup = document.getElementById('profilePopup');
        const notificationPopup = document.getElementById('notificationPopup');
        notificationPopup.classList.remove('active');
        profilePopup.classList.toggle('active');
        if (profilePopup.classList.contains('active')) {
            document.addEventListener('click', closePopups);
        }
    });

    document.getElementById('logoContainer').addEventListener('click', function() {
        window.location.href = 'index.php';
    });

    document.querySelector('.notification-link').addEventListener('click', function(event) {
        event.stopPropagation();
        const notificationPopup = document.getElementById('notificationPopup');
        const profilePopup = document.getElementById('profilePopup');
        profilePopup.classList.remove('active');
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

        if (!notificationPopup.contains(event.target) && !document.querySelector('.notification-link').contains(event
                .target)) {
            notificationPopup.classList.remove('active');
        }

        if (!profilePopup.classList.contains('active') && !notificationPopup.classList.contains('active')) {
            document.removeEventListener('click', closePopups);
        }
    }

    function signOut() {
        // Sign out logic here
        alert("Signed out");
    }

    function clearNotifications() {
        // Clear notifications logic here
        document.querySelector('.no-notification').textContent = "No notifications";
    }

    function goBack() {
        window.location.href = 'index.php';
    }
    </script>
</body>

</html>