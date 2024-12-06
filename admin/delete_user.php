<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // Get the user ID from the form

    // Delete the user from the database
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the user list page after successful deletion
        header("Location: user_list.php");
        exit;
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
