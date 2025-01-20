<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json'); // Ensure JSON response
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debugging - log all incoming POST data
file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

try {
    // Retrieve form data
    $buyer_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $category_id = $_POST['category_id']; // Selected category
    $shipping_address = trim($_POST['shipping_address']);
    $contact_number = trim($_POST['contact_number']);

    // Validate contact number: at least 11 digits and numeric
    if (strlen($contact_number) < 11 || !preg_match('/^\d+$/', $contact_number)) {
        echo json_encode(['status' => 'error', 'message' => 'Contact number must be at least 11 digits and numeric.']);
        exit();
    }

    // Ensure all required fields are filled
    if (!$product_id || !$quantity || !$shipping_address || !$contact_number || !$category_id) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Fetch product details to verify stock and price
    $stmt = $conn->prepare("SELECT price, stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the product exists
    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
        exit();
    }

    $product = $result->fetch_assoc();

    // Verify stock availability
    if ($product['stock'] < $quantity) {
        echo json_encode(['status' => 'error', 'message' => 'Insufficient stock.']);
        exit();
    }

    // Validate that the selected category is valid for the product
    $stmt = $conn->prepare("
        SELECT 1 
        FROM product_categories 
        WHERE product_id = ? AND category_id = ?
    ");
    $stmt->bind_param("ii", $product_id, $category_id);
    $stmt->execute();
    $category_result = $stmt->get_result();

    if ($category_result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid category selected for this product.']);
        exit();
    }

    // Calculate total price
    $total_price = $product['price'] * $quantity;

    // Deduct stock from the product
    $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $stmt->bind_param("di", $quantity, $product_id);
    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update stock.']);
        exit();
    }

    // Insert the order into the database
    $stmt = $conn->prepare("
        INSERT INTO orders (buyer_id, product_id, category_id, quantity, total_price, shipping_address, contact_number) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiiddss", $buyer_id, $product_id, $category_id, $quantity, $total_price, $shipping_address, $contact_number);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Purchase submitted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to process the purchase.']);
        error_log("MySQL Error: " . $stmt->error); // Log SQL errors
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    error_log("Exception: " . $e->getMessage()); // Log exception errors
}
$product_id = $conn->insert_id; // Get the newly inserted product's ID
$category_id = $_POST['category_id']; // Assuming category_id is selected from a dropdown

// Insert into product_categories
$stmt = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
$stmt->bind_param("ii", $product_id, $category_id);
if (!$stmt->execute()) {
    die("Error: Could not insert into product_categories. " . $stmt->error);
}

?>
