<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'db_connection.php';

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Delete the user from the users table
    $query = $conn->prepare("DELETE FROM users WHERE id = ?");
    $query->bind_param("i", $user_id);
    if ($query->execute()) {
        // Redirect back to manage users page
        header("Location: manage_users.php");
    } else {
        echo "Error deleting user.";
    }
}
?>
