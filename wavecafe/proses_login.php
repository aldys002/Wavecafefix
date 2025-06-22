<?php
session_start();
include 'koneksi.php';
$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id FROM users WHERE username=? AND password=PASSWORD(?) AND is_admin=1");
$stmt->bind_param("ss",$username,$password);
$stmt->execute(); $stmt->store_result();

if ($stmt->num_rows === 1) {
  $_SESSION['admin'] = $username;
  header("Location: kelolamenu.php");
} else {
  header("Location: login.php?error=invalid");
}
?>
