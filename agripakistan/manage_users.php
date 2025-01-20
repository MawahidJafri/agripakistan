<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'db_connection.php';

// Fetch all users
$query = $conn->prepare("SELECT id, username, email, contact, role, created_at FROM users");
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <!-- Include your CSS here -->
</head>
<body>
    <h1>Manage Users</h1>
    <table border="1">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['contact']; ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <!-- Delete User Form -->
                    <form action="delete_user.php" method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
