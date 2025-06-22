<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("UPDATE menu SET nama=?, harga=?, deskripsi=? WHERE id=?");
    $stmt->bind_param("sdsi", $nama, $harga, $deskripsi, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
}

$stmt = $conn->prepare("SELECT * FROM menu WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$menu = $result->fetch_assoc();
$stmt->close();
?>

<h2>Edit Menu</h2>
<form method="post">
    <input type="text" name="nama" value="<?= $menu['nama'] ?>" required><br>
    <input type="number" step="0.01" name="harga" value="<?= $menu['harga'] ?>" required><br>
    <textarea name="deskripsi"><?= $menu['deskripsi'] ?></textarea><br>
    <button type="submit">Simpan</button>
</form>
