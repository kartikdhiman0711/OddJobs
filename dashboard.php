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

    $db = mysqli_connect($servername, $dbusername, $password, $database);

    // Check if filter parameters are submitted
    if (isset($_POST['apply-filter'])) {
        // Retrieve filter parameters
        $category = $_POST['category'];
        $location = $_POST['location'];
        $jobType = $_POST['jobType'];
        $datePosted = $_POST['datePosted'];
        $workMode = $_POST['work'];

        // Construct SQL query with filter parameters
        $sql = "SELECT * FROM jobs WHERE 1=1"; // Start with a basic query

        if ($category != 'all') {
            $sql .= " AND category = '$category'";
        }
        if ($location) {
            $sql .= " AND location = '$location'";
        }
        if ($jobType != 'all') {
            $sql .= " AND job_type = '$jobType'";
        }
        if ($workMode != 'all') {
            $sql .= " AND work_mode = '$workMode'";
        }
        if ($datePosted != 'all') { 
            switch ($datePosted) {
                case 'last12Hours':
                    $sql .= " AND date_posted >= DATE_SUB(NOW(), INTERVAL 12 HOUR)";
                    break;
                case 'last24Hours':
                    $sql .= " AND date_posted >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
                    break;
                case 'lastWeek':
                    $sql .= " AND date_posted >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
                    break;
                case 'lastMonth':
                    $sql .= " AND date_posted >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                    break;
                case 'last6Months':
                    $sql .= " AND date_posted >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
                    break;
            }
        }

        // Add conditions for other filter parameters similarly

        $sql .= " ORDER BY job_id DESC";

        // Execute the query
        $jqry = mysqli_query($db, $sql);
    } else {
        // No filter parameters provided, fetch all job posts
        $jqry = mysqli_query($db, "SELECT * FROM jobs ORDER BY job_id DESC");
    }

    $tjobs = mysqli_num_rows($jqry);
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
    <title>OddJobs - Job Board</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./logo/logo.png">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<style>
        /* Side Menu Styles */
        .side-menu {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .side-menu a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 20px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .side-menu .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            transition: transform 0.3s ease;
        }

        .closebtn:hover{
            cursor: pointer;
            transform: scale(1.2);
        }

        .menu-toggle {
            position: relative;
            top: -4px;
            font-size: 30px;
            cursor: pointer;
            color: white;
            margin-right: 20px;
            transition: transform 0.3s ease;
        }

        .menu-toggle:hover {
            transform: scale(1.2);
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .apply_job{
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            background-color: #0366d6;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .apply_job:hover{
            background-color: #0056b3;
        }

        /* Job Application Popup */
#jobApplyPopup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #ffffff;
    color: #333;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 999;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    border-radius: 10px;
}

#jobApplyPopup.active {
    display: block;
}

#jobApplyPopup h3 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

#jobApplyPopup input[type="text"],
#jobApplyPopup input[type="number"],
#jobApplyPopup textarea,
#jobApplyPopup select {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 16px;
}

#jobApplyPopup button[type="submit"] {
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    background-color: #28a745;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 16px;
}

#jobApplyPopup button[type="submit"]:hover {
    background-color: #218838;
}

    </style>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-left">
        <span class="menu-toggle" onclick="openNav()">&#9776;</span>
            <div id="mySidenav" class="side-menu">
            <span href="javascript:void(0)" class="closebtn" onclick="closeNav()">&#9776;</span>
                <a href="./index.php">Home</a>
                <a href="./dashboard.php">Buy & Sell</a>
                <a href="./contact.php">Contact</a>
                <a href="./about.php">About</a>
            </div>
            <div class="navbar-logo" id="logoContainer">
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


    <!-- Popup content -->
    <!-- Container -->
    <div class="container">
        <div class="column-left">
            <div class="section">
                <h2>Have A Job</h2>
                <div class="popup job-post-popup" id="jobPostPopup">
                    <div class="profile-info">
                        <h3>Post a Job</h3>
                    </div>
                    <div class="profile-details job-post-form-container">
                        <form id="jobPostFormPopup" action="post_job.php" enctype="multipart/form-data" method="post">
                            <input type="text" name="jobTitle" id="jobTitlePopup" placeholder="Job Title"
                                required><br><br>
                            <textarea name="jobDescription" id="jobDescriptionPopup" placeholder="Job Description"
                                required></textarea><br><br>
                            <select name="category" id="categoryPopup" placeholder="category">
                                <option value="all">All Categories</option>
                                <option value="academic writing">Academic Writing</option>
                                <option value="animation">Animation</option>
                            </select><br><br>
                            <select name="jobType" id="jobTypePopup">
                                <option value="all">All Job Types</option>
                                <option value="full-time">Full Time</option>
                                <option value="part-time">Part Time</option>
                                <option value="contract">Contract</option>
                            </select><br><br>
                            <input type="text" name="location" id="locationPopup" placeholder="Enter Location"
                                required><br><br>
                            <select name="workMode" id="workModePopup">
                                <option value="all">Any Mode</option>
                                <option value="onsite">Onsite</option>
                                <option value="hybrid">Hybrid</option>
                            </select><br><br>
                            <!-- Input for uploading multiple photos -->
                            <input type="file" id="jobPhotos" name="jobPhotos[]" accept="image/*" multiple>
                            <!-- Container to display selected images -->
                            <div id="selectedImages"></div>
                            <button type="submit">Post Job</button>
                        </form>
                    </div>
                </div>

                <button id="openJobPostPopup">New</button>
                <p><a href="profile.php" class="repository-link"><?php echo $username; ?></a></p>
                <p>Recent activity: will be updated based on your recent activity</p>
            </div>

            <!-- Add more sections here if needed -->
        </div>
        <!-- Middle column -->
        <div class="middle-column">
            <div class="section">
                <h2>Jobs</h2>
                <!-- Job posts container -->
                <div class="job-posts-container" id="jobPostsContainer">
                    <?php
                    if ($tjobs > 0) {
                        while ($jobsdata = mysqli_fetch_assoc($jqry)) {
                            $jid = $jobsdata['job_id'];
                            echo '<div class="job-post">';
                            echo '<div class="job-details">';
                            echo "<h2 class='job-title'>" . $jobsdata['title'] . "</h2>";
                            $img_qry = mysqli_query($db, "SELECT * FROM job_images WHERE job_id = '$jid'");
                            if (mysqli_num_rows($img_qry) > 0) {
                                echo '<div class="job-images">';
                                echo '<div class="image-carousel">';
                                while ($img_ftch = mysqli_fetch_assoc($img_qry)) {
                                    echo '<div><img src="' . $img_ftch['image_path'] . '" class="job-image"></div>';
                                }
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                            echo "<p class='job-desc'>" . $jobsdata['description'] . "</p>";
                            echo "<p class='job-category'><strong>Category:</strong> " . $jobsdata['category'] . "</p>";
                            echo "<p class='job-type'><strong>Job Type:</strong> " . $jobsdata['job_type'] . "</p>";
                            echo "<p class='job-location'><strong>Location:</strong> " . $jobsdata['location'] . "</p>";
                            echo "<p class='job-mode'><strong>Mode:</strong> " . $jobsdata['work_mode'] . "</p>";
                            echo "<p class='job-posted-date'><strong>Date Posted:</strong> " . $jobsdata['date_posted'] . "</p>";
                            if ($jobsdata['posted_by'] != $postedBy) {
                                echo '<button class="apply_job">Apply Job<input type="hidden" name="apply_job_id" value="' . $jobsdata['job_id'] . '"></button>';
                            }                                                       
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No Jobs Found</p>";
                    }                    
                    ?>
                </div>
            </div>
        </div>

        <!-- Job Application Popup -->
<div class="popup job-apply-popup" id="jobApplyPopup">
    <div class="profile-info">
        <h3>Apply for Job</h3>
    </div>
    <div class="profile-details job-apply-form-container">
        <form id="jobApplyFormPopup" action="apply.php" method="post">
            <textarea name="description" id="applicationDescriptionPopup" placeholder="Application Description" required></textarea><br><br>
            <select name="currency" id="currencyPopup" required>
                <option value="USD">USD</option>
                <option value="INR">INR</option>
            </select><br><br>
            <input type="number" name="bid_amount" id="bidAmountPopup" placeholder="Bid Amount" step="0.01" required><br><br>
            <input type="number" name="days" id="durationPopup" placeholder="Available (in days)" required><br><br>
            <input type="hidden" name="apply_job_id" id="applyJobIdPopup">
            <button type="submit">Apply</button>
        </form>
    </div>
</div>

        <!-- Right column -->
        <div class="column-right">
            <div class="section">
                <h2>Filter Jobs</h2>
                <!-- Filter options -->
                <div class="filter-options">
                    <!-- Filter form -->
                    <form method="post" action="">
                        <div class="filter-option">
                            <label for="category">Category:</label>
                            <select name="category" id="category">
                                <option value="all">All Categories</option>
                                <option value="academic writing">Academic Writing</option>
                                <option value="animation">Animation</option>
                            </select>
                        </div>
                        <div class="filter-option">
                            <label for="location">Location:</label>
                            <input type="text" name="location" id="location" placeholder="Enter Location">
                        </div>
                        <div class="filter-option">
                            <label for="jobType">Job Type:</label>
                            <select name="jobType" id="jobType">
                                <option value="all">All Job Types</option>
                                <option value="full-time">Full Time</option>
                                <option value="part-time">Part Time</option>
                                <option value="contract">Contract</option>
                            </select>
                        </div>
                        <div class="filter-option">
                            <label for="work">Work Mode:</label>
                            <select name="work" id="work">
                            <option value="all">Any Mode</option>
                                <option value="onsite">Onsite</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div class="filter-option">
                            <label for="datePosted">Date Posted:</label>
                            <select name="datePosted" id="datePosted">
                                <option value="all">All Dates</option>
                                <option value="last12Hours">Last 12 Hours</option>
                                <option value="last24Hours">Last 24 Hours</option>
                                <option value="lastWeek">Last 1 Week</option>
                                <option value="lastMonth">Last 1 Month</option>
                                <option value="last6Months">Last 6 Months</option>
                            </select>
                        </div>
                        <button type="submit" id="apply-filter" name="apply-filter">Apply Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        }
</script>

    <!-- Slick Carousel JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <!-- Initialize the carousel -->
    <script type="text/javascript">
    $(document).ready(function() {
        $('.image-carousel').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
            arrows: true
        });
    });
    </script>
    <script>
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

    // Add click event listener to logo container
    document.getElementById('logoContainer').addEventListener('click', function() {
        window.location.href = 'index.php'; // Change URL to your desired link
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

    // Function to close popups
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

    // Display job posting popup when the button is clicked
    document.getElementById('openJobPostPopup').addEventListener('click', function(event) {
        event.stopPropagation();
        const jobPostPopup = document.getElementById('jobPostPopup');
        jobPostPopup.classList.toggle('active');
        if (jobPostPopup.classList.contains('active')) {
            document.addEventListener('click', closePopup);
        }
    });

    // Function to close the popup
    function closePopup(event) {
        const jobPostPopup = document.getElementById('jobPostPopup');
        if (!jobPostPopup.contains(event.target) && !document.getElementById('openJobPostPopup').contains(event
                .target)) {
            jobPostPopup.classList.remove('active');
            document.removeEventListener('click', closePopup);
        }
    }
    // Function to handle file selection for job photos
    document.getElementById('jobPhotos').addEventListener('change', function(event) {
        var files = event.target.files;
        var selectedImagesContainer = document.getElementById('selectedImages');
        selectedImagesContainer.innerHTML = ''; // Clear previous selection

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('selected-image');
                selectedImagesContainer.appendChild(img);
            };

            reader.readAsDataURL(file);
        }
    });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Get all the Apply Job buttons
    const applyJobButtons = document.querySelectorAll('.apply_job');
    const jobApplyPopup = document.getElementById('jobApplyPopup');
    const applyJobIdInput = document.getElementById('applyJobIdPopup');

    // Function to close the popup
    const closeJobApplyPopup = () => {
        jobApplyPopup.classList.remove('active');
    };

    // Attach click event to each Apply Job button
    applyJobButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            // Set job ID in the hidden input field
            const jobId = this.querySelector('input[name="apply_job_id"]').value;
            applyJobIdInput.value = jobId;
            // Display the popup
            jobApplyPopup.classList.add('active');
        });
    });

    // Close the popup when clicking outside of it
    window.addEventListener('click', function (event) {
        if (!jobApplyPopup.contains(event.target) && !Array.from(applyJobButtons).some(button => button.contains(event.target))) {
            closeJobApplyPopup();
        }
    });

    // Optional: Close the popup when clicking on a close button inside the popup
    const closeButton = jobApplyPopup.querySelector('.close-button');
    if (closeButton) {
        closeButton.addEventListener('click', closeJobApplyPopup);
    }
});

</script>


    <script>
    // Function to handle form submission for filter
    function applyFilter(event) {
        event.preventDefault(); // Prevent form submission
        var category = document.getElementById('category').value;
        var location = document.getElementById('location').value;
        var jobType = document.getElementById('jobType').value;
        var datePosted = document.getElementById('datePosted').value;

        // Construct the form data
        var formData = new FormData();
        formData.append('category', category);
        formData.append('location', location);
        formData.append('jobType', jobType);
        formData.append('datePosted', datePosted);

        // Send a POST request to the current page with filter parameters
        fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Replace the job posts container with the updated content
                document.getElementById('jobPostsContainer').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Add event listener for form submission
    document.getElementById('filterForm').addEventListener('submit', applyFilter);
    </script>

</body>

</html>