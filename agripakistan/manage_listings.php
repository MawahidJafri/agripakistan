<?php
session_start();
require 'db_connection.php';

// Restrict access to sellers only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header("Location: unauthorized_access.html");
    exit();
}

// Suppress warnings
error_reporting(E_ERROR | E_PARSE);

// Fetch products for the logged-in seller
$sellerId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, name, description, price, image_path FROM products WHERE seller_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $sellerId);
    $stmt->execute();
    $result = $stmt->get_result();
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
    /* Table Styling */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

table thead {
    background-color: #007bff;
    color: #fff;
}

table th,
table td {
    padding: 12px 15px;
    text-align: left;
    border: 1px solid #ddd;
}

table th {
    text-transform: uppercase;
    font-size: 14px;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
}

table td img {
    max-width: 100px;
    border-radius: 8px;
}

table a {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
    margin-right: 10px;
}

table a:hover {
    text-decoration: underline;
}

table a.delete {
    color: #dc3545;
}

table a.delete:hover {
    text-decoration: underline;
}
/* Ensure body and html take full height */
html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
}

/* Main content area */
main {
    flex: 1; /* This pushes the footer to the bottom */
    padding: 64px; /* Add some spacing for readability */
}

/* Footer styles */
footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px 20px;
    position: relative;
    width: 100%;
    bottom: 0;
}


</style>
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

        
        </nav>
    </div>
</header>

<main>
    <h2>Manage Your Listings</h2>

    <!-- Display Success Messages -->
    <?php if (isset($_GET['message'])): ?>
        <p class="success-message"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <!-- Check if there are products -->
    <?php if (isset($result) && $result->num_rows > 0): ?>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image" width="100"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                        <td>
                            <!-- Update Stock Form -->
                            <form action="update_stock.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="new_stock" value="<?php echo $product['stock']; ?>" min="0" class="form-control" style="width: 80px; display: inline;">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                        <td>
                        <a href="edit_product.php?id=<?= $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No products found. <a href="add_products.php" class="btn btn-primary">Add a product</a></p>
    <?php endif; ?>
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

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script defer src="assets/js/register_popup.js"></script>
  <script defer src="assets/js/login_popup.js"></script>
  <script>
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch('delete_product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Product deleted successfully!');
                location.reload(); // Reload the page to update the listings
            } else {
                alert('Error deleting product: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the product.');
        });
    }
}
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = `delete_product.php?id=${productId}`;
    }
}
</script>


</body>

</html>
