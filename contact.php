<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buff Budgets - Contact Us</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

<style>


</style>

</head>

<body>

<nav class="contact-navbar">
    <div class="index-logo">
        <a href="index.html"><img src="logo.png"></a>
    </div>
    <ul class="index-nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="contact.php">Contact Us</a></li>
    </ul>
</nav>

<div class="contact-wrapper">

    <div class="contact-header">
        <h1>Contact Our Team</h1>
        <p>If you have any problems or questions, get in touch and we’ll respond as soon as possible.</p>
    </div>

    <div class="contact-grid">

        <div class="contact-card contact-form">
            <h2>Send Us a Message</h2>
            <p>Fill out the form below and we'll get back to you.</p>

            <form action="#" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>

        <div class="contact-card contact-info">
            <h2>Contact Details</h2>
            <p><strong>Address:</strong> Buff Budgets, 123 Sheffield Road</p>
            <p><strong>Phone:</strong> (01321) 2340 235</p>
            <p><strong>Fax:</strong> (01321) 2340 236</p>
            <p><strong>Email:</strong> <a href="mailto:info@buffbudgets.com">info@buffbudgets.com</a></p>
        </div>

    </div>

</div>

<footer class="index-footer">
    <div class="index-footer-container">
        <div class="index-footer-column">
            <img src="logo.png" class="footer-logo">
            <p>© 2026 Buff Budgets. All rights reserved.</p>
        </div>

        <div class="index-footer-column">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.html">Dashboard</a></li>
            </ul>
        </div>

        <div class="index-footer-column">
            <h4>Contact Us</h4>
            <p>Tel: (01321) 2340 235</p>
            <p>Email: info@buffbudgets.com</p>
        </div>
    </div>
</footer>

</body>
</html>