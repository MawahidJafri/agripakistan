<?php
session_start();
require 'db_connection.php';

try {
    // Restrict access to sellers only
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
        header("Location: manage_listings.php");
        exit();
    }

    // Check if the form is submitted via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $seller_id = $_SESSION['user_id']; // Get the logged-in seller's ID
        $name = trim($_POST['product_name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);

        // Validate inputs
        if (empty($name) || empty($description) || $price <= 0 || $stock < 0) {
            throw new Exception("All fields are required, and price/stock must be valid values.");
        }

        // Initialize category_id
        $category_id = null;

        // Handle category
        if (isset($_POST['category_option']) && $_POST['category_option'] === 'existing') {
            $category_id = intval($_POST['category_id']);
            if ($category_id <= 0) {
                throw new Exception("Invalid existing category selected.");
            }
        } elseif (isset($_POST['category_option']) && $_POST['category_option'] === 'new') {
            $new_category_name = trim($_POST['new_category_name']);
            if (empty($new_category_name)) {
                throw new Exception("New category name cannot be empty.");
            }

            // Insert new category into the database
            $category_stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $category_stmt->bind_param("s", $new_category_name);
            if ($category_stmt->execute()) {
                $category_id = $category_stmt->insert_id; // Get the ID of the newly inserted category
            } else {
                throw new Exception("Error inserting new category: " . $category_stmt->error);
            }
        } else {
            throw new Exception("Invalid category option selected.");
        }

        // Check if category_id is valid
        if (!$category_id) {
            throw new Exception("Category ID is missing or invalid.");
        }

        // Handle file upload
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Image upload failed. Please ensure a valid image is uploaded.");
        }

        $upload_dir = __DIR__ . '/uploads/';
        $image_name = time() . '_' . basename($_FILES['image']['name']); // Add timestamp for uniqueness
        $image_path = $upload_dir . $image_name;

        // Check if the directory exists, if not, create it
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("Failed to create upload directory.");
            }
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            throw new Exception("Failed to move uploaded file.");
        }

        // Insert product details into the database
        $stmt = $conn->prepare("INSERT INTO products (seller_id, name, description, price, category_id, image_path, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdisi", $seller_id, $name, $description, $price, $category_id, $image_name, $stock);

        if ($stmt->execute()) {
            $product_id = $stmt->insert_id; // Get the ID of the newly inserted product

            // Automatically insert into product_category table
            $product_category_stmt = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
            $product_category_stmt->bind_param("ii", $product_id, $category_id);
            if ($product_category_stmt->execute()) {
                // Redirect to manage listings with a success message
                header("Location: manage_listings.php?message=Product added successfully!");
                exit();
            } else {
                throw new Exception("Error inserting into product_category: " . $product_category_stmt->error);
            }
        } else {
            throw new Exception("Error inserting product: " . $stmt->error);
        }
    } else {
        throw new Exception("Invalid request method.");
    }
} catch (Exception $e) {
    // Display a user-friendly error message
    die("<p style='color: red;'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</p>");
}
?>
