<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - OddJobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./logo/logo.png">
    <link rel="stylesheet" href="./css/contact_style.css">
</head>

<body>
    <video class="video-background" autoplay loop muted>
        <!-- <source src="./images/background2.mp4" type="video/mp4">    -->
        <source src="https://videos.pexels.com/video-files/2611250/2611250-uhd_3840_2160_30fps.mp4" type="video/mp4">
    </video>

    <div class="navbar">
        <div class="navbar-left" id="logoContainer">
            <div class="navbar-logo">
                <img onclick="window.location.href='./index.php'" src="./logo/logo.png" alt="OddJobs">
            </div>
            <a href="./dashboard.php">Find Job</a>
            <a href="./dashboard.php">Post Job</a>
            <a href="./buy_sell.php">Buy & Sell</a>
            <a href="./contact.php">Contact</a>
            <a href="./about.php">About</a>
        </div>
        <div class="navbar-right">
            <form id="searchForm" action="#">
                <input id="searchInput" type="text" placeholder="Search OddJobs">
            </form>
            <button class="login-button" onclick="window.location.href='login.php'">Log In</button>
            <button class="signup-button" onclick="window.location.href='signup.php'">Sign Up</button>
        </div>
    </div>

    <!-- Contact Sections -->
    <div class="contact-sections">
        <!-- Contact Details Section -->
        <div class="contact-details">
            <h2>Contact Details</h2>
            <p><strong>Location:</strong> CR12, Central University Himachal Pradesh</p>
            <p><strong>Phone:</strong> +123-456-7890</p>
            <p><strong>Email:</strong> we.oddjobs.info@gmail.com</p>
            <p><strong>Instagram:</strong> @oddjobs_official</p>
            <!-- Embedding a Google Map -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2154.4287219302496!2d76.1555080678491!3d32.2245815740599!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391b5ea0362d1c07%3A0x4b75f266ab9a9cc7!2sCentral%20University%20of%20Himachal%20Pradesh%20(Shahpur%20Campus)!5e0!3m2!1sen!2sin!4v1715317158514!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <!-- Contact Form Section -->
        <div class="contact-form">
            <h2>Contact Us</h2>
            <p>Have questions or feedback? Reach out to us using the form below.</p>
            <form id="contactForm" action="send_message.php" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>What we do.</h3>
                <p>We provide a place to find and post jobs.</p>
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

    <!-- Modal Popup -->
    <div id="thankYouModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('thankYouModal')">&times;</span>
            <h2>Thank You!</h2>
            <p>Your message has been sent successfully. We will get back to you soon.</p>
        </div>
    </div>
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('errorModal')">&times;</span>
            <h2>Error</h2>
            <p>There was a problem sending your message. Please try again later.</p>
        </div>
    </div>

    <script>
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        document.getElementById('contactForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var form = event.target;
            var formData = new FormData(form);

            fetch('send_message.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('thankYouModal').style.display = 'block';
                    } else {
                        document.getElementById('errorModal').style.display = 'block';
                    }
                })
                .catch(error => {
                    document.getElementById('errorModal').style.display = 'block';
                });
        });

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
    </script>
</body>

</html>