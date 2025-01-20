<?php
session_start();
require 'db_connection.php';

// Restrict access to sellers only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header("Location: unauthorized_access.html");
    exit();
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch categories for the dropdown
$categories_query = "SELECT id, name FROM categories";
$categories_result = $conn->query($categories_query);

if (!$categories_result) {
    die("Error fetching categories: " . $conn->error);
}
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle JS (with Popper.js for dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
<style>
    /* General Form Styling */
form {
    width: 50%;
    margin: 0 auto;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

form input[type="text"],
form input[type="number"],
form input[type="file"],
form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}

form textarea {
    resize: vertical;
    height: 120px;
}

form button {
    background-color: #28a745;
    color: #fff;
    font-size: 16px;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #218838;
}

</style>
</head>

<body class="index-page">
<header id="header" class="header d-flex align-items-center">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <img  src="assets/img/logo.jpeg" alt="AgriCulture">
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

        
        </nav>
    </div>
</header>

<main>
    <div class="container">
      <h2 class="text-center mt-4">Add Your Product</h2>
      <form action="process_add_products.php" method="POST" enctype="multipart/form-data">
            <!-- Product Name -->
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" class="form-control" required>

            <!-- Description -->
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control" required></textarea>

            <!-- Price -->
            <label for="price">Price (in $):</label>
            <input type="number" id="price" name="price" step="0.01" class="form-control" required>

            <!-- Category Selection -->
            <label>Category:</label>
            <div>
                <input type="radio" id="existing_category" name="category_option" value="existing" checked>
                <label for="existing_category">Choose Existing Category</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="" disabled selected>Select a category</option>
                    <?php while ($row = $categories_result->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <input type="radio" id="new_category" name="category_option" value="new">
                <label for="new_category">Add New Category</label>
                <input type="text" id="new_category_name" name="new_category_name" class="form-control" placeholder="Enter new category" disabled>
            </div>

            <!-- Stock -->
            <label for="stock">Stock Quantity:</label>
            <input type="number" id="stock" name="stock" class="form-control" min="0" required>

            <!-- Image Upload -->
            <label for="image">Upload Product Image:</label>
            <input type="file" id="image" name="image" accept="image/*" class="form-control" required>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary mt-3">Add Product</button>
        </form>

    </div>
  </main>

  <footer id="footer" class="footer dark-background">

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

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script defer src="assets/js/register_popup.js"></script>
  <script defer src="assets/js/login_popup.js"></script>
  
<script>
document.getElementById('existing_category').addEventListener('change', function () {
    document.getElementById('category_id').disabled = false;
    document.getElementById('new_category_name').disabled = true;
    document.getElementById('new_category_name').value = ''; // Clear new category input
});

document.getElementById('new_category').addEventListener('change', function () {
    document.getElementById('category_id').disabled = true;
    document.getElementById('new_category_name').disabled = false;
    document.getElementById('category_id').value = ''; // Clear existing category selection
});
</script>


</body>

</html>
