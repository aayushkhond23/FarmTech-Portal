<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card p-4 mx-auto" style="width:400px">

<h3 class="text-center">Register</h3>

<form action="register_process.php" method="POST">

<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<select name="role" class="form-control mb-3">
<option value="farmer">Register as Farmer</option>
<option value="admin">Register as Admin</option>
</select>

<button class="btn btn-primary w-100">Register</button>

</form>

</div>
</div>

</body>
</html>
