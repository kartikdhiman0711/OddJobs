<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - OddJobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./logo/logo.png">
    <link rel="stylesheet" href="css/about_style.css">
</head>

<body>

    <div class="navbar">
        <div class="navbar-left" id="logoContainer">
            <div class="navbar-logo">
                <img src="./logo/logo.png" alt="OddJobs">
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

    <div class="about-us-container">
        <div class="about-us-text">
            <h2>OddJobs</h2>
            <p>OddJobs is a leading platform that connects job seekers with employers, and facilitates transactions between buyers and sellers. Our mission is to make the job search process easier and more efficient for everyone involved.</p>
            <p>Whether you're looking for full-time employment, part-time gigs, or freelance projects, OddJobs has you covered. Our user-friendly interface allows you to browse through a variety of job opportunities and easily apply to positions that match your skills and interests.</p>
            <p>For employers, OddJobs provides a convenient platform to post job listings and attract qualified candidates. We offer customizable job posting options and tools to streamline the hiring process.</p>
            <p>In addition to job search and recruitment services, OddJobs also features a marketplace where users can buy and sell services, products, and expertise. Whether you're a freelancer offering your skills or a buyer looking for quality services, our platform offers a secure and efficient marketplace for transactions.</p>
        </div>
        <div class="about-us-image">
            <img src="./images/about2.jpeg" alt="About Us Image">
        </div>
    </div>

    <div class="our-story-container">
        <div class="our-story-image">
            <img src="./images/about1.jpeg" alt="Our Story Image">
        </div>
        <div class="our-story-text">
            <h2>Story</h2>
            <p>Founded in 2023 by a team of passionate entrepreneurs, OddJobs has quickly grown into one of the most trusted job search platforms on the internet. Our journey began with a simple idea: to create a platform that would revolutionize the way people find and apply for jobs.</p>
            <p>Over the years, we've worked tirelessly to improve and expand our platform, incorporating feedback from users and implementing innovative features to meet the evolving needs of the job market.</p>
            <p>Today, OddJobs serves millions of users worldwide, connecting job seekers with employers and helping businesses find the talent they need to succeed. Our commitment to excellence and dedication to our users remain as strong as ever, and we look forward to continuing our journey of innovation and growth.</p>
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
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            pagination: {
                el: '.swiper-pagination',
                clickable: true // Enable clickable pagination dots
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 2000, // 2 seconds delay between slides
            },
            loop: true, // Enable loop mode
            grabCursor: true, // Enable grab cursor for swiper container
            touchEventsTarget: 'container', // Enable touch events for swiper container
        });

        // Add click event listener to logo container
        document.getElementById('logoContainer').addEventListener('click', function() {
            window.location.href = 'index.php'; // Change URL to your desired link
        });

        // Get the button
        var mybutton = document.getElementById("scrollTopBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction()
        };

        function toggleScrollTopButton() {
            var scrollTopButton = document.getElementById("scrollTopBtn");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollTopButton.style.display = "block";
            } else {
                scrollTopButton.style.display = "none";
            }
        }

        // Function to scroll to the top of the document when the button is clicked
        function scrollToTop() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE, and Opera
        }

        // Event listener to toggle the display of the scroll-to-top button on scroll
        window.addEventListener("scroll", function() {
            toggleScrollTopButton();
        });


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
    </script>
</body>

</html>