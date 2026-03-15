<?php
// delete_labor.php - deletes a labor record by id
include __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_dashboard.php');
    exit;
}
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header('Location: admin_dashboard.php');
    exit;
}
$stmt = $conn->prepare("DELETE FROM labor WHERE id = ? LIMIT 1");
if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}
header('Location: admin_dashboard.php');
exit;
