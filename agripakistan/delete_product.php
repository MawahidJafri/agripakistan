<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    echo "<script>alert('Unauthorized access.');</script>";
    header("Location: manage_listings.php");
    exit();
}

// Check if the product ID is passed
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $seller_id = $_SESSION['user_id'];

    // Ensure the product belongs to the logged-in seller
    $query = "DELETE FROM products WHERE id = ? AND seller_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $product_id, $seller_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "<script>alert('Product deleted successfully!');</script>";
    } else {
        echo "<script>alert('Failed to delete product. Either it does not exist or unauthorized access.');</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
}

header("Location: manage_listings.php");
exit();
?>
