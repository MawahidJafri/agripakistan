<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $profile_picture = $_FILES['profile_picture'];

    // Profile picture upload
    $target_file = null;
    if ($profile_picture['size'] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture['name']);
        if (!move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
            $target_file = null; // Keep null if upload fails
        }
    }

    // Update user details
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_picture = COALESCE(?, profile_picture) WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $target_file, $user_id);

    if ($stmt->execute()) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Failed to update profile.";
    }
}
?>
