<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OddJobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./logo/logo.png">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        /* Basic CSS for navbar */
        body {
            font-family: 'Open Sans', sans-serif;
            /* Apply Open Sans font */
            margin: 0;
            padding: 0;
            position: relative;
            /* Ensure the footer is at the bottom of the page */
        }

        .video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
            /* Cover the entire viewport */
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0);
            /* Adjusted opacity */
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            /* Set position to fixed */
            top: 0;
            /* Position it at the top */
            left: 0;
            /* Align it to the left */
            right: 0;
            /* Align it to the right */
            z-index: 1000;
            /* Ensure the navbar stays on top of other content */
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        .navbar-left {
            display: flex;
            align-items: center;
            font-family: 'Open Sans', sans-serif;
            /* Apply Open Sans font */
        }

        .navbar-logo img {
            height: 32px;
            margin-right: 10px;
            cursor: pointer;
            /* Add cursor pointer to indicate clickable */
            transition: transform 0.3s ease;
            /* Smooth transition for size increase */
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .navbar-logo img:hover {
            transform: scale(1.2);
            /* Increase size on hover */
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .navbar-left a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            transition: all 0.3s ease;
            /* Smooth transition for hover effect */
        }

        .navbar-left a:hover {
            background-color: #555;
            border-radius: 6px;
            transform: scale(1.1);
            /* Increase size on hover */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            /* Add glowing effect */
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .navbar-right input[type="text"],
        .navbar-right button {
            margin-left: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            background-color: #555;
            color: #fff;
            cursor: text;
        }

        .navbar-right input[type="text"] {
            width: 200px;
        }

        .navbar-right .login-button {
            background-color: rgba(101, 190, 38, 0.8);
            /* Change background color to green */
            font-weight: bold;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            /* Smooth transition for hover effect */
        }

        .navbar-right .login-button:hover {
            animation: glow-login 1s infinite alternate;
            transform: scale(1.1);
            /* Increase size on hover */
        }

        @keyframes glow-login {
            from {
                box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
                /* Initial glow effect */
            }

            to {
                box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
                /* Increased glow effect */
            }
        }

        .navbar-right .signup-button {
            background-color: #555;
            /* Change background color to gray */
            font-weight: bold;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            /* Smooth transition for hover effect */
        }

        .navbar-right .signup-button:hover {
            animation: glow-signup 1s infinite alternate;
            transform: scale(1.1);
            /* Increase size on hover */
        }

        @keyframes glow-signup {
            from {
                box-shadow: 0 0 10px rgba(121, 120, 120, 0.5);
                /* Initial glow effect with #555 color */
            }

            to {
                box-shadow: 0 0 20px rgba(121, 120, 120, 0.5);
                /* Increased glow effect with #555 color */
            }
        }

        /* Styles for the slider */
        .swiper-container {
            width: 100%;
            height: 660px;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            background-color: #22202000;
            /* Background color of the slider */

        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            height: 100%;
            /* Ensure slide takes full height of container */
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        .slide-content {
            background-color: rgba(0, 0, 0, 0.5);
            /* Updated background color of slide content */
            padding: 20px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
            text-align: left;
            /* Align text to the left */
        }

        .slide-content h1 {
            font-size: 100px;
            margin-bottom: 10px;
            /* Add some spacing between heading and subheading */
            font-weight: bold;
            color: #fff;
        }

        .slide-content p {
            margin-bottom: 20px;
            /* Add some spacing between subheading and button */
            color: #fff;
        }

        .slide-button {

            padding: 12px 24px;
            /* Increased padding for better button appearance */
            border: none;
            border-radius: 6px;
            background-color: #f42f2feb;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Smooth transition for background color and text color */
            display: inline-block;
            /* Make button display inline */
            font-size: 16px;
            /* Increased font size */
        }

        .slide-button:hover {
            background-color: #444;
            /* Darken button color on hover */
            color: #fff;
            /* Change text color on hover */
            transform: translateY(-2px);
            /* Add a slight lift effect on hover */
        }

        .swiper-button-prev,
        .swiper-button-next {
            color: #fcf8f848;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        .swiper-button-prev {
            left: 20px;
        }

        .swiper-button-next {
            right: 20px;
        }

        /* Styles for the grid section */
        .section-grids {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
            padding: 20px;
        }

        .grid {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .grid h2 {
            color: #fff;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .grid p {
            color: #fff;
            margin-bottom: 20px;
        }

        .grid button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            background-color: #f42f2feb;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-size: 16px;
        }

        .grid button:hover {
            background-color: #444;
            color: #fff;
            transform: translateY(-2px);
        }

        /* Footer styles */
        .footer {
            background-color: #222;
            color: #fff;
            padding: 50px 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-section {
            width: 30%;
            margin-bottom: 30px;
        }

        .footer-section h3 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .footer-section p {
            margin: 5px 0;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: #f42f2f;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 30px;
        }

        /* Scroll to Top Button */
        #scrollTopBtn {
            display: none;
            /* Hide the button by default */
            position: fixed;
            /* Fixed/sticky position */
            bottom: 20px;
            /* Place the button at the bottom */
            right: 30px;
            /* Place the button 30px from the right */
            z-index: 99;
            /* Make sure it does not overlap */
            border: none;
            /* Remove borders */
            outline: none;
            /* Remove outline */
            background-color: #555;
            /* Set a background color */
            color: white;
            /* Text color */
            cursor: pointer;
            /* Add a pointer on hover */
            padding: 5px;
            /* Some padding */
            border-radius: 50%;
            /* Rounded corners */
            font-size: 14px;
            /* Decrease font size */
            transition: background-color 0.3s ease;
            width: 30px;
            /* Adjust width */
            height: 30px;
            /* Adjust height */

        }

        #scrollTopBtn:hover {
            background-color: #444;
            /* Darken the button color on hover */
        }
    </style>
</head>

<body>

    <video class="video-background" autoplay loop muted>
        <!-- <source src="./images/background2.mp4" type="video/mp4">    -->
        <source src="https://videos.pexels.com/video-files/2611250/2611250-uhd_3840_2160_30fps.mp4" type="video/mp4">
    </video>

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

    <!-- Slider Section -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide slide1">
                <div class="slide-content">
                    <h1>Find a Suitable Job</h1>
                    <p>Browse through a variety of Job Options.</p>
                    <button class="slide-button" onclick="window.location.href='./dashboard.php'">Find Job</button>
                </div>
            </div>
            <div class="swiper-slide slide2">
                <div class="slide-content">
                    <h1>Post Your Job</h1>
                    <p>Easily post your job requirements and attract potential candidates.</p>
                    <button class="slide-button" onclick="window.location.href='./dashboard.php'">Post Job</button>
                </div>
            </div>
            <div class="swiper-slide slide3">
                <div class="slide-content">
                    <h1>Buy & Sell Products</h1>
                    <p>Explore a marketplace where you can buy and sell services.</p>
                    <button class="slide-button" onclick="window.location.href='./dashboard.php'">Buy & Sell</button>
                </div>
            </div>
        </div>
        <!-- Add Navigation Arrows -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>

    <!-- Section for grid with descriptions and buttons -->
    <section class="section-grids">
        <div class="grid">
            <h2>Find Jobs</h2>
            <p>Explore a variety of job opportunities that match your skills and interests. Whether you're seeking full-time employment, part-time gigs, or freelance projects, we've got you covered.</p>
            <button onclick="window.location.href='./dashboard.php'">Find Jobs</button>
        </div>
        <div class="grid">
            <h2>Post Jobs</h2>
            <p>Easily post your job requirements and attract potential candidates. Whether you're hiring for a specific role or looking for freelance help, our platform makes it simple to connect with talented individuals.</p>
            <button onclick="window.location.href='./dashboard.php'">Post Jobs</button>
        </div>
        <div class="grid">
            <h2>Buy & Sell</h2>
            <p>Discover a marketplace where you can buy and sell services, products, and expertise. Whether you're a freelancer offering your skills or a buyer looking for quality services, our platform facilitates seamless transactions.</p>
            <button onclick="window.location.href='./dashboard.php'">Buy & Sell</button>
        </div>
    </section>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>What we do.</h3>
                <p>we provide a place to find and post jobs.</p>
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

    <!-- Scroll to Top Button -->
    <button onclick="topFunction()" id="scrollTopBtn" title="Go to top">^</button>

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