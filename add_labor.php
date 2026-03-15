<?php
// add_labor.php - save labor entries
include __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: farmer_dashboard.php');
    exit;
}

// sanitize inputs (allow comma as decimal separator)
$labor = trim($_POST['labor_name'] ?? '');
$crop = trim($_POST['crop_name'] ?? '');
$work = trim($_POST['work_desc'] ?? '');
$farm = trim($_POST['farm_name'] ?? '');
$weight_raw = str_replace(',', '.', trim($_POST['weight_kg'] ?? ''));
$price_raw = str_replace(',', '.', trim($_POST['price_per_kg'] ?? ''));
$amount_raw = str_replace(',', '.', trim($_POST['amount'] ?? ''));
$weight = is_numeric($weight_raw) ? (float)$weight_raw : 0.0;
$price = is_numeric($price_raw) ? (float)$price_raw : 0.0;
$amount = is_numeric($amount_raw) ? (float)$amount_raw : ($weight * $price);

// create table if not exists
$create = "CREATE TABLE IF NOT EXISTS labor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    labor_name VARCHAR(255) DEFAULT '',
    crop_name VARCHAR(100) DEFAULT '',
    work_desc VARCHAR(255) DEFAULT '',
    farm_name VARCHAR(255) DEFAULT '',
    weight_kg DOUBLE DEFAULT 0,
    price_per_kg DOUBLE DEFAULT 0,
    amount DOUBLE DEFAULT 0,
    work_date DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($create);

// insert with correct types: 4 strings then 3 doubles
$stmt = $conn->prepare("INSERT INTO labor (labor_name, crop_name, work_desc, farm_name, weight_kg, price_per_kg, amount, work_date) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
if ($stmt) {
    $stmt->bind_param('ssssddd', $labor, $crop, $work, $farm, $weight, $price, $amount);
    $stmt->execute();
    $stmt->close();
}

header('Location: farmer_dashboard.php');
exit;
