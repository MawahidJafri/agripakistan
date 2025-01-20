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
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = __DIR__ . '/uploads/';
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Update product with a new image
            $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image_path = ? WHERE id = ? AND seller_id = ?");
            $stmt->bind_param("ssdissi", $name, $description, $price, $stock, $image_name, $product_id, $_SESSION['user_id']);
        } else {
            die("Failed to upload image.");
        }
    } else {
        // Update product without a new image
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ? AND seller_id = ?");
        $stmt->bind_param("ssdiii", $name, $description, $price, $stock, $product_id, $_SESSION['user_id']);
    }

    if ($stmt->execute()) {
        header("Location: manage_listings.php?message=Product updated successfully!");
        exit();
    } else {
        die("Error updating product: " . $stmt->error);
    }
} else {
    die("Invalid request method.");
}
?>
