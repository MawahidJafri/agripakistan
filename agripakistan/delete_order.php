<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'db_connection.php';

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Delete the order from the orders table
    $query = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $query->bind_param("i", $order_id);
    if ($query->execute()) {
        // Redirect back to manage orders page
        header("Location: manage_orders.php");
    } else {
        echo "Error deleting order.";
    }
}
?>
