<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header('Location: login.html');
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Fetch orders for the logged-in buyer
$stmt = $conn->prepare("SELECT o.id, p.name, o.quantity, o.total_price, o.shipping_address, o.contact_number, o.created_at FROM orders o JOIN products p ON o.product_id = p.id WHERE o.buyer_id = ?");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<h1>My Orders</h1>
<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Shipping Address</th>
            <th>Contact Number</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($order = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo htmlspecialchars($order['name']); ?></td>
            <td><?php echo $order['quantity']; ?> kg</td>
            <td>$<?php echo $order['total_price']; ?></td>
            <td><?php echo htmlspecialchars($order['shipping_address']); ?></td>
            <td><?php echo htmlspecialchars($order['contact_number']); ?></td>
            <td><?php echo $order['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
