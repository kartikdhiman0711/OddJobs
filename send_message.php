<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to PHPMailer autoload file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    // Check if fields are not empty
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Recipient email address
        $recipient = "we.oddjobs.info@gmail.com";

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your SMTP server hostname
            $mail->SMTPAuth = true;
            $mail->Username = 'we.oddjobs.info@gmail.com'; // Your SMTP username
            $mail->Password = 'lorb sbhs saga vjke'; // Your SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            // Sender and recipient
            $mail->setFrom('we.oddjobs.info@gmail.com', 'OddJobs Contact');
            $mail->addAddress($recipient);

            // Email content
            $mail->Subject = "New Contact Message from $name";
            $mail->Body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

            // Send email
            $mail->send();

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
