<?php
session_start();
include("config.php");
if(isset($_POST['contact_submit'])){

$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$sql = "INSERT INTO contact_messages(name,email,subject,message)
VALUES('$name','$email','$subject','$message')";

if(mysqli_query($conn,$sql)){
   echo "<script>alert('Message Sent Successfully');</script>";
}else{
   echo "Error";
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Eventify | Smart Event Management Platform</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ========== HEADER / NAVIGATION ========== -->
    <header class="site-header">
        <div class="container header-container">
            <div class="logo-area">
                <a href="#" class="logo">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Eventify</span>
                </a>
            </div>

            <!-- Hamburger for mobile -->
            <div class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </div>

            <!-- Navigation & Auth -->
            <nav class="main-nav" id="mainNav">
                <ul class="nav-links">
                    <li><a href="#" class="active">Home</a></li>
                     <li><a href="#featured-events" id="navEvents">Events</a></li>
                    <li><a href="#marketplace" id="navMarketplace">Marketplace</a></li>
                    <li><a href="#contact" id="navContact">Contact</a></li>
                    
                </ul>
                <div class="auth-buttons">
                    <a href="login.php" class="btn-outline" id="loginBtn">Login</a>
                    <a href="register.php" class="btn-primary" id="registerBtn">Register</a>
                    <a href="admin/admin-login.php" class="btn-admin" id="adminBtn">Admin</a>
                </div>
               



            </nav>
        </div>
    </header>

    <main>
        <!-- ========== HERO SECTION (Landing) ========== -->
        <section class="hero-section">
            <div class="container hero-grid">
                <div class="hero-content">
                    <span class="hero-badge">✨ Smart Event Ecosystem</span>
                    <h1>Create, Promote & Manage <span class="gradient-text">Unforgettable Events</span></h1>
                    <p>From weddings to tech conferences, birthdays to corporate meets — Eventify brings organizers, vendors, and attendees together on one intelligent platform.</p>
                    <div class="hero-buttons">
                        <a href="create-event.php" class="btn-large btn-primary" id="createEventBtn">
                            <i class="fas fa-plus-circle"></i> Create Event
                        </a>
                       <a href="explore-events.php" class="btn-large btn-outline-dark"><i class="fas fa-search"></i> Explore Events</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item"><span>500+</span> Events Hosted</div>
                        <div class="stat-item"><span>12k+</span> Happy Attendees</div>
                        <div class="stat-item"><span>280+</span> Verified Vendors</div>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="floating-card card-1">
                        <i class="fas fa-ticket-alt"></i> <span>QR Smart Ticket</span>
                    </div>
                    <div class="floating-card card-2">
                        <i class="fas fa-video"></i> <span>Live Stream</span>
                    </div>
                    <div class="floating-card card-3">
                        <i class="fas fa-chart-line"></i> <span>Real Analytics</span>
                    </div>
                    <div class="hero-illustration">
                        <img src="images/pic-1.jpeg" alt="Event management illustration" class="illustration-img">
                    </div>
                </div>
            </div>
        </section>


        <!-- ========== ABOUT US SECTION ========== -->
<section class="about-section" id="about">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag"><i class="fas fa-info-circle"></i> About Eventify</span>
            <h2>Empowering Events,<br>Creating Memories</h2>
            <p>We're on a mission to simplify event management for everyone</p>
        </div>

        <div class="about-grid">
            <div class="about-content">
                <h3>Our Story</h3>
                <p>Founded in 2023, Eventify started with a simple idea: event planning should be seamless, smart, and stress-free. From intimate birthday parties to large-scale conferences, we provide a unified platform where organizers, vendors, and attendees connect effortlessly.</p>
                <p>Today, we've helped host <strong>500+ events</strong> across the country, partnered with <strong>280+ trusted vendors</strong>, and brought joy to <strong>12,000+ attendees</strong>. Our all-in-one solution handles ticketing, vendor hiring, live streaming, and analytics – so you can focus on what matters most: creating unforgettable experiences.</p>
                <div class="about-stats">
                    <div class="about-stat">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Events Hosted</span>
                    </div>
                    <div class="about-stat">
                        <span class="stat-number">280+</span>
                        <span class="stat-label">Vendors</span>
                    </div>
                    <div class="about-stat">
                        <span class="stat-number">12k+</span>
                        <span class="stat-label">Happy Attendees</span>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <img src="images/pic-14.webp" alt="About Eventify">
            </div>
        </div>

        <div class="features-list">
            <div class="feature-item">
                <i class="fas fa-ticket-alt"></i>
                <h4>Smart Ticketing</h4>
                <p>QR codes, online payments, PDF tickets</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-handshake"></i>
                <h4>Vendor Marketplace</h4>
                <p>Photographers, caterers, decorators & more</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-chart-line"></i>
                <h4>Real Analytics</h4>
                <p>Sales reports, attendee insights</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-video"></i>
                <h4>Live Stream</h4>
                <p>Virtual event integration</p>
            </div>
        </div>
    </div>
</section>
        <!-- ========== UPCOMING FEATURED EVENTS SECTION ========== -->
        <section class="featured-events" id="featured-events">
            <div class="container">
                <div class="section-header">
                    <div>
                        <span class="section-tag">🔥 Don't Miss Out</span>
                        <h2>Upcoming Featured Events</h2>
                        <p>Handpicked experiences — from exclusive galas to music festivals</p>
                    </div>
                    <a href="explore-events.php" class="btn-large btn-outline-dark">View all events <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="events-grid" id="eventsGrid">
                    <!-- Event Card 1 -->
                    <div class="event-card">
                        <div class="event-img">
                            <img src="images/pic-5.jpg" alt="Wedding Expo">
                            <span class="event-badge">Featured</span>
                        </div>
                        <div class="event-info">
                            <div class="event-category"><i class="fas fa-ring"></i> Wedding</div>
                            <h3>Grand Wedding Expo 2025</h3>
                            <div class="event-meta">
                                <span><i class="fas fa-calendar-day"></i> May 15, 2025</span>
                                <span><i class="fas fa-map-marker-alt"></i> Downtown Grand Hall</span>
                            </div>
                            <div class="event-footer">
                                <div class="price">Starting at <strong>$49</strong></div>
                                <a href="book-ticket.php?id=1001" class="btn-sm btn-primary">Get Tickets →</a>
                            </div>
                        </div>
                    </div>
                    <!-- Event Card 2 -->
                    <div class="event-card">
                        <div class="event-img">
                            <img src="images/pic-6.webp" alt="Summer Music Fest">
                            <span class="event-badge">Almost Sold Out</span>
                        </div>
                        <div class="event-info">
                            <div class="event-category"><i class="fas fa-music"></i> Concert</div>
                            <h3>Summer Beats Music Festival</h3>
                            <div class="event-meta">
                                <span><i class="fas fa-calendar-day"></i> June 28, 2025</span>
                                <span><i class="fas fa-map-marker-alt"></i> Riverside Park</span>
                            </div>
                            <div class="event-footer">
                                <div class="price">From <strong>$79</strong></div>
                                <a href="book-ticket.php?id=1002" class="btn-sm btn-primary">Get Tickets →</a>
                            </div>
                        </div>
                    </div>
                    <!-- Event Card 3 -->
                    <div class="event-card">
                        <div class="event-img">
                            <img src="images/pic-7.avif" alt="Tech Summit">
                            <span class="event-badge">Early Bird</span>
                        </div>
                        <div class="event-info">
                            <div class="event-category"><i class="fas fa-microchip"></i> Conference</div>
                            <h3>Global Tech Summit 2025</h3>
                            <div class="event-meta">
                                <span><i class="fas fa-calendar-day"></i> July 10, 2025</span>
                                <span><i class="fas fa-map-marker-alt"></i> City Convention Center</span>
                            </div>
                            <div class="event-footer">
                                <div class="price">From <strong>$149</strong></div>
                                <a href="book-ticket.php?id=1003" class="btn-sm btn-primary">Get Tickets →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== CATEGORIES SECTION (Wedding, Seminar, Music, Sports, etc) ========== -->
        <section class="categories-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-tag">Browse by Category</span>
                    <h2>Explore events by interest</h2>
                    <p>Find the perfect experience — weddings, conferences, parties & more</p>
                </div>
                <div class="categories-grid">
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-heart"></i></div>
                        <h3>Wedding</h3>
                        <p>Extravagant ceremonies & receptions</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-chalkboard-user"></i></div>
                        <h3>Seminar</h3>
                        <p>Workshops & educational talks</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-guitar"></i></div>
                        <h3>Music</h3>
                        <p>Concerts, festivals & live bands</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-futbol"></i></div>
                        <h3>Sports</h3>
                        <p>Tournaments & athletic events</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-birthday-cake"></i></div>
                        <h3>Birthday</h3>
                        <p>Party planning & celebrations</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon"><i class="fas fa-building"></i></div>
                        <h3>Corporate</h3>
                        <p>Meetings & networking events</p>
                    </div>
                </div>
            </div>
        </section>
    </main>



    <!-- ========== VENDOR MARKETPLACE SECTION ========== -->
<section class="marketplace-section" id="marketplace">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag"><i class="fas fa-store"></i> Trusted Partners</span>
            <h2>Hire Top-Rated Vendors</h2>
            <p>Photographers, caterers, decorators & more – make your event perfect</p>
        </div>

        <div class="vendors-grid">
            <!-- Vendor 1: Photographer -->
            <div class="vendor-card">
                <div class="vendor-img">
                    <img src="images/pic-10.jpg" alt="Photographer">
                </div>
                <div class="vendor-info">
                    <h3><i class="fas fa-camera"></i> ShutterMagic Photography</h3>
                    <div class="vendor-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        <span>4.8 (124 reviews)</span>
                    </div>
                    <p>Professional event photography, candid shots, pre-wedding shoots.</p>
                    <div class="vendor-meta">
                        <span><i class="fas fa-tag"></i> Starting at $299</span>
                        <span><i class="fas fa-clock"></i> 3+ years exp</span>
                    </div>
                    <!-- Example for Photographer -->
                    <a href="hire-vendor.php?id=vendor1" class="btn-hire">Hire Now →</a>
                </div>
            </div>

            <!-- Vendor 2: Caterer -->
            <div class="vendor-card">
                <div class="vendor-img">
                    <img src="images/pic-11.jpg" alt="Caterer">
                </div>
                <div class="vendor-info">
                    <h3><i class="fas fa-utensils"></i> Gourmet Delights Catering</h3>
                    <div class="vendor-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <span>5.0 (89 reviews)</span>
                    </div>
                    <p>Multi-cuisine catering, buffet setup, live food stations.</p>
                    <div class="vendor-meta">
                        <span><i class="fas fa-tag"></i> Starting at $15/person</span>
                        <span><i class="fas fa-clock"></i> 500+ events</span>
                    </div>
                    <!-- Example for Photographer -->
                    <a href="hire-vendor.php?id=vendor2" class="btn-hire">Hire Now →</a>
                </div>
            </div>

            <!-- Vendor 3: Decorator -->
            <div class="vendor-card">
                <div class="vendor-img">
                    <img src="images/pic-12.jpg" alt="Decorator">
                </div>
                <div class="vendor-info">
                    <h3><i class="fas fa-palette"></i> Elegant Decorators</h3>
                    <div class="vendor-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <span>4.9 (210 reviews)</span>
                    </div>
                    <p>Floral arrangements, themed decor, lighting, stage setup.</p>
                    <div class="vendor-meta">
                        <span><i class="fas fa-tag"></i> Starting at $499</span>
                        <span><i class="fas fa-clock"></i> 8+ years exp</span>
                    </div>
                    <!-- Example for Photographer -->
                    <a href="hire-vendor.php?id=vendor3" class="btn-hire">Hire Now →</a>
                </div>
            </div>

            <!-- Vendor 4: DJ / Sound System -->
            <div class="vendor-card">
                <div class="vendor-img">
                    <img src="images/pic-13.jpg" alt="DJ">
                </div>
                <div class="vendor-info">
                    <h3><i class="fas fa-music"></i> BeatBlast DJ & Sound</h3>
                    <div class="vendor-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        <span>4.7 (67 reviews)</span>
                    </div>
                    <p>Professional DJ, high-quality sound system, lighting effects.</p>
                    <div class="vendor-meta">
                        <span><i class="fas fa-tag"></i> Starting at $399</span>
                        <span><i class="fas fa-clock"></i> 200+ events</span>
                    </div>
                    <!-- Example for Photographer -->
                    <a href="hire-vendor.php?id=vendor4" class="btn-hire">Hire Now →</a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========== CONTACT US SECTION ========== -->
<section class="contact-section" id="contact">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag"><i class="fas fa-envelope"></i> Get in Touch</span>
            <h2>Contact Us</h2>
            <p>Have questions? We'd love to hear from you.</p>
        </div>

        <div class="contact-grid">
            <div class="contact-info">
                <div class="info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Visit Us</h3>
                    <p>GEC<br>Chittagong, Bangladesh</p>
                </div>
                <div class="info-card">
                    <i class="fas fa-phone-alt"></i>
                    <h3>Call Us</h3>
                    <p>+ (880) 123-4567<br>Mon-Fri, 9am-6pm</p>
                </div>
                <div class="info-card">
                    <i class="fas fa-envelope"></i>
                    <h3>Email Us</h3>
                    <p>support@eventify.com<br>info@eventify.com</p>
                </div>
            </div>

            <div class="contact-form">
                <form method="POST" action="">

                    <div class="form-row">
                        <input type="text" name="name" placeholder="Your Name" required>
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>

                    <input type="text" name="subject" placeholder="Subject" required>

                    <textarea name="message" rows="5" placeholder="Your Message..." required></textarea>

                    <button type="submit" name="contact_submit" class="btn-submit">
                    Send Message
                    </button>

                    </form>
            </div>
        </div>
    </div>
</section>
    
<!-- ========== BEAUTIFUL FOOTER ========== -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Column 1: Brand -->
            <div class="footer-col">
                <div class="footer-logo">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Eventify</span>
                </div>
                <p class="footer-description">
                    Smart Event Management Platform. Create, promote & manage unforgettable events — weddings, conferences, concerts & more.
                </p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Upcoming Events</a></li>
                    <li><a href="#">Vendor Marketplace</a></li>
                    <li><a href="#">How It Works</a></li>
                    <li><a href="#">Support Center</a></li>
                </ul>
            </div>

            <!-- Column 3: For Users -->
            <div class="footer-col">
                <h3>For Users</h3>
                <ul class="footer-links">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register as Organizer</a></li>
                    <li><a href="register.php">Become a Vendor</a></li>
                    <li><a href="#">My Bookings</a></li>
                    <li><a href="#">Submit Event</a></li>
                </ul>
            </div>

            <!-- Column 4: Newsletter & Contact -->
            <div class="footer-col">
                <h3>Stay Updated</h3>
                <p>Subscribe to get latest events & offers</p>
                <form class="newsletter-form" id="footerNewsletterForm">
                    <input type="email" placeholder="Your email address" required>
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
                <div class="footer-contact">
                    <p><i class="fas fa-phone-alt"></i> +1 (555) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> hello@eventify.com</p>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Eventify — Smart Event Management Platform. All rights reserved.</p>
            <div class="bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Settings</a>
            </div>
        </div>
    </div>
</footer>

    <script src="script.js"></script>
</body>
</html>