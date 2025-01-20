<?php
require 'db_connection.php';

// Fetch all products along with their categories
$query = "
SELECT 
    products.id, 
    products.name, 
    products.description, 
    products.price, 
    products.stock, 
    products.image_path, 
    GROUP_CONCAT(categories.name SEPARATOR ', ') AS category_names
FROM 
    products
LEFT JOIN 
    product_categories 
ON 
    products.id = product_categories.product_id
LEFT JOIN 
    categories 
ON 
    product_categories.category_id = categories.id
GROUP BY 
    products.id
";
$result = $conn->query($query);

// Fetch all available categories
$categoriesQuery = "SELECT id, name FROM categories";
$categoriesResult = $conn->query($categoriesQuery);
$categories = [];
while ($category = $categoriesResult->fetch_assoc()) {
    $categories[] = $category;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriPakistan</title>
    <link rel="stylesheet" href="assets/css/main.css">

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
    <!-- Add your custom styles -->
<style>
        body {
    font-family: 'Open Sans', sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

h1 {
    text-align: center;
    margin: 20px 0;
    color: #333;
    font-size: 2rem;
    font-weight: 600;
}

.products-container {
    max-width: 1200px;
    margin: 20px auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    padding: 0 15px;
}

.product-card {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: row;
    transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.product-card img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

.product-details {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-details h3 {
    margin: 0;
    font-size: 1.2rem;
    color: #333;
    font-weight: 600;
}

.product-details p {
    font-size: 0.9rem;
    margin: 8px 0;
    color: #555;
}

.product-details .price {
    font-size: 1rem;
    font-weight: bold;
    color: #333;
}

.product-actions {
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-end;
    gap: 10px;
}

.product-actions input[type="number"] {
    width: 80px;
    border-radius: 4px;
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}

.btn-buy-now {
    background: #28a745;
    color: #ffffff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-buy-now:hover {
    background: #218838;
}

/* Popup Styling */
.popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #ffffff;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    padding: 20px;
    border-radius: 8px;
    z-index: 1000;
    width: 100%;
    max-width: 400px;
    height: 58%;
}

.popup .close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    font-size: 20px;
    color: #333;
}

.popup form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.popup form input,
.popup form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
}

.popup form button {
    background: #28a745;
    color: #ffffff;
    padding: 12px;
    font-size: 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.popup form button:hover {
    background: #218838;
}

/* Footer Fix */
html, body {
    height: 100%;
}

footer {
    margin-top: auto;
    background-color: #222;
    color: #fff;
    padding: 20px 0;
    text-align: center;
}

footer a {
    color: #0dcaf0;
    text-decoration: none;
}

footer .footer-top {
    margin-bottom: 10px;
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
            <li style="text-tecoration: none;"><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="about.html" class="<?php echo basename($_SERVER['PHP_SELF']) === 'about.html' ? 'active' : ''; ?>">About Us</a></li>
            <li><a href="services.html" class="<?php echo basename($_SERVER['PHP_SELF']) === 'services.html' ? 'active' : ''; ?>">Our Services</a></li>

                <li class="dropdown">
                    <a href="#" ><span>Resources for Farmers</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
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
        <h1>Available Products</h1>
        <div class="products-container">
        <?php while ($product = $result->fetch_assoc()): ?>
<div class="product-card">
    <img src="<?php echo !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : 'default_image.png'; ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'No Name'); ?>">
    <div class="product-details">
        <h3><?php echo htmlspecialchars($product['name'] ?? 'No Name'); ?></h3>
        <p><?php echo htmlspecialchars($product['description'] ?? 'No Description'); ?></p>
        <p>Price per kg: $<?php echo htmlspecialchars($product['price'] ?? '0.00'); ?></p>
        <p>Category: <?php echo htmlspecialchars($product['category_names'] ?? 'No Category'); ?></p>
        <p>Stock: <?php echo htmlspecialchars($product['stock'] ?? '0'); ?> kg</p>
    </div>
    <div class="product-actions">
        <button class="btn-buy-now" onclick="openBuyNowPopup('<?php echo htmlspecialchars($product['id'] ?? '0'); ?>', '<?php echo htmlspecialchars($product['name'] ?? 'No Name'); ?>', '<?php echo htmlspecialchars($product['price'] ?? '0.00'); ?>')">Buy Now</button>
    </div>
</div>
<?php endwhile; ?>

        </div>
    

    <div id="buyNowPopup" class="popup" style="display: none;">
    <div class="popup-content">
        <span class="close" onclick="closeBuyNowPopup()">&times;</span>
        <h2>Buy Now</h2>
        <form id="buyNowForm">
            <input type="hidden" name="product_id" id="productId">
            <p><strong>Product:</strong> <span id="popupProductName"></span></p>
            <p><strong>Price per kg:</strong> $<span id="popupProductPrice"></span></p>
            <label for="quantity">Quantity (kg):</label>
            <input type="number" name="quantity" id="popupQuantity" step="0.1" min="0.1" required>
            <label for="category">Category:</label>
            <select id="popupCategory" name="category_id" required>
                <option>Loading categories...</option>
            </select>
            <label for="shipping_address">Shipping Address:</label>
            <textarea name="shipping_address" id="popupShippingAddress" rows="4" required></textarea>
            <label for="contact_number">Contact Number:</label>
            <input type="text" name="contact_number" id="popupContactNumber" required>
            <button type="submit">Confirm Purchase</button>
        </form>
        <p id="popupMessage" style="color: red; display: none;"></p>
    </div>
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
          <p>City Villas Sialkot</p>
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
    function openBuyNowPopup(productId, productName, productPrice) {
    document.getElementById('buyNowPopup').style.display = 'block';
    document.getElementById('productId').value = productId;
    document.getElementById('popupProductName').innerText = productName;
    document.getElementById('popupProductPrice').innerText = productPrice;

    // Fetch categories dynamically for the selected product
    fetch(`fetch_categories.php?product_id=${productId}`)
        .then((response) => response.json())
        .then((data) => {
            const categorySelect = document.getElementById('popupCategory');
            categorySelect.innerHTML = ''; // Clear previous options

            if (data.status === 'success' && data.categories.length > 0) {
                data.categories.forEach((category) => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.textContent = 'No categories available';
                categorySelect.appendChild(option);
            }
        })
        .catch((error) => {
            console.error('Error fetching categories:', error);
            alert('An error occurred while fetching categories.');
        });
}



function closeBuyNowPopup() {
    const popup = document.getElementById('buyNowPopup');
    popup.style.display = 'none';
}

document.getElementById('buyNowForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('process_buy_now.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        const message = document.getElementById('popupMessage');
        if (data.status === 'success') {
            message.style.color = 'green';
            message.innerText = data.message;
            message.style.display = 'block';

            // Close the popup and reload the page after 2 seconds
            setTimeout(() => {
                closeBuyNowPopup();
                location.reload();
            }, 2000);
        } else {
            message.style.color = 'red';
            message.innerText = data.message;
            message.style.display = 'block';

            // Close the popup regardless of error
            setTimeout(() => {
                closeBuyNowPopup();
            }, 2000);
        }
    })
    .catch((error) => {
        // Close the popup regardless of error
        closeBuyNowPopup();
    });
});

</script>
</body>
</html>
