<?php
session_start();
require 'db_connection.php';

// Ensure only sellers can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header("Location: unauthorized_access.html");
    exit();
}

// Get the product ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Product ID.");
}

$product_id = intval($_GET['id']);

// Fetch the product details from the database
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found or you don't have permission to edit this product.");
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body>
<header id="header" class="header d-flex align-items-center">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <img src="assets/img/logo.png" alt="AgriCulture">
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
            <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="about.html" class="<?php echo basename($_SERVER['PHP_SELF']) === 'about.html' ? 'active' : ''; ?>">About Us</a></li>
            <li><a href="services.html" class="<?php echo basename($_SERVER['PHP_SELF']) === 'services.html' ? 'active' : ''; ?>">Our Services</a></li>
            <li><a href="farmer-profiles.html" class="<?php echo basename($_SERVER['PHP_SELF']) === 'farmer-profiles.html' ? 'active' : ''; ?>">Farmer Profiles</a></li>

                <li class="dropdown">
                    <a href="#"><span>Resources for Farmers</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <!-- Marketplace (Visible to All) -->
                        <li class="dropdown">
                            <a href="#"><span>Marketplace</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="browse_products.html">Browse Products</a></li>
                                <li><a href="bulk_orders.html">Bulk Orders</a></li>
                                <li><a href="best_sellers.html">Best Sellers</a></li>
                                <li><a href="product_categories.html">Product Categories</a></li>
                            </ul>
                        </li>

                        <!-- Farmer's Corner (Visible to Sellers Only) -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') : ?>
                        <li class="dropdown">
                            <a href="#"><span>Farmer's Corner</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="add_products.php">Add Your Product</a></li>
                                <li><a href="manage_listings.html">Manage Listings</a></li>
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
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
        </nav>
    </div>
</header>

    <main class="container mt-5">
        <h2>Edit Product</h2>
        <form action="update_product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price (in $)</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']); ?>" min="0" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Product Image (Optional)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
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
</body>
</html>
