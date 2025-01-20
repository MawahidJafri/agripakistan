<?php
session_start();
require 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch user details
$stmt = $conn->prepare("SELECT username, email, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch specific data based on role
if ($role === 'buyer') {
    // Fetch order history for buyers
    $orders_stmt = $conn->prepare("
        SELECT orders.id, orders.total_price, orders.quantity, orders.created_at, products.name AS product_name 
        FROM orders 
        JOIN products ON orders.product_id = products.id 
        WHERE orders.buyer_id = ?
    ");
    $orders_stmt->bind_param("i", $user_id);
    $orders_stmt->execute();
    $orders = $orders_stmt->get_result();
} elseif ($role === 'seller') {
    // Fetch sales summary for sellers
    $sales_stmt = $conn->prepare("
        SELECT orders.id, orders.total_price, orders.quantity, orders.created_at, products.name AS product_name 
        FROM orders 
        JOIN products ON orders.product_id = products.id 
        WHERE products.seller_id = ?
    ");
    $sales_stmt->bind_param("i", $user_id);
    $sales_stmt->execute();
    $sales = $sales_stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriPakistan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle JS (with Popper.js for dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
    <style>
        /* General Popup Styles */
.modal-dialog {
    max-width: 600px;
    margin: 1.75rem auto;
}

.modal-content {
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background-color: #007bff;
    color: #ffffff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    padding: 20px;
}

.modal-header .btn-close {
    background-color: transparent;
    border: none;
    font-size: 1.5rem;
    color: #ffffff;
    cursor: pointer;
}

.modal-body {
    padding: 20px;
    background-color: #f9f9f9;
}

.modal-body input[type="text"],
.modal-body input[type="email"],
.modal-body input[type="file"],
.modal-body textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 1rem;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.modal-body input[type="text"]:focus,
.modal-body input[type="email"]:focus,
.modal-body input[type="file"]:focus,
.modal-body textarea:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.modal-footer {
    background-color: #f1f1f1;
    padding: 15px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    text-align: right;
}

.modal-footer .btn-primary {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.modal-footer .btn-primary:hover {
    background-color: #0056b3;
}

.modal-footer .btn-secondary {
    background-color: #ddd;
    color: #333;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.modal-footer .btn-secondary:hover {
    background-color: #bbb;
}

/* Round Profile Picture */
.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 20px;
    border: 4px solid #007bff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* Buttons */
.btn-edit {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-edit:hover {
    background-color: #0056b3;
}
    </style>
</head>
<body>
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
                <li><a href="profile.php" class="active">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
        </nav>
    </div>
</header>
<div class="profile-container text-center">
    <!-- Profile Picture -->
    <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'default_avatar.png'); ?>" alt="Profile Picture" class="profile-picture">

    <!-- User Details -->
    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
    <p><?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($role)); ?></p>

    <!-- Edit Profile Button -->
    <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
</div>

<!-- Order/Sales Table -->
<div class="table-container">
    <?php if ($role === 'buyer'): ?>
        <h4>Your Order History</h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?> kg</td>
                    <td>$<?php echo htmlspecialchars($order['total_price']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($role === 'seller'): ?>
        <h4>Your Sales Summary</h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($sale = $sales->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sale['id']); ?></td>
                    <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($sale['quantity']); ?> kg</td>
                    <td>$<?php echo htmlspecialchars($sale['total_price']); ?></td>
                    <td><?php echo htmlspecialchars($sale['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process_update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <!-- Profile Picture -->
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<footer style="margin-top: 10%" id="footer" class="footer dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">AgriPakistan</span>
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
              <li><a href="#">Terms of service</a></li>
              <li><a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><a href="#">Fresh Products</a></li>
              <li><a href="#">Custom Orders</a></li>
              <li><a href="#">Product Listing</a></li>
              <li><a href="#">Marketing</a></li>
              <li><a href="#">Shipping Assistance</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>
            © Copyright <strong><span>Muhammad Subhan,Mawahid Mujtaba, Amna Asif</span></strong>. All Rights Reserved. This is the final year project for University of Sialkot.
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
