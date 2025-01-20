<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        .container { padding: 20px; text-align: center; }
        .dashboard a { margin: 10px; display: inline-block; padding: 20px; border: 1px solid #ccc; text-decoration: none; }
        .dashboard a:hover { background-color: #f0f0f0; }
    </style>
</head>
<body>
     <h1>Welcome, Admin</h1>
    <ul>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_orders.php">Manage Orders</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
