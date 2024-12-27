<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    // User is not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $title = $_POST['jobTitle'];
    $description = $_POST['jobDescription'];
    $category = $_POST['category'];
    $jobType = $_POST['jobType'];
    $location = $_POST['location'];
    $workMode = $_POST['workMode'];
    $postedBy = $_SESSION['uid']; // Get username from session
    date_default_timezone_set('Asia/Kolkata');
    $datePosted = date("Y-m-d H:i:s");// Get current date in YYYY-MM-DD format

    // Validate uploaded images
    $uploadedImages = $_FILES['jobPhotos'];
    $imagePaths = [];

    // Check if images are uploaded
    if (!empty($uploadedImages['name'][0])) {
        $uploadDir = 'uploads/'; // Directory to store uploaded images

        // Loop through uploaded images
        foreach ($uploadedImages['name'] as $key => $imageName) {
            $imageTmpName = $uploadedImages['tmp_name'][$key];
            $imageError = $uploadedImages['error'][$key];

            // Check if there is no upload error
            if ($imageError === UPLOAD_ERR_OK) {
                $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
                $imageNewName = uniqid('image_') . '.' . $imageExtension;
                $imageDestination = $uploadDir . $imageNewName;

                // Move uploaded image to the upload directory
                if (move_uploaded_file($imageTmpName, $imageDestination)) {
                    $imagePaths[] = $imageDestination;
                }
            }
        }
    }

    // Insert job data into the database
    $dbHost = 'localhost';
    $dbUser = 'root'; // Replace with your MySQL username
    $dbPass = ''; // Replace with your MySQL password
    $dbName = 'oddjobs'; // Replace with your MySQL database name

    try {
        // Create a PDO instance
        $dsn = "mysql:host=$dbHost;dbname=$dbName";
        $pdo = new PDO($dsn, $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement for inserting job data
        $sql = "INSERT INTO jobs (title, description, category, job_type, location, work_mode, posted_by, date_posted) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $description, $category, $jobType, $location, $workMode, $postedBy, $datePosted]);

        // Get the ID of the inserted job
        $jobId = $pdo->lastInsertId();

        // Insert image paths into the job_images table
        foreach ($imagePaths as $imagePath) {
            $sql = "INSERT INTO job_images (job_id, image_path) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$jobId, $imagePath]);
        }

        // Redirect to the dashboard with success message
        header('Location: dashboard.php?success=1');
        exit();
    } catch (PDOException $e) {
        echo 'Database Error: ' . $e->getMessage();
    }
} else {
    // If the form is not submitted, redirect to the dashboard
    header('Location: dashboard.php');
    exit();
}