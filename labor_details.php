<?php
include __DIR__ . '/db.php';
// ensure 'paid' column exists (will fail harmlessly on older MySQL versions)
@$conn->query("ALTER TABLE labor ADD COLUMN paid TINYINT DEFAULT 0");

$res = $conn->query("SELECT * FROM labor ORDER BY created_at DESC LIMIT 500");
$rows = [];
if ($res) { while($r = $res->fetch_assoc()) $rows[] = $r; }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Labor Details</title>
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
    <h4>Labor Details</h4>
    <?php if (count($rows)===0): ?>
      <div class="small text-muted">No labor records.</div>
    <?php else: ?>
      <div class="row">
        <?php foreach($rows as $r): ?>
          <?php $paid = !empty($r['paid']) ? 1 : 0; ?>
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($r['labor_name']); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted small"><?php echo htmlspecialchars(ucfirst($r['crop_name'])); ?> — <?php echo htmlspecialchars($r['farm_name']); ?></h6>
                  </div>
                  <div class="text-end">
                    <?php if($paid): ?>
                      <span class="badge bg-success">Paid</span>
                    <?php else: ?>
                      <span class="badge bg-secondary">Unpaid</span>
                    <?php endif; ?>
                  </div>
                </div>
                <p class="card-text small mb-1"><strong>Work:</strong> <?php echo htmlspecialchars($r['work_desc']); ?></p>
                <p class="card-text small mb-1"><strong>Weight:</strong> <?php echo htmlspecialchars($r['weight_kg']); ?> kg • <strong>Price/kg:</strong> Rs <?php echo htmlspecialchars($r['price_per_kg']); ?></p>
                <p class="card-text small mb-1"><strong>Amount:</strong> Rs <?php echo htmlspecialchars($r['amount']); ?></p>
                <p class="card-text small text-muted">Added: <?php echo htmlspecialchars($r['created_at']); ?></p>
                <div class="mt-2 d-flex gap-2">
                  <button class="btn btn-sm btn-outline-success mark-paid" data-id="<?php echo $r['id']; ?>">Mark Paid</button>
                  <button class="btn btn-sm btn-outline-secondary mark-unpaid" data-id="<?php echo $r['id']; ?>">Mark Unpaid</button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  
  <script>
  // attach handlers to mark paid/unpaid
  (function(){
    function sendUpdate(id, value, btn){
      fetch('toggle_payment.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'id='+encodeURIComponent(id)+'&status='+encodeURIComponent(value)
      }).then(r=>r.json()).then(function(js){
        if(!js || !js.ok) return alert('Update failed');
        // find the card badge and update
        const btnEl = btn;
        const card = btnEl.closest('.card');
        if(!card) return;
        const badge = card.querySelector('.badge');
        if(js.paid==1){
          if(badge){ badge.className = 'badge bg-success'; badge.textContent = 'Paid'; }
        } else {
          if(badge){ badge.className = 'badge bg-secondary'; badge.textContent = 'Unpaid'; }
        }
      }).catch(function(){ alert('Network error'); });
    }
    document.querySelectorAll('.mark-paid').forEach(function(b){ b.addEventListener('click', function(){ sendUpdate(this.dataset.id, 1, this); }); });
    document.querySelectorAll('.mark-unpaid').forEach(function(b){ b.addEventListener('click', function(){ sendUpdate(this.dataset.id, 0, this); }); });
  })();
  </script>
</div>
</body>
</html>
