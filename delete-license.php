<?php
include("include/conn.php");

// Check if the user is logged in
include("include/function.php");
$login = cekSession();
if ($login != 1) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

// Check if the ID is provided
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM `users` WHERE `id` = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'License deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete license.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}

$conn->close();
?>
