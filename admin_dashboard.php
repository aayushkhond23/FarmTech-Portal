<?php
include __DIR__ . '/db.php';

// fetch farmers (users with role='farmer')
$farmers = [];
$res = $conn->query("SELECT id, username, created_at FROM users WHERE role='farmer' ORDER BY username");
if ($res) {
		while ($r = $res->fetch_assoc()) $farmers[] = $r;
}

// fetch available years from crops
$years = [];
$res = $conn->query("SELECT DISTINCT YEAR(production_date) as y FROM crops WHERE production_date IS NOT NULL ORDER BY y DESC");
if ($res) { while ($r = $res->fetch_assoc()) $years[] = $r['y']; }

// fetch distinct crop names
$cropNames = [];
$res = $conn->query("SELECT DISTINCT crop_name FROM crops ORDER BY crop_name");
if ($res) { while ($r = $res->fetch_assoc()) if($r['crop_name']!=='') $cropNames[] = $r['crop_name']; }

// handle filters
$selYear = intval($_GET['year'] ?? (count($years)>0 ? $years[0] : date('Y')));
$selCrop = $_GET['crop'] ?? 'all';
// week selector (format YYYY-Www, e.g., 2026-W11)
$selWeek = $_GET['week'] ?? date('o-\\W\\W');

// aggregated totals per farm for selected year and crop
$params = [];
$sql = "SELECT farm_name, crop_name, SUM(daily_production) as total FROM crops WHERE YEAR(production_date)=?";
// prepare and bind
if ($selCrop !== 'all') {
		$sql .= " AND crop_name = ?";
}
$sql .= " GROUP BY farm_name, crop_name ORDER BY total DESC";

$totals = [];
if ($stmt = $conn->prepare($sql)) {
		if ($selCrop !== 'all') {
				$stmt->bind_param('is', $selYear, $selCrop);
		} else {
				$stmt->bind_param('i', $selYear);
		}
		$stmt->execute();
		$res = $stmt->get_result();
		while ($r = $res->fetch_assoc()) {
				$totals[] = $r;
		}
		$stmt->close();
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
	<div class="container d-flex justify-content-between align-items-center">
		<h3 class="text-white mb-0">Admin Dashboard</h3>
		<a href="logout.php" class="btn btn-light">Logout</a>
	</div>

</nav>

<div class="container mt-4">
	<div class="row">

		<div class="col-md-4">
			<div class="card p-3 shadow">
				<h5>Reports</h5>
				<form class="row g-2" method="GET">
					<div class="col-6">
						<label class="form-label small">Year</label>
						<select name="year" class="form-select">
							<?php foreach($years as $y): ?>
								<option value="<?php echo $y; ?>" <?php if($selYear==$y) echo 'selected'; ?>><?php echo $y; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-6">
						<label class="form-label small">Crop</label>
						<select name="crop" class="form-select">
							<option value="all" <?php if($selCrop==='all') echo 'selected'; ?>>All</option>
							<?php foreach($cropNames as $c): ?>
								<option value="<?php echo htmlspecialchars($c); ?>" <?php if($selCrop===$c) echo 'selected'; ?>><?php echo htmlspecialchars(ucfirst($c)); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-12 text-end mt-2">
						<button class="btn btn-sm btn-primary">Show</button>
					</div>
				</form>
			</div>
		</div>

	</div>

	<div class="row mt-4">
		<div class="col-md-6">
			<div class="card p-3 shadow">
				<h5>Production Report for <?php echo htmlspecialchars($selYear); ?> <?php if($selCrop!=='all') echo ' - '.htmlspecialchars(ucfirst($selCrop)); ?></h5>
				<div class="table-responsive">
					<table class="table table-sm table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Farm Name</th>
								<th>Crop</th>
								<th>Total Production (kg)</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($totals)===0): ?>
								<tr><td colspan="4" class="text-center small text-muted">No data for selected filters</td></tr>
							<?php else: $i=1; foreach($totals as $t): ?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo htmlspecialchars($t['farm_name']); ?></td>
									<td><?php echo htmlspecialchars(ucfirst($t['crop_name'])); ?></td>
									<td><?php echo htmlspecialchars($t['total']); ?></td>
								</tr>
							<?php endforeach; endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card p-3 shadow">
				<h5>Labor Report (Weekly)</h5>
				<form class="row g-2 mb-3" method="GET">
					<div class="col-6">
						<label class="form-label small">Week</label>
						<input type="week" name="week" class="form-control" value="<?php echo htmlspecialchars($selWeek); ?>">
					</div>
					<div class="col-6">
						<label class="form-label small">Farmer / Farm</label>
						<input type="text" name="farm" class="form-control" placeholder="(optional) farm name" value="<?php echo htmlspecialchars($_GET['farm'] ?? ''); ?>">
					</div>
					<div class="col-12 text-end mt-2">
						<button class="btn btn-sm btn-primary">Show Labor</button>
					</div>
				</form>
				<?php
				// compute start/end date for selected ISO week
				$weekInput = $_GET['week'] ?? date('o-\\W\\W');
				$weekStart = null; $weekEnd = null;
				if (preg_match('/^(\\d{4})-W?(\\d{2})$/', str_replace('W','-', $weekInput), $m)) {
					$y = (int)$m[1]; $w = (int)$m[2];
					$dt = new DateTime();
					$dt->setISODate($y, $w);
					$weekStart = $dt->format('Y-m-d');
					$weekEnd = $dt->modify('+6 days')->format('Y-m-d');
				} else {
					$dt = new DateTime(); $dt->setISODate((int)$dt->format('o'), (int)$dt->format('W'));
					$weekStart = $dt->format('Y-m-d');
					$weekEnd = $dt->modify('+6 days')->format('Y-m-d');
				}

				// optional farm filter
				$farmFilter = trim($_GET['farm'] ?? '');

				// fetch labor rows for week
				$params = [];
				$sql = "SELECT id, labor_name, crop_name, work_desc, farm_name, weight_kg, price_per_kg, amount, work_date, paid FROM labor WHERE work_date BETWEEN ? AND ?";
				$params[] = $weekStart; $params[] = $weekEnd;
				if ($farmFilter !== '') { $sql .= " AND farm_name LIKE ?"; $params[] = "%".$farmFilter."%"; }
				$sql .= " ORDER BY work_date DESC, id DESC";
				$labRows = [];
				if ($stmt = $conn->prepare($sql)) {
					$types = str_repeat('s', count($params));
					$stmt->bind_param($types, ...$params);
					$stmt->execute();
					$resL = $stmt->get_result();
					while($r = $resL->fetch_assoc()) $labRows[] = $r;
					$stmt->close();
				}

				// compute totals per farm and paid/unpaid sums
				$summary = [];
				foreach($labRows as $r){
					$key = $r['farm_name'];
					if(!isset($summary[$key])) $summary[$key] = ['paid'=>0.0,'unpaid'=>0.0,'total'=>0.0,'count'=>0];
					$summary[$key]['total'] += (float)$r['amount'];
					if(!empty($r['paid'])) $summary[$key]['paid'] += (float)$r['amount'];
					else $summary[$key]['unpaid'] += (float)$r['amount'];
					$summary[$key]['count']++;
				}
				?>
				<div class="mb-3">
					<strong>Week:</strong> <?php echo htmlspecialchars($weekStart.' → '.$weekEnd); ?>
				</div>
				<?php if(empty($labRows)): ?>
					<div class="small text-muted">No labor records for this week.</div>
				<?php else: ?>
					<div class="table-responsive">
						<table class="table table-sm table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Farm</th>
									<th>Worker</th>
									<th>Work</th>
									<th>Date</th>
									<th>Amount</th>
									<th>Paid</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; foreach($labRows as $lr): ?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo htmlspecialchars($lr['farm_name']); ?></td>
										<td><?php echo htmlspecialchars($lr['labor_name']); ?></td>
										<td><?php echo htmlspecialchars($lr['work_desc']); ?></td>
										<td><?php echo htmlspecialchars($lr['work_date']); ?></td>
										<td>Rs <?php echo htmlspecialchars($lr['amount']); ?></td>
										<td><?php if(!empty($lr['paid'])) echo '<span class="badge bg-success">Paid</span>'; else echo '<span class="badge bg-secondary">Unpaid</span>'; ?></td>
										<td>
											<form method="POST" action="delete_labor.php" onsubmit="return confirm('Delete this labor entry?');">
												<input type="hidden" name="id" value="<?php echo (int)$lr['id']; ?>">
												<button class="btn btn-sm btn-danger">Delete</button>
											</form>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<div class="mt-3">
						<h6>Summary by Farm</h6>
						<ul class="list-group">
							<?php foreach($summary as $farm => $s): ?>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<div>
										<strong><?php echo htmlspecialchars($farm); ?></strong>
										<div class="small text-muted">Entries: <?php echo $s['count']; ?></div>
									</div>
									<div class="text-end">
										<div>Paid: Rs <?php echo number_format($s['paid'],2); ?></div>
										<div>Unpaid: Rs <?php echo number_format($s['unpaid'],2); ?></div>
										<div>Total: Rs <?php echo number_format($s['total'],2); ?></div>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

</div>

</body>
</html>
