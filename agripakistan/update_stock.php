<?php
session_start();
require 'db_connection.php';

// Restrict access to sellers only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header("Location: unauthorized_access.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $new_stock = intval($_POST['new_stock']);

    // Update stock in the database
    $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("iii", $new_stock, $product_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header("Location: manage_listings.php?message=Stock updated successfully!");
        exit();
    } else {
        die("Error updating stock: " . $stmt->error);
    }
} else {
    die("Invalid request method.");
}
?>
