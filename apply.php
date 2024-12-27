<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['uid'])) {
    echo "<script>alert('You must be logged in to apply for a job.'); window.location.href = 'dashboard.php';</script>";
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['uid'];
$servername = "localhost";
$dbusername = "root";
$password = "";
$database = "oddjobs";

// Database connection
$db = mysqli_connect($servername, $dbusername, $password, $database);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$job_id = isset($_POST['apply_job_id']) ? (int)$_POST['apply_job_id'] : 0;
$description = isset($_POST['description']) ? mysqli_real_escape_string($db, $_POST['description']) : '';
$bid_amount = isset($_POST['bid_amount']) ? (float)$_POST['bid_amount'] : 0.00;
$currency = isset($_POST['currency']) ? mysqli_real_escape_string($db, $_POST['currency']) : '';
$days = isset($_POST['days']) ? (int)$_POST['days'] : 0;

// Validate job ID
if ($job_id <= 0) {
    echo "<script>alert('Invalid job ID.'); window.location.href = 'dashboard.php';</script>";
    exit();
}

// Fetch the job details to verify the job exists and who posted it
$job_qry = mysqli_query($db, "SELECT posted_by FROM jobs WHERE job_id = $job_id");
if (!$job_qry || mysqli_num_rows($job_qry) == 0) {
    echo "<script>alert('Invalid job ID.'); window.location.href = 'dashboard.php';</script>";
    exit();
}

$job_data = mysqli_fetch_assoc($job_qry);
$posted_by = $job_data['posted_by'];

// Check if the logged-in user is the one who posted the job
if ($posted_by == $user_id) {
    echo "<script>alert('You cannot apply for a job you posted.'); window.location.href = 'dashboard.php';</script>";
    exit();
}

// Check if the user has already applied for this job
$check_qry = mysqli_query($db, "SELECT * FROM job_applications WHERE job_id = $job_id AND user_id = $user_id");
if (mysqli_num_rows($check_qry) > 0) {
    echo "<script>alert('You have already applied for this job.'); window.location.href = 'dashboard.php';</script>";
    exit();
}

// Insert the application into the job_applications table
$apply_qry = mysqli_query($db, "INSERT INTO job_applications (job_id, user_id, description, bid_amount, currency, days) VALUES ($job_id, $user_id, '$description', $bid_amount, '$currency', $days)");
if ($apply_qry) {
    // Job application successful
    mysqli_close($db); // Close database connection
    echo "<script>alert('You have successfully applied for the job.'); window.location.href = 'dashboard.php';</script>";
    exit();
} else {
    // Error applying for the job
    echo "<script>alert('Error applying for the job: " . mysqli_error($db) . "'); window.location.href = 'dashboard.php';</script>";
    exit();
}
?>
