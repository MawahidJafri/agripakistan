<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the order status
    $query = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $query->bind_param("si", $status, $order_id);
    if ($query->execute()) {
        // Redirect back to manage orders page
        header("Location: manage_orders.php");
    } else {
        echo "Error updating order status.";
    }
}
?>
