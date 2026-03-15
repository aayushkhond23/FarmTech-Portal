<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Farmer Portal Login</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
body{
	/* Use provided AVIF as background; falls back to gradient if missing */
	background-image: url('img/photo-1574943320219-553eb213f72d.avif');
	background-size: cover;
	background-position: center;
	background-attachment: scroll;
	height:100vh;
	margin:0;
	position:relative;
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
	color:#213b21;
}
.card{
	padding:30px;
	border-radius:16px;
	width:400px;
	/* right-center positioning */
	position:fixed;
	right:2rem;
	top:50%;
	transform:translateY(-50%);
	background: rgba(255,255,255,0.85);
	box-shadow: 0 12px 30px rgba(15,23,14,0.22);
	border: 1px solid rgba(255,255,255,0.6);
	backdrop-filter: blur(6px) saturate(120%);
	transition: transform .22s ease, box-shadow .22s ease;
}
.card:hover{
	transform: translateY(-52%) scale(1.01);
	box-shadow: 0 20px 50px rgba(15,23,14,0.28);
}
.card .logo{
	width:72px;height:72px;border-radius:50%;background:linear-gradient(180deg, rgba(46,125,50,0.12), rgba(76,175,80,0.08));display:block;margin:0 auto 12px;box-shadow: inset 0 -6px 12px rgba(0,0,0,0.03);
}
.card h3{ text-align:center;margin-bottom:0.75rem;color:#1b5e20;font-weight:700; }
.form-control:focus{ box-shadow: 0 0 0 0.18rem rgba(46,125,50,0.16); border-color:#2e7d32; }
.btn-success{ background: linear-gradient(90deg,#2e7d32,#4caf50); border:none; }
.btn-success:hover{ filter:brightness(0.96); }
.text-center a{ color:#2e7d32; font-weight:600; }

/* Responsive: center the card on narrow screens */
@media (max-width: 576px) {
	.card{
		position:static;
		transform:none;
		margin: 2rem auto;
		width:90%;
		background: rgba(255,255,255,0.96);
	}
}
.bg-phrase{
	position: fixed;
	left: 2rem;
	top: 50%;
	transform: translateY(-50%);
	max-width: 40%;
	font-size: 2.2rem;
	line-height: 1.15;
	color: #000;
	font-weight: 700;
	pointer-events: none;
	white-space: pre-wrap;
	font-family: 'Noto Sans Devanagari', 'Mangal', 'Lohit Devanagari', serif;
}
@media (max-width: 992px){ .bg-phrase{ display:none; } }
</style>
</head>
<body>

<div class="bg-phrase">मातीशी नातं आपलं, घाम गाळू निष्ठेनं, शेतकरी राजा आपण, सजवू सृष्टी निजधनानं.</div>

<div class="card shadow" style="width:400px">
<h3 class="text-center mb-3">Login</h3>

<form action="login_process.php" method="POST">

<div class="mb-3">
<input type="text" name="username" class="form-control" placeholder="Username" required>
</div>

<div class="mb-3">
<input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<div class="mb-3">
<select name="role" class="form-control">
<option value="farmer">Login as Farmer</option>
<option value="admin">Login as Admin</option>
</select>
</div>

<button class="btn btn-success w-100">Login</button>

<p class="text-center mt-3">
<a href="register.php">Register Here</a>
</p>

</form>
</div>

</body>
</html>
