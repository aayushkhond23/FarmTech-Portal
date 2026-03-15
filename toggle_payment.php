<?php
// toggle_payment.php - AJAX endpoint to mark a labor record paid/unpaid
include __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok'=>false,'error'=>'invalid_method']);
    exit;
}
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '0';
$paid = ($status === '1' || $status === 'true' || $status === 'paid') ? 1 : 0;
if ($id <= 0) {
    echo json_encode(['ok'=>false,'error'=>'invalid_id']);
    exit;
}
$stmt = $conn->prepare("UPDATE labor SET paid = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(['ok'=>false,'error'=>'prepare_failed']);
    exit;
}
$stmt->bind_param('ii', $paid, $id);
$ok = $stmt->execute();
$stmt->close();
if ($ok) echo json_encode(['ok'=>true,'paid'=>$paid]);
else echo json_encode(['ok'=>false,'error'=>'execute_failed']);

exit;
