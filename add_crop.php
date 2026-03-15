<?php
// add_crop.php - process Add Crop form and save into `crops` table
include __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: farmer_dashboard.php');
    exit;
}

$farm = trim($_POST['farm_name'] ?? '');
$season = trim($_POST['season'] ?? '');
$crop = trim($_POST['crop_name'] ?? '');
$date = trim($_POST['production_date'] ?? '');
$prod = $_POST['daily_production'] ?? 0;

if ($prod === '') $prod = 0;

// create table if not exists
$create = "CREATE TABLE IF NOT EXISTS crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farm_name VARCHAR(255) DEFAULT '',
    season VARCHAR(50) DEFAULT '',
    crop_name VARCHAR(100) DEFAULT '',
    production_date DATE DEFAULT NULL,
    daily_production DOUBLE DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($create);

$stmt = $conn->prepare("INSERT INTO crops (farm_name, season, crop_name, production_date, daily_production) VALUES (?, ?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param('ssssd', $farm, $season, $crop, $date, $prod);
    $stmt->execute();
    $stmt->close();
}

header('Location: farmer_dashboard.php');
exit;
