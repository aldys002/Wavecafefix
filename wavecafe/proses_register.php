<?php
session_start();
include 'koneksi.php';
$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, PASSWORD(?), 1)");
$stmt->bind_param("ss",$username,$password);
if ($stmt->execute()) header("Location: login.php?success=registered");
else echo "Register gagal: ".$conn->error;
?>
