<?php
require 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    $contact = trim($_POST['contact']);
    $dob = trim($_POST['dob']);
    $address = trim($_POST['address']);

    // Check if required fields are provided
    if (!$username || !$email || strlen($password) < 8 || !$role) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit();
    }

    // Check if optional fields are empty and set to null
    if (empty($contact)) $contact = null;
    if (empty($dob)) $dob = null;
    if (empty($address)) $address = null;

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
        exit();
    }

    // Insert the user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, contact, dob, address, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $email, $hashedPassword, $contact, $dob, $address, $role);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }
}
?>
