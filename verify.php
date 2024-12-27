<?php
// Establishing connection to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$database = "oddjobs";

$conn = new mysqli($servername, $username, $password, $database);

// Checking connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if token is set
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found
        $row = $result->fetch_assoc();
        $update_query = "UPDATE users SET verified = 1, token = '' WHERE token = '$token'";

        if ($conn->query($update_query) === TRUE) {
            echo "<script>alert('Email verified successfully!'); window.location.href = 'login.php';</script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "<script>alert('Invalid token or already verified.'); window.location.href = 'signup.php';</script>";
    }

    $stmt->close();
}

// Closing connection
$conn->close();
