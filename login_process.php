<?php
include "db.php";

$username=$_POST['username'];
$password=$_POST['password'];
$role=$_POST['role'];

$sql="SELECT * FROM users WHERE username='$username' AND password='$password' AND role='$role'";

$result=$conn->query($sql);

if($result->num_rows>0){

if($role=="farmer"){
header("Location:farmer_dashboard.php");
}
else{
header("Location:admin_dashboard.php");
}

}
else{
echo "Invalid Login";
}
?>
