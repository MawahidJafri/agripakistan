<?php
require 'db_connection.php';

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Fetch categories associated with the product
    $query = "
        SELECT categories.id, categories.name 
        FROM product_categories
        JOIN categories ON product_categories.category_id = categories.id
        WHERE product_categories.product_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'categories' => $categories
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Product ID not provided'
    ]);
}
?>
