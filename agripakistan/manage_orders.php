<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'db_connection.php';

// Fetch orders
$query = $conn->prepare("
    SELECT 
        orders.id,
        users.username AS buyer_name,
        (SELECT username FROM users WHERE users.id = orders.seller_id) AS seller_name,
        products.name AS product_name,
        orders.quantity,
        orders.total_price,
        orders.status,
        orders.shipping_address,
        orders.contact_number
    FROM 
        orders
    JOIN users ON orders.buyer_id = users.id
    JOIN products ON orders.product_id = products.id
");
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <!-- Include your CSS here -->
</head>
<body>
    <h1>Manage Orders</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Buyer</th>
                <th>Seller</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Shipping Address</th>
                <th>Contact Number</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['buyer_name']; ?></td>
                <td><?php echo $row['seller_name']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['total_price']; ?></td>
                <td><?php echo $row['shipping_address']; ?></td>
                <td><?php echo $row['contact_number']; ?></td>
                <td>
                    <form action="update_order_status.php" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Shipped" <?php if ($row['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="Completed" <?php if ($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                </td>
                <td>
                    <form action="delete_order.php" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
