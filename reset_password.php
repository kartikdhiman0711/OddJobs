<?php
session_start();

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

$msg = "";
$error = "";

// Check if user is already logged in
if (isset($_SESSION['Email_Session'])) {
    header("Location: dashboard.php");
    die();
}

// Process password reset
if (isset($_GET['Reset'])) {
    $resetCode = mysqli_real_escape_string($conn, $_GET['Reset']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->bind_param("s", $resetCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if (isset($_POST['submit'])) {
            $password = mysqli_real_escape_string($conn, $_POST['Password']);
            $confirmPassword = mysqli_real_escape_string($conn, $_POST['Conf-Password']);

            // Check if passwords match
            if ($password === $confirmPassword) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE users SET Password = ? WHERE token = ?");
                $stmt->bind_param("ss", $hashedPassword, $resetCode);
                $result = $stmt->execute();

                if ($result) {
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $msg = "<div class='error-message alert alert-danger'>An error occurred while updating your password. Please try again.</div>";
                }
            } else {
                $msg = "<div class='error-message alert alert-danger'>Password and Confirm Password do not match</div>";
                $error = 'style="border: 1px solid red; box-shadow: 0px 1px 11px 0px red"';
            }
        }
    } else {
        header("Location: forgot_password.php");
        exit();
    }
    $stmt->close();
} else {
    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - OddJobs</title>
    <link rel="stylesheet" href="./css/login_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .error-message {
            color: red;
        }

        .login-container input[type="text"] {
            width: calc(100% - 40px);
            margin: 10px 0;
            /* Adjusted margin */
            padding: 10px;
            border: none;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            box-sizing: border-box;
        }

        /* Style for modal popup */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #04061a;
            color: #fff;
            margin: 2% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .psfield {
            position: relative;
        }

        .login-container input[type="checkbox"],
        .checkbox {
            position: absolute;
            top: 17px;
            right: 30px;
        }

        .close:hover,
        .close:focus {
            color: #858588;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header class="login-header">
        <a href="index.php">
            <img class="logo" src="./logo/logo.png" alt="OddJobs Logo">
        </a>
        <h2 class="login-text">Change Password</h2>
    </header>
    <div class="login-container">
        <form action="" method="POST">
            <div class="psfield">
                <input type="password" name="Password" placeholder="Enter your new password" id="password" required <?php echo $error; ?>>
                <i class="fa fa-eye checkbox" id="togglePassword1" aria-hidden="true"></i>
            </div>
            <div class="psfield">
                <input type="password" name="Conf-Password" placeholder="Confirm your new password" id="confirm-password" required <?php echo $error; ?>>
                <i class="fa fa-eye checkbox" id="togglePassword2" aria-hidden="true"></i>
            </div>
            <button type="submit" name="submit">Change Password</button>
        </form>
        <?php if (isset($msg)) : ?>
            <div class="alert"><?php echo $msg; ?></div>
        <?php endif; ?>
    </div>
    <footer class="footer">
        <div class="footer-links">
            <a href="#" id="termsLink" onclick="openModal('termsModal')">Terms</a>
            <a href="#" id="privacyLink" onclick="openModal('privacyModal')">Privacy</a>
            <a href="./contact.php">Contact OddJobs</a>
            <a href="./about.php">About</a>
        </div>
    </footer>

    <!-- Modal popup for Terms -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('termsModal')">&times;</span>
            <h2>Terms and Conditions</h2>
            <p>1. Introduction</p>
            <p>Welcome to OddJobs ("the Website"). These Terms and Conditions ("Terms") govern your access to and use of
                the Website, including any content, functionality, and services offered on or through the Website. By
                using the Website, you accept and agree to be bound and abide by these Terms. If you do not agree to
                these Terms, you must not access or use the Website.</p>
            <p>2. Eligibility</p>
            <p>You must be at least 18 years old to use the Website. By using the Website, you represent and warrant
                that you are of legal age to form a binding contract with us and meet all of the foregoing eligibility
                requirements. If you do not meet all of these requirements, you must not access or use the Website.</p>
            <p>3. Account Registration</p>
            <p>To access certain features of the Website, you may be required to register for an account. When you
                register for an account, you agree to provide accurate, current, and complete information about yourself
                as prompted by the registration form. You are responsible for maintaining the confidentiality of your
                account and password and for restricting access to your computer. You agree to accept responsibility for
                all activities that occur under your account or password.</p>
            <p>4. Job Postings and Applications</p>
            <p>4.1 Job Postings: Employers may post job opportunities on the Website. Job postings must comply with all
                applicable laws, including non-discrimination and employment laws. We reserve the right to remove any
                job posting that violates these Terms or any applicable law.</p>
            <p>4.2 Job Applications: Job seekers may apply for job opportunities through the Website. By applying for a
                job, you agree to provide accurate and complete information about yourself. We do not guarantee that any
                job application will result in employment.</p>
            <p>5. Prohibited Activities</p>
            <p>You agree not to use the Website for any unlawful or prohibited purpose. You agree not to:</p>
            <ul>
                <li>Post any false or misleading job opportunities or applications.</li>
                <li>Impersonate any person or entity or otherwise misrepresent your affiliation with a person or entity.
                </li>
                <li>Use the Website to harass, abuse, or harm another person.</li>
                <li>Engage in any activity that could disable, overburden, or impair the proper working of the Website.
                </li>
                <li>Use any robot, spider, or other automatic device, process, or means to access the Website for any
                    purpose.</li>
            </ul>
            <p>6. Intellectual Property Rights</p>
            <p>The Website and its entire contents, features, and functionality (including but not limited to all
                information, software, text, displays, images, video, and audio, and the design, selection, and
                arrangement thereof) are owned by us, our licensors, or other providers of such material and are
                protected by copyright, trademark, patent, trade secret, and other intellectual property or proprietary
                rights laws. You may not reproduce, distribute, modify, create derivative works of, publicly display,
                publicly perform, republish, download, store, or transmit any of the material on our Website.</p>
            <p>7. Disclaimer of Warranties</p>
            <p>The Website is provided on an "as is" and "as available" basis. We make no representations or warranties
                of any kind, express or implied, as to the operation of the Website or the information, content, or
                materials included on the Website. You expressly agree that your use of the Website is at your sole
                risk.</p>
        </div>
    </div>

    <!-- Modal popup for Privacy Policy -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('privacyModal')">&times;</span>
            <h2>Privacy Policy</h2>
            <p>This Privacy Policy describes how OddJobs ("we", "us", or "our") collects, uses, and protects the
                information you provide when you use our website. Your privacy is important to us, and we are committed
                to protecting your personal information.</p>
            <p>Information We Collect</p>
            <p>We may collect personal information that you voluntarily provide to us when you use our website. This may
                include your name, email address, and any other information you choose to provide.</p>
            <p>We may also automatically collect certain information about your visit to our website, such as your IP
                address, browser type, operating system, and browsing activity.</p>
            <p>How We Use Your Information</p>
            <p>We may use the information we collect for various purposes, including:</p>
            <ul>
                <li>To provide and maintain our website;</li>
                <li>To improve, personalize, and enhance your experience;</li>
                <li>To communicate with you, including responding to your inquiries and providing customer support;</li>
                <li>To analyze how our website is used and to monitor and prevent fraud and abuse.</li>
            </ul>
            <p>Sharing Your Information</p>
            <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your
                consent. However, we may share your information with trusted third-party service providers who assist us
                in operating our website or conducting our business.</p>
            <p>Security</p>
            <p>We take the security of your personal information seriously and take reasonable measures to protect it
                from loss, misuse, or unauthorized access.</p>
            <p>Cookies</p>
            <p>We may use cookies and similar tracking technologies to track the activity on our website and hold
                certain information. You have the option to accept or refuse these cookies and know when a cookie is
                being sent to your computer.</p>
            <p>Links to Other Websites</p>
            <p>Our website may contain links to third-party websites that are not operated by us. We have no control
                over and assume no responsibility for the content, privacy policies, or practices of any third-party
                sites or services.</p>
            <p>Changes to This Privacy Policy</p>
            <p>We may update our Privacy Policy from time to time. Any changes we make to our Privacy Policy will be
                posted on this page.</p>
            <p>Contact Us</p>
            <p>If you have any questions or concerns about our Privacy Policy, please contact us at [contact email
                address].</p>
        </div>
    </div>

    <script>
        // Function to open modal
        function openModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = "block";
        }

        // Function to close modal
        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            var modals = document.getElementsByClassName("modal");
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = "none";
                }
            }
        }

        document.getElementById('togglePassword1').addEventListener('click', function() {
            var passwordField = document.getElementById('password');
            var confirmPasswordField = document.getElementById('confirm-password');
            var eye1 = document.getElementById('togglePassword1');
            var eye2 = document.getElementById('togglePassword2');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                confirmPasswordField.type = 'text';
                eye1.classList.remove('fa-eye');
                eye1.classList.add('fa-eye-slash');
                eye2.classList.remove('fa-eye');
                eye2.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                confirmPasswordField.type = 'password';
                eye1.classList.remove('fa-eye-slash');
                eye1.classList.add('fa-eye');
                eye2.classList.remove('fa-eye-slash');
                eye2.classList.add('fa-eye');
            }
        });

        document.getElementById('togglePassword2').addEventListener('click', function() {
            var passwordField = document.getElementById('password');
            var confirmPasswordField = document.getElementById('confirm-password');
            var eye1 = document.getElementById('togglePassword1');
            var eye2 = document.getElementById('togglePassword2');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                confirmPasswordField.type = 'text';
                eye1.classList.remove('fa-eye');
                eye1.classList.add('fa-eye-slash');
                eye2.classList.remove('fa-eye');
                eye2.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                confirmPasswordField.type = 'password';
                eye1.classList.remove('fa-eye-slash');
                eye1.classList.add('fa-eye');
                eye2.classList.remove('fa-eye-slash');
                eye2.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>