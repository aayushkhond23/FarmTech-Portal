<?php
include "db.php";

$username=$_POST['username'];
$password=$_POST['password'];
$role=$_POST['role'];

$sql="INSERT INTO users(username,password,role) VALUES('$username','$password','$role')";

$conn->query($sql);

header("Location:index.php");
?>
