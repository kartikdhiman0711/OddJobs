<?php
session_start();

if (isset($_SESSION['loggedin'])) {
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "oddjobs";

$db = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$postedBy = $_SESSION['uid'];

// Fetch jobs posted by the user
$jqry = mysqli_query($db, "SELECT j.*, (SELECT COUNT(*) FROM job_applications ja WHERE ja.job_id = j.job_id) AS application_count FROM jobs j WHERE j.posted_by = '$postedBy' ORDER BY j.job_id DESC");
$tjobs = mysqli_num_rows($jqry);

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SHOW COLUMNS FROM users LIKE 'profile_image'");
    $stmt->execute();
    $column = $stmt->fetch();

    if (!$column) {
        $alterSql = "ALTER TABLE users ADD profile_image VARCHAR(255) DEFAULT 'default_profile.jpg'";
        $pdo->exec($alterSql);
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

$username = $_SESSION['username'];

$stmt = $pdo->prepare("SELECT profile_image FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$profileImage = $user['profile_image'] ?? 'default_profile.png';

$appliedJobsQry = mysqli_query($db, "SELECT j.* FROM job_applications ja JOIN jobs j ON ja.job_id = j.job_id WHERE ja.user_id = '$postedBy' ORDER BY ja.application_date DESC");
$appliedJobs = mysqli_fetch_all($appliedJobsQry, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $newUsername = $_POST['new_username'];
    $_SESSION['username'] = $newUsername;
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['new_profile_picture'])) {
    if ($_FILES["new_profile_picture"]["error"] == 0) {
        $username = $_SESSION['username'];

        $uploadDir = 'images/';
        $fileName = basename($_FILES["new_profile_picture"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["new_profile_picture"]["tmp_name"], $targetFilePath)) {
                $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE username = ?");
                $stmt->execute([$fileName, $username]);

                echo json_encode(array("success" => true, "filePath" => $targetFilePath));
                exit();
            } else {
                echo json_encode(array("success" => false, "message" => "Sorry, there was an error uploading your file."));
                exit();
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."));
            exit();
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Please select an image file to upload."));
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_job'])) {
    $jobId = $_POST['job_id'];

    // Delete job from the database
    $deleteJobQuery = mysqli_query($db, "DELETE FROM jobs WHERE job_id = '$jobId' AND posted_by = '$postedBy'");
    if ($deleteJobQuery) {
        echo json_encode(array("success" => true, "message" => "Job deleted successfully."));
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to delete job."));
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $username; ?> - Profile</title>
    <link rel="icon" type="image/x-icon" href="./logo/logo.png">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" href="./css/profile.css">
    <script>
    window.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('Overview').style.display = 'block';
    });
    </script>
</head>

<body>
    <div class="container">
        <div class="profile-header">
            <div class="profile-picture">
                <img src="./images/<?php echo $profileImage; ?>" alt="Profile Picture" id="profile_picture">
            </div>
            <div class="profile-info">
                <h1><?php echo $username; ?></h1>
                <p>OddJobs User</p>
                <button class="profile_button" id="edit_profile_button">Edit profile</button>
                <button class="profile_button" onclick="window.location.href='dashboard.php'">Dashboard</button>
                <form method="post">
                    <button name="logout" class="profile_button">Sign Out</button>
                </form>
            </div>
        </div>
        <div class="profile-body">
            <div class="tab">
                <button class="tablinks active" onclick="openTab(event, 'Overview')">Overview</button>
                <button class="tablinks" onclick="openTab(event, 'Jobs')">Jobs Posted</button>
                <button class="tablinks" onclick="openTab(event, 'JobsApplied')">Jobs Applied</button>
                <button class="tablinks" onclick="openTab(event, 'Settings')">Settings</button>
            </div>
            <div id="Overview" class="tabcontent">
                <h3>Overview</h3>
                <p>Some content about the user...</p>
            </div>
            <div id="Jobs" class="tabcontent">
                <div class="section">
                    <h2>Jobs</h2>
                    <div class="job-posts-container" id="jobPostsContainer">
                        <?php
                        if ($tjobs > 0) {
                            while ($jobsdata = mysqli_fetch_assoc($jqry)) {
                                $jid = $jobsdata['job_id'];
                                $applicationCount = $jobsdata['application_count'];
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
                                echo "<p class='application-count'><strong>Applications:</strong> " . $applicationCount . "</p>";
                                echo '<button class="delete-job-button" data-job-id="' . $jid . '">Delete</button>';
                                echo '</div>';
                            }
                        } else {
                            echo "<p>No Jobs Found</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div id="JobsApplied" class="tabcontent">
                <h2>Jobs Applied</h2>
                <div class="applied-jobs-container" id="appliedJobsContainer">
                    <?php
                        if (!empty($appliedJobs)) {
                            foreach ($appliedJobs as $job) {
                                echo '<div class="job-post">';
                                echo '<div class="job-details">';
                                echo "<h2 class='job-title'>" . $job['title'] . "</h2>";
                                echo "<p class='job-desc'>" . $job['description'] . "</p>";
                                echo "<p class='job-category'><strong>Category:</strong> " . $job['category'] . "</p>";
                                echo "<p class='job-type'><strong>Job Type:</strong> " . $job['job_type'] . "</p>";
                                echo "<p class='job-location'><strong>Location:</strong> " . $job['location'] . "</p>";
                                echo "<p class='job-mode'><strong>Mode:</strong> " . $job['work_mode'] . "</p>";
                                echo "<p class='job-posted-date'><strong>Date Posted:</strong> " . $job['date_posted'] . "</p>";
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo "<p>No Applied Jobs Found</p>";
                        }
                    ?>
                </div>
            </div>
            <div id="Settings" class="tabcontent">
                <h3>Settings</h3>
                <div class="settings">
                    <form id="profile_picture_form" enctype="multipart/form-data">
                        <label for="new_profile_picture">Change Profile Picture:</label>
                        <input type="file" id="new_profile_picture" accept="image/*" required>
                        <input type="submit" value="Upload">
                    </form>
                    <form id="profile_edit_form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <label for="new_username">Change Username:</label>
                        <input type="text" id="new_username" name="new_username" required>
                        <input type="submit" name="update_profile" value="Save Changes">
                    </form>
                    <div class="change-password">
                        <h4>Change Password</h4>
                        <form id="change_password_form">
                            <label for="current_password">Current Password:</label>
                            <input type="password" id="current_password" name="current_password" required>
                            <label for="new_password">New Password:</label>
                            <input type="password" id="new_password" name="new_password" required>
                            <input type="submit" value="Change Password">
                        </form>
                    </div>
                    <div class="delete-account">
                        <h4>Delete Account</h4>
                        <button id="delete_account_button">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        // Delete job handler
        $('.delete-job-button').click(function() {
            const jobId = $(this).data('job-id');
            const jobPostElement = $(this).closest('.job-post');

            if (confirm('Are you sure you want to delete this job?')) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
                    data: { delete_job: true, job_id: jobId },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            alert('Job deleted successfully.');
                            jobPostElement.remove();
                        } else {
                            alert('Failed to delete job.');
                        }
                    }
                });
            }
        });
    });

    document.getElementById('edit_profile_button').addEventListener('click', function() {
        document.querySelector('.tab button:nth-child(4)').click();
    });

    document.getElementById('new_profile_picture').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile_picture').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('profile_picture_form').addEventListener('submit', function(e) {
        e.preventDefault();
        var fileInput = document.getElementById('new_profile_picture');
        var file = fileInput.files[0];
        if (file) {
            var formData = new FormData();
            formData.append('new_profile_picture', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('profile_picture').src = response.filePath;
                    } else {
                        alert('Failed to update profile picture.');
                    }
                } else {
                    alert('Error occurred while updating profile picture. Please try again.');
                }
            };
            xhr.send(formData);
        }
    });

    document.getElementById('change_password_form').addEventListener('submit', function(e) {
        e.preventDefault();
        var currentPassword = document.getElementById('current_password').value;
        var newPassword = document.getElementById('new_password').value;
        console.log("Current Password:", currentPassword);
        console.log("New Password:", newPassword);
        document.getElementById('current_password').value = "";
        document.getElementById('new_password').value = "";
    });

    document.getElementById('delete_account_button').addEventListener('click', function() {
        var confirmDelete = confirm("Are you sure you want to delete your account?");
        if (confirmDelete) {
            console.log("Account deleted");
            window.location.href = "logout.php";
        }
    });

    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    </script>
</body>

</html>
