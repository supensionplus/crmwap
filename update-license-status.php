<?php
include("include/conn.php");

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE `users` SET `status` = '$status' WHERE `id` = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true, 'status' => $status]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters.']);
}
?>
