<?php
session_start();
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>AgriPakistan</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/logo.jpeg" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Marcellus:wght@400&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body class="index-page">
<header id="header" class="header d-flex align-items-center">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <img src="assets/img/logo.jpeg" alt="AgriCulture">
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
            <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="about.html" class="<?php echo basename($_SERVER['PHP_SELF']) === 'about.html' ? 'active' : ''; ?>">About Us</a></li>
            <li><a href="services.html" class="<?php echo basename($_SERVER['PHP_SELF']) === 'services.html' ? 'active' : ''; ?>">Our Services</a></li>

                <li class="dropdown">
                    <a href="#"><span>Resources for Farmers</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <!-- Marketplace (Visible to All) -->
                        <li class="dropdown">
                            <a href="#"><span>Marketplace</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="products.php">Browse Products</a></li>
                                <li><a href="bulk_orders.html">Bulk Orders</a></li>
                            </ul>
                        </li>

                        <!-- Farmer's Corner (Visible to Sellers Only) -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') : ?>
                        <li class="dropdown">
                            <a href="#"><span>Farmer's Corner</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="add_products.php">Add Your Product</a></li>
                                <li><a href="manage_listings.php">Manage Listings</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>

                        <!-- Support (Visible to All) -->
                        <li class="dropdown">
                            <a href="#"><span>Support</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="help_center.html">Help Center</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li><a href="contact.html">Contact</a></li>

                <!-- Authentication Links -->
                <?php if (!isset($_SESSION['user_id'])) : ?>
                <li><a href="#" onclick="showRegisterPopup()">Register</a></li>
                <li><a href="#" onclick="showLoginPopup()">Login</a></li>
                <?php else : ?>
                <li><a href="buyer_profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
        </nav>
    </div>
</header>


  
<!-- Registration Popup -->
<div id="registerPopup" class="popup" style="display: none;">
  <div class="popup-content">
    <span class="close" onclick="closeRegisterPopup()">&times;</span>
    <h2 id="registerTitle">Register</h2>
    <form id="registerForm">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" minlength="8" required>
      </div>
      <div class="mb-3">
        <label for="contact" class="form-label">Contact Number</label>
        <input type="tel" id="contact" name="contact" class="form-control" placeholder="Enter your contact number" required>
      </div>
      <div class="mb-3">
        <label for="dob" class="form-label">Date of Birth</label>
        <input type="date" id="dob" name="dob" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea id="address" name="address" class="form-control" placeholder="Enter your address" required></textarea>
      </div>
      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select id="role" name="role" class="form-select" required>
          <option value="buyer">Buyer</option>
          <option value="seller">Seller</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
    <div id="signupMessage" style="margin-top: 10px;"></div>
  </div>
</div>


<!-- Login Popup -->
<div id="loginPopup" class="popup" style="display: none;">
  <div class="popup-content">
    <span class="close" onclick="closeLoginPopup()">&times;</span>
    <h2>Login</h2>
    <form id="loginForm" method="POST">
      <div class="mb-3">
        <label for="loginEmail" class="form-label">Email</label>
        <input type="email" id="loginEmail" name="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="mb-3">
        <label for="loginPassword" class="form-label">Password</label>
        <input type="password" id="loginPassword" name="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      <!-- Error Message -->
      <p id="loginMessage" style="color: red; margin-top: 10px;"></p>
      <p><a href="forgot_password.php" onclick="showForgotPasswordPopup()">Forgot Password?</a></p>
    </form>
  </div>
</div>


        <!-- Error Message -->
<?php if (isset($_GET['error'])) : ?>
    <p id="loginError" style="color: red; margin-top: 10px;">
        <?php
        if ($_GET['error'] === 'invalid') {
            echo "Incorrect password. Please try again.";
        } elseif ($_GET['error'] === 'notfound') {
            echo "No user found with this email.";
        }
        ?>
    </p>
    <script>
        // Automatically show the login popup when there's an error
        document.addEventListener('DOMContentLoaded', function () {
            showLoginPopup();
        });
    </script>
<?php endif; ?>

        
    </div>
</div>

<script>

<script>
    function showLoginPopup() {
        document.getElementById('loginPopup').style.display = 'flex';
    }

    function closeLoginPopup() {
        document.getElementById('loginPopup').style.display = 'none';
    }
</script>


  <main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- First Carousel Item -->
        <div class="carousel-item active">
          <img src="assets/img/farmer_1.jpeg" alt="Fresh Organic Produce">
          <div class="carousel-container">
            <h2>Get Fresh, Organic Produce Delivered to Your Doorstep</h2>
            <p>We bring the best of farming straight from the field to your table. No middlemen, just fresh, quality products.</p>
            <a href="products.php" class="btn-get-started">Shop Now</a>
          </div>
        </div><!-- End Carousel Item -->
  
        <!-- Second Carousel Item -->
        <div class="carousel-item">
          <img src="assets/img/farmer_2.jpeg" alt="Organic Vegetables">
          <div class="carousel-container">
            <h2>Healthy, Organic Vegetables for a Better Tomorrow</h2>
            <p>Buy directly from farmers and enjoy the taste of fresh, healthy vegetables, grown with love and care.</p>
            <a href="products.php" class="btn-get-started">Shop Now</a>
          </div>
        </div><!-- End Carousel Item -->
  
        <!-- Third Carousel Item -->
        <div class="carousel-item">
          <img src="assets/img/farmer_3.jpeg" alt="Fresh Produce Every Day">
          <div class="carousel-container">
            <h2>Fresh Produce Every Single Day</h2>
            <p>Experience farm-to-table freshness every day with just a few clicks. Delivered at your convenience.</p>
            <a href="products.php" class="btn-get-started">Shop Now</a>
          </div>
        </div><!-- End Carousel Item -->
  
        <!-- Fourth Carousel Item -->
        <div class="carousel-item">
          <img src="assets/img/farmer_4.jpeg" alt="Passion for Farming">
          <div class="carousel-container">
            <h2>Farming with Passion</h2>
            <p>Our farmers are passionate about growing high-quality, organic produce. Taste the difference!</p>
            <a href="products.php" class="btn-get-started">Shop Now</a>
          </div>
        </div><!-- End Carousel Item -->
  
        <!-- Fifth Carousel Item -->
        <div class="carousel-item">
          <img src="assets/img/farmer_5.jpeg" alt="Good Food for All">
          <div class="carousel-container">
            <h2>Good Food for All</h2>
            <p>Bringing good, healthy food to your table – without the hassle. We make shopping easy and enjoyable.</p>
            <a href="products.php" class="btn-get-started">Shop Now</a>
          </div>
        </div><!-- End Carousel Item -->
  
        <!-- Carousel Controls -->
        <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>
        <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>
  
        <!-- Carousel Indicators (optional) -->
        <ol class="carousel-indicators">
  
</ol>
      </div>
    </section><!-- /Hero Section -->
  </main>
  

    <!-- Services Section -->
<section id="services" class="services section">
  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>SERVICES</h2>
    <p>Helping local farmers sell fresh produce directly to international markets.</p>
  </div><!-- End Section Title -->

  <div class="content">
    <div class="container">
      <div class="row g-0">
        <!-- Service Item: Farm Registration & Listing -->
        <div class="col-lg-3 col-md-6">
          <div style="border: 1px solid rgba(0, 0, 0, 0.1);" class="service-item">
            <span class="number">01</span>
            <div  class="service-item-content">
              <h3 class="service-heading">Farm Registration & Listing</h3>
              <p>Farmers can easily register and list their produce on our platform, gaining access to a wide range of buyers.</p>
            </div>
          </div>
        </div>

        <!-- Service Item: Product Photography & Marketing -->
        <div class="col-lg-3 col-md-6">
          <div style="border: 1px solid rgba(0, 0, 0, 0.1);" class="service-item">
            <span class="number">02</span>
            <div class="service-item-content">
              <h3 class="service-heading">Product Photography & Marketing</h3>
              <p>We provide professional product photography and marketing strategies to help farmers showcase their produce to a global audience.</p>
            </div>
          </div>
        </div>

        <!-- Service Item: Packaging & Shipping Assistance -->
        <div class="col-lg-3 col-md-6">
          <div style="border: 1px solid rgba(0, 0, 0, 0.1);" class="service-item">
            <span class="number">03</span>
            <div class="service-item-content">
              <h3 class="service-heading">Packaging & Shipping Assistance</h3>
              <p>We assist farmers with packaging and ensure efficient shipping, so products arrive fresh and on time.</p>
            </div>
          </div>
        </div>

        <!-- Service Item: Buy Fresh Produce -->
        <div class="col-lg-3 col-md-6">
          <div style="border: 1px solid rgba(0, 0, 0, 0.1);" class="service-item">
            <span class="number">04</span>
            <div class="service-item-content">
              <h3 class="service-heading">Buy Fresh Produce</h3>
              <p>Buyers can purchase fresh, organic produce directly from farmers, cutting out the middlemen for better prices.</p>
            </div>
          </div>
        </div>


          
        <div class="col-lg-3 col-md-6">
          <div style="border: 1px solid rgba(0, 0, 0, 0.1);" class="service-item">
            <span class="number">05</span>
            <div class="service-item-content">
              <h3 class="service-heading">Custom Orders</h3>
              <p>Buyers can place custom orders for bulk or special requests, ensuring they get exactly what they need.</p>
            </div>
          </div>
        </div>

     
        <div class="col-lg-3 col-md-6">
          <div style="border: 1px solid rgba(0, 0, 0, 0.1);" class="service-item">
            <span class="number">06</span>
            <div class="service-item-content">
              <h3 class="service-heading">Order Tracking & Delivery</h3>
              <p>Track your orders and enjoy fast, reliable delivery of fresh produce directly to your home or business.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section><!-- /Services Section -->

    <!-- Services 2 Section -->
    <section id="services-2" class="services-2 section dark-background">
      <!-- Section Title -->
      <div class="container section-title aos-init aos-animate" data-aos="fade-up">
        <h2>Services</h2>
      </div><!-- End Section Title -->

      <div class="services-carousel-wrap">
        <div class="container">
          <div class="swiper init-swiper swiper-initialized swiper-horizontal swiper-backface-hidden">
            <button class="navigation-prev js-custom-prev" tabindex="0" aria-label="Previous slide" aria-controls="swiper-wrapper-4bf9412721df1928">
              <i class="bi bi-arrow-left-short"></i>
            </button>
            <button class="navigation-next js-custom-next" tabindex="0" aria-label="Next slide" aria-controls="swiper-wrapper-4bf9412721df1928">
              <i class="bi bi-arrow-right-short"></i>
            </button>
            <div class="swiper-wrapper" id="swiper-wrapper-4bf9412721df1928" aria-live="off" style="transition-duration: 0ms; transform: translate3d(-1336px, 0px, 0px); transition-delay: 0ms;">              
              <div class="swiper-slide" role="group" aria-label="5 / 6" data-swiper-slide-index="4" style="width: 405.333px; margin-right: 40px;">
                <div class="service-item">
                  <div class="service-item-contents">
                    <a href="#">
                      <h2 class="service-item-title">Product Listing</h2>
                    </a>
                  </div>
                  <img src="assets/img/img_sq_5.jpg" alt="Image" class="img-fluid">
                </div>
              </div>
              <div class="swiper-slide" role="group" aria-label="6 / 6" data-swiper-slide-index="5" style="width: 405.333px; margin-right: 40px;">
                <div class="service-item">
                  <div class="service-item-contents">
                    <a href="#">
                      <h2 class="service-item-title">Secure Payment</h2>
                    </a>
                  </div>
                  <img src="assets/img/img_sq_6.jpg" alt="Image" class="img-fluid">
                </div>
              </div>
    
            <div class="swiper-slide swiper-slide-prev" role="group" aria-label="1 / 6" data-swiper-slide-index="0" style="width: 405.333px; margin-right: 40px;">
                <div class="service-item">
                  <div class="service-item-contents">
                    <a href="#">
                      <h2 class="service-item-title">Shipment &amp; Delivery</h2>
                    </a>
                  </div>
                  <img src="assets/img/img_sq_1.jpg" alt="Image" class="img-fluid">
                </div>
              </div><div class="swiper-slide swiper-slide-active" role="group" aria-label="2 / 6" data-swiper-slide-index="1" style="width: 405.333px; margin-right: 40px;">
                <div class="service-item">
                  <div class="service-item-contents">
                    <a href="#">
                      <h2 class="service-item-title">Profile Management</h2>
                    </a>
                  </div>
                  <img src="assets/img/img_sq_3.jpg" alt="Image" class="img-fluid">
                </div>
              </div><div class="swiper-slide swiper-slide-next" role="group" aria-label="3 / 6" data-swiper-slide-index="2" style="width: 405.333px; margin-right: 40px;">
                <div class="service-item">
                  <div class="service-item-contents">
                    <a href="#">
                      <h2 class="service-item-title">Customer Support</h2>
                    </a>
                  </div>
                  <img src="assets/img/img_sq_8.jpg" alt="Image" class="img-fluid">
                </div>
              </div>
              <div class="swiper-slide" role="group" aria-label="4 / 6" data-swiper-slide-index="3" style="width: 405.333px; margin-right: 40px;">
                <div class="service-item">
                  <div class="service-item-contents">
                    <a href="#">
                      <h2 class="service-item-title">Rating &amp; Reviews</h2>
                    </a>
                  </div>
                  <img src="assets/img/img_sq_4.jpg" alt="Image" class="img-fluid">
                </div>
              </div></div>
            <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal"><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 1"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 2" aria-current="true"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 3"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 4"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 5"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 6"></span></div>
          <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
          <button style="visibility: hidden;" class="mobile-nav-toggle">
               <i class="bi bi-list"></i> <!-- Change to bi-x when active -->
               </button>

        </div>
      </div>
    </section><!-- /Services 2 Section -->

    <section class="index-product-section">
  <h2 class="index-product-heading">Available Products</h2>
  <div class="index-product-grid">
    <!-- Rice Product -->
    <div class="index-product-card">
      <div class="index-product-image">
        <img src="assets/img/Rice_AdobeStock_64819529_E.jpg" alt="Rice">
      </div>
      <div class="index-product-title">Rice</div>
      <a href="products.php"><button class="index-buy-now-btn">Buy Now</button></a>
    </div>

    <!-- Wheat Product -->
    <div class="index-product-card">
      <div class="index-product-image">
        <img src="assets/img/wheat-berries-bowl-768x512.png" alt="Wheat">
      </div>
      <div class="index-product-title">Wheat</div>
      <a href="products.php"><button class="index-buy-now-btn">Buy Now</button></a>
    </div>
  </div>
</section>


  </main>

  <footer id="footer" class="footer dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">AgriCulture</span>
            </a>
            <div class="footer-contact pt-3">
              <p>Shahab Pura Road</p>
              <p>Sialkot, Pakistan</p>
              <p class="mt-3"><strong>Phone:</strong> <span>+92 334 716 3786</span></p>
              <p><strong>Email:</strong> <span>info@example.com</span></p>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Contact us</a></li>
              <li><a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><a href="#">Product Listing</a></li>
              <li><a href="#">Secure Payment</a></li>
              <li><a href="#">Shipment &amp; Delivery</a></li>
              <li><a href="#">Profile Management</a></li>
              <li><a href="#">Customer Support</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>
            © Copyright <strong><span>This is the final year project for University of Sialkot. </span></strong> All Rights Reserved.
          </div>
        </div>

        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>

      </div>
    </div>

  </footer>

  <script>
    function showRegisterPopup(role) {
        document.getElementById('registerPopup').style.display = 'flex';
        document.getElementById('role').value = role;
        document.getElementById('registerTitle').innerText = `Register as ${role.charAt(0).toUpperCase() + role.slice(1)}`;
    }

    function closeRegisterPopup() {
        document.getElementById('registerPopup').style.display = 'none';
    }

    function showLoginPopup() {
        document.getElementById('loginPopup').style.display = 'flex';
    }

    function closeLoginPopup() {
        document.getElementById('loginPopup').style.display = 'none';
    }
</script>

<script>

$(document).ready(function () {
  $('#registerForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    // Collect form data
    const formData = {
      username: $('#username').val(),
      email: $('#email').val(),
      password: $('#password').val(),
      contact: $('#contact').val(),
      dob: $('#dob').val(),
      address: $('#address').val(),
      role: $('#role').val(),
    };

    // Send AJAX request
    $.ajax({
      url: 'signup.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function (response) {
        const messageDiv = $('#signupMessage');
        if (response.status === 'success') {
          messageDiv.css('color', 'green').text(response.message);
          $('#registerForm')[0].reset(); // Clear the form
        } else {
          messageDiv.css('color', 'red').text(response.message);
        }
      },
      error: function () {
        $('#signupMessage').css('color', 'red').text('An error occurred. Please try again.');
      },
    });
  });
});
</script>

<script>
$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const formData = {
            email: $('#loginEmail').val(),
            password: $('#loginPassword').val(),
        };

        $.ajax({
            url: 'login_popup.php', // Backend script
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                const messageDiv = $('#loginMessage');
                if (response.status === 'success') {
                    messageDiv.css('color', 'green').text('Login successful! Redirecting...');
                    setTimeout(() => {
                        if (response.role === 'seller') {
                            window.location.href = 'index.php';
                        } else if (response.role === 'buyer') {
                            window.location.href = 'index.php';
                        } else {
                            window.location.href = 'index.php'; // Default redirect
                        }
                    }, 1000); // Redirect after 1 second
                } else {
                    // Show error message in the popup for invalid credentials
                    messageDiv.css('color', 'red').text(response.message);
                }
            },
            error: function (xhr, status, error) {
                $('#loginMessage').css('color', 'red').text('An error occurred. Please try again.');
                console.error('AJAX Error:', error);
                console.error('XHR Response:', xhr.responseText);
            },
        });
    });
});

</script>
<!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/register_popup.js"></script>
  <script src="assets/js/login_popup.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>

</html>