<?php
// Use a neutral background color/gradient for the farmer dashboard (no image)
$bgStyle = "background: linear-gradient(120deg,#e9f5ee,#d6edd8);";
?>
<!DOCTYPE html>
<html>
<head>
<title>Farmer Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body style="<?php echo $bgStyle; ?>">

<nav class="navbar navbar-dark bg-success">
	<div class="container d-flex justify-content-between align-items-center">
		<a class="navbar-brand text-white mb-0" href="#">Farmer Dashboard</a>
		<div class="d-flex align-items-center">
			<a href="logout.php" class="btn btn-light btn-sm">Logout</a>
		</div>
	</div>
</nav>

<!-- Weather widget (bottom-left) -->
<div id="weather-widget" class="card shadow-sm text-center" style="position:fixed;bottom:1rem;left:1rem;width:220px;z-index:1050;padding:0.5rem;">
	<div id="weather-loading" class="small text-muted">Loading weather...</div>
	<div id="weather-content" style="display:none;">
		<div id="weather-location" class="fw-bold"></div>
		<div id="weather-temp" style="font-size:1.4rem;"></div>
		<div id="weather-desc" class="small text-muted"></div>
	</div>
</div>

<div class="container mt-4">
	<!-- moved 7/12 button into Quick Links card header -->
</div>

    
	<div class="row mb-4">
		<div class="col-md-4">
			<div class="card p-3 shadow">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<h5 class="mb-0">Quick Links</h5>
						<a class="btn btn-sm btn-primary" href="https://bhulekh.mahabhumi.gov.in/" target="_blank" rel="noopener noreferrer">Open 7/12</a>
					</div>
					<ul class="list-group list-group-flush">
						<a class="list-group-item list-group-item-action" href="https://bhulekh.mahabhumi.gov.in/" target="_blank">7/12</a>
						<a class="list-group-item list-group-item-action" href="#">8/A</a>
						<a class="list-group-item list-group-item-action" href="#">View Farmer ID</a>
					</ul>
				</div>
		</div>

		<div class="col-md-4">
			<div class="card p-3 shadow">
				<h5>Add Crop</h5>
				<form action="add_crop.php" method="POST">
					<div class="mb-2">
						<label class="form-label small">Farm Name</label>
						<input name="farm_name" class="form-control" required>
					</div>
					<div class="mb-2">
						<label class="form-label small">Season</label>
						<select id="seasonSelect" name="season" class="form-select" required>
							<option value="karif">Karif</option>
							<div class="col-md-4">
								<div class="card p-3 shadow">
									<h5>Add Crop</h5>
									<form action="add_crop.php" method="POST">
										<div class="mb-2">
											<label class="form-label small">Farm Name</label>
											<input name="farm_name" class="form-control" required>
										</div>
										<div class="mb-2">
											<label class="form-label small">Season</label>
											<select id="seasonSelect" name="season" class="form-select" required>
												<option value="karif">Karif</option>
												<option value="rabi">Rabi</option>
											</select>
										</div>
										<div class="mb-2">
											<label class="form-label small">Crop Name</label>
											<select id="cropSelect" name="crop_name" class="form-select" required>
												<!-- options populated by JS -->
											</select>
										</div>
										<div class="mb-2">
											<label class="form-label small">Production Date</label>
											<input type="date" name="production_date" class="form-control" required>
										</div>
										<div class="mb-2">
											<label class="form-label small">Daily Production (kg)</label>
											<input type="number" step="0.01" min="0" name="daily_production" class="form-control" required>
										</div>
										<button class="btn btn-success w-100" type="submit">Add Crop</button>
									</form>
										<div class="mt-2 text-center">
											<a href="crop_details.php" class="btn btn-outline-secondary btn-sm">See Crop Details</a>
										</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="card p-3 shadow border-start border-4 border-info">
									<div class="d-flex justify-content-between align-items-center mb-2">
										<h5 class="mb-0">Add Labor</h5>
										<span class="badge bg-info text-dark small">Labor</span>
									</div>
									<form action="add_labor.php" method="POST">
										<div class="mb-2">
											<label class="form-label small">Labor Name</label>
											<input name="labor_name" class="form-control" required>
										</div>
										<div class="mb-2">
											<label class="form-label small">Select Crop</label>
											<select name="crop_name" id="laborCropSelect" class="form-select" required>
												<option value="kapas">Kapas</option>
												<option value="soyaben">Soyaben</option>
												<option value="tur">Tur</option>
												<option value="chana">Chana</option>
												<option value="onion">Onion</option>
												<option value="wheat">Wheat</option>
											</select>
										</div>
										<div class="mb-2">
											<label class="form-label small">Work Description</label>
											<input name="work_desc" class="form-control" placeholder="e.g., picking, threshing">
										</div>
										<div class="mb-2">
											<label class="form-label small">Farm Name</label>
											<input name="farm_name" class="form-control" required>
										</div>
										<div class="row gx-2">
											<div class="col-6 mb-2">
												<label class="form-label small">Weight (kg)</label>
												<input name="weight_kg" id="weightKg" type="number"class="form-control" required>
											</div>
											<div class="col-6 mb-2">
												<label class="form-label small">Price per kg (Rs)</label>
												<input name="price_per_kg" id="pricePerKg" type="number" step="1 min="0" class="form-control" required>
											</div>
										</div>
										<div class="mb-2">
											<label class="form-label small">Total Amount (Rs)</label>
											<input name="amount" id="totalAmount" class="form-control" readonly>
										</div>
										<button class="btn btn-primary w-100" type="submit">Add Labor Entry</button>
									</form>
									<div class="mt-2 text-center">
										<a href="labor_details.php" class="btn btn-outline-secondary btn-sm">View Labor Details</a>
									</div>
								</div>
							</div>
	const locEl = document.getElementById('weather-location');
	const tempEl = document.getElementById('weather-temp');
	const descEl = document.getElementById('weather-desc');

	const weatherCodeMap = {
		0: 'Clear', 1: 'Mainly clear', 2: 'Partly cloudy', 3: 'Overcast',
		45: 'Fog', 48: 'Depositing rime fog',
		51: 'Light drizzle', 53: 'Moderate drizzle', 55: 'Dense drizzle',
		61: 'Slight rain', 63: 'Moderate rain', 65: 'Heavy rain',
		71: 'Slight snow', 73: 'Moderate snow', 75: 'Heavy snow',
		80: 'Rain showers', 81: 'Heavy showers', 82: 'Violent showers'
	};

	function showError(msg){
		loadingEl.textContent = msg;
		contentEl.style.display = 'none';
	}

	function updateWeather(lat, lon){
		loadingEl.textContent = 'Fetching weather...';
		const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&timezone=auto`;
		fetch(url).then(r=>r.json()).then(data=>{
			if(!data || !data.current_weather){ showError('No weather data'); return; }
			const cw = data.current_weather;
			const code = cw.weathercode;
			const desc = weatherCodeMap[code] || 'Weather';
			locEl.textContent = (data.timezone || '') ;
			tempEl.textContent = `${cw.temperature}°C`;
			descEl.textContent = `${desc} • wind ${cw.windspeed} km/h`;
			loadingEl.style.display = 'none';
			contentEl.style.display = 'block';
		}).catch(err=>{
			showError('Weather fetch failed');
		});
	}

	const fallback = {lat: 6.9271, lon: 79.8612}; // fallback coords (can be changed)
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(pos=>{
			updateWeather(pos.coords.latitude, pos.coords.longitude);
			setInterval(()=>updateWeather(pos.coords.latitude, pos.coords.longitude), 10*60*1000);
		}, err=>{ updateWeather(fallback.lat, fallback.lon); setInterval(()=>updateWeather(fallback.lat, fallback.lon), 10*60*1000); });
	} else {
		updateWeather(fallback.lat, fallback.lon);
		setInterval(()=>updateWeather(fallback.lat, fallback.lon), 10*60*1000);
	}
})();
</script>

<script>
// populate crop select based on season
(function(){
	const seasonEl = document.getElementById('seasonSelect');
	const cropEl = document.getElementById('cropSelect');
	const options = {
		karif: [ 'soyaben', 'kapas', 'tur' ],
		rabi: [ 'chana', 'onion', 'wheat', 'maka' ]
	};
	function setOptions(s){
		cropEl.innerHTML = '';
		(options[s]||[]).forEach(function(c){
			const o = document.createElement('option'); o.value = c; o.textContent = c.charAt(0).toUpperCase() + c.slice(1);
			cropEl.appendChild(o);
		});
	}
	if(seasonEl) {
		setOptions(seasonEl.value);
		seasonEl.addEventListener('change', function(){ setOptions(this.value); });
	}
})();
</script>

<script>
// calculate labor total = weight * price
(function(){
	const w = document.getElementById('weightKg');
	const p = document.getElementById('pricePerKg');
	const total = document.getElementById('totalAmount');
	function sanitizeNumInput(el){
		if(!el) return '';
		// replace comma with dot, remove invalid chars
		let v = el.value.replace(',','.')
								.replace(/[^0-9.\-]/g,'');
		// allow only one dot
		const parts = v.split('.');
		if(parts.length>2) v = parts.shift() + '.' + parts.join('');
		el.value = v;
		return v;
	}
	function calc(){
		const wv = sanitizeNumInput(w);
		const pv = sanitizeNumInput(p);
		const wt = parseFloat(wv||0);
		const pr = parseFloat(pv||0);
		const amt = (isNaN(wt) || isNaN(pr))?0:(wt * pr);
		if(total) total.value = amt.toFixed(2);
	}
	if(w) w.addEventListener('input', calc);
	if(p) p.addEventListener('input', calc);
	calc();
})();
</script>


</body>
</html>
