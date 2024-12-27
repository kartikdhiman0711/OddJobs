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
    $jqry = mysqli_query($db, "SELECT * FROM jobs WHERE posted_by = '$postedBy' ORDER BY job_id DESC");
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
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>

<body>
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

    <!-- the container -->
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
                            <input type="text" name="jobTitle" id="jobTitlePopup" placeholder="Job Title" required><br><br>
                            <textarea name="jobDescription" id="jobDescriptionPopup" placeholder="Job Description" required></textarea><br><br>
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
                                <option value="temporary">Temporary</option>
                                <option value="freelance">Freelance</option>
                                <option value="internship">Internship</option>
                                <option value="remote">Remote</option>
                                <option value="volunteer">Volunteer</option>
                            </select><br><br>
                            <input type="text" name="location" id="locationPopup" placeholder="Enter Location" required><br><br>
                            <select name="workMode" id="workModePopup">
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
                <p><a href="#" class="repository-link"><?php echo $username; ?></a></p>
                <p>Recent activity: will be updated based on your recent activity</p>
            </div>

            <!-- Add more sections here if needed -->
        </div>
        <div class="middle-column">
            <div class="section">
                <h2>Jobs</h2>
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
                            echo '</div>';
                            
                        }
                    } else {
                        echo "<p>No Jobs Found</p>";
                    }
                    ?>
                </div>
            </div>

            <!-- Add more sections here if needed -->
        </div>
        <div class="column-right">
            <div class="section">
                <h2>Filter Jobs</h2>
                <div class="filter-options">
                    <div class="filter-option">
                        <label for="category">Category:</label>
                        <select name="category" id="category">
                            <option value="all">All Categories</option>
                            <option value="academic writing">Academic Writing</option>
                            <option value="animation">Animation</option>
                            <!-- Add options for different categories -->
                        </select>
                    </div>
                    <div class="filter-option">
                        <label for="location">Location:</label>
                        <input type="text" name="location" id="location" placeholder="Enter Location" required>
                    </div>
                    <div class="filter-option">
                        <label for="job-type">Job Type:</label>
                        <select name="jobType" id="job-type">
                            <option value="all">All Job Types</option>
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="contract">Contract</option>
                            <option value="temporary">Temporary</option>
                            <option value="freelance">Freelance</option>
                            <option value="internship">Internship</option>
                            <option value="remote">Remote</option>
                            <option value="volunteer">Volunteer</option>
                            <!-- Add more options for different job types -->
                        </select>
                    </div>
                    <div class="filter-option">
                        <label for="date-posted">Date Posted:</label>
                        <select name="datePosted" id="date-posted">
                            <option value="last12Hours">Last 12 Hours</option>
                            <option value="last24Hours">Last 24 Hours</option>
                            <option value="lastWeek">Last 1 Week</option>
                            <option value="lastMonth">Last 1 Month</option>
                            <option value="last6Months">Last 6 Months</option>
                        </select>
                    </div>
                </div>
                <button id="apply-filter">Apply Filter</button>
            </div>
        </div>
    </div>
    

    <!-- Slick Carousel JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <!-- Initialize the carousel -->
    <script type="text/javascript">
        $(document).ready(function(){
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

        // Initialize Google Places Autocomplete for location fields
        var locationInput = document.getElementById('locationPopup');
        var filterLocationInput = document.getElementById('location');

        var autocompleteOptions = {
            types: ['geocode']
        };

        var locationAutocomplete = new google.maps.places.Autocomplete(locationInput, autocompleteOptions);
        var filterLocationAutocomplete = new google.maps.places.Autocomplete(filterLocationInput, autocompleteOptions);
        // Initialize Swiper
        var swiper = new Swiper('.swiper-container', {
            pagination: {
                el: '.swiper-pagination',
            },
        });

        // Function to handle filter application
        document.getElementById('apply-filter').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission

            // Get filter values
            var category = document.getElementById('category').value;
            var location = document.getElementById('location').value;
            var jobType = document.getElementById('job-type').value;
            var datePosted = document.getElementById('date-posted').value;

            // Apply filters and fetch relevant job posts
            fetchJobPosts(category, location, jobType, datePosted);
        });

        // Function to fetch job posts based on filters
        function fetchJobPosts(category, location, jobType, datePosted) {
            // Your logic to fetch job posts based on the selected filters goes here
            console.log("Fetching job posts based on the following filters:");
            console.log("Category: " + category);
            console.log("Location: " + location);
            console.log("Job Type: " + jobType);
            console.log("Date Posted: " + datePosted);
        }
    </script>
    <!-- Google Places API script -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initAutocomplete" async defer></script>
</body>

</html>