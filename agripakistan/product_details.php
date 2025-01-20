<?php
require 'db_connection.php';

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<header>
    <a href="products.php">Back to Products</a>
</header>
<main>
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image">
    <p><?php echo htmlspecialchars($product['description']); ?></p>
    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
    <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
    <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn btn-success">Add to Cart</a>
</main>
</body>
</html>
