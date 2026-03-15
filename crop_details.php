<?php
include __DIR__ . '/db.php';

// fetch crop records
$res = $conn->query("SELECT * FROM crops ORDER BY production_date DESC, created_at DESC LIMIT 500");
$crops = [];
if ($res) { while($r = $res->fetch_assoc()) $crops[] = $r; }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Crop Details</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
body{ background: linear-gradient(120deg,#eef7f0,#dff1df); min-height:100vh; }
.container{ padding-top:2rem; }
.card{ border-radius:10px; }
.back-btn{ margin-bottom:1rem; }
</style>
</head>
<body>
<div class="container">
  <a href="farmer_dashboard.php" class="btn btn-sm btn-outline-primary back-btn">← Back to Dashboard</a>
  <div class="card p-3 shadow">
    <h4>Crop Details</h4>
    <p class="small text-muted">Latest crop entries</p>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Farm Name</th>
            <th>Season</th>
            <th>Crop</th>
            <th>Production Date</th>
            <th>Daily Production (kg)</th>
            <th>Added At</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($crops)===0): ?>
            <tr><td colspan="7" class="text-center small text-muted">No crop records found.</td></tr>
          <?php else: $i=1; foreach($crops as $r): ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($r['farm_name']); ?></td>
              <td><?php echo htmlspecialchars(ucfirst($r['season'])); ?></td>
              <td><?php echo htmlspecialchars(ucfirst($r['crop_name'])); ?></td>
              <td><?php echo htmlspecialchars($r['production_date']); ?></td>
              <td><?php echo htmlspecialchars($r['daily_production']); ?></td>
              <td><?php echo htmlspecialchars($r['created_at']); ?></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
