<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($order_id > 0 && !empty($status)) {
        $update_query = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "Order status updated successfully.";
        } else {
            echo "Failed to update order status.";
        }
    } else {
        echo "Invalid order ID or status.";
    }
} else {
    echo "Invalid request method.";
}
?>
