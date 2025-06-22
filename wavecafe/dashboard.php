<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
include 'koneksi.php';

// Tambah Menu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if (!empty($gambar)) {
        move_uploaded_file($tmp, "img/" . $gambar);
    }

    $stmt = $conn->prepare("INSERT INTO menu (nama, deskripsi, harga, gambar) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $nama, $deskripsi, $harga, $gambar);
    $stmt->execute();
    header("Location: dashboard.php");
}

// Hapus Menu
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM menu WHERE id=$id");
    header("Location: dashboard.php");
}

// Ambil semua menu
$menus = $conn->query("SELECT * FROM menu");
?>

<h2>Kelola Menu</h2>
<p>Admin: <?= $_SESSION['admin'] ?> | <a href="proses_logout.php">Logout</a></p>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nama" placeholder="Nama Menu" required><br>
    <textarea name="deskripsi" placeholder="Deskripsi" required></textarea><br>
    <input type="number" step="0.01" name="harga" placeholder="Harga" required><br>
    <input type="file" name="gambar" accept="image/*"><br>
    <button type="submit" name="tambah">Tambah Menu</button>
</form>

<h3>Daftar Menu</h3>
<table border="1">
<tr><th>Nama</th><th>Deskripsi</th><th>Harga</th><th>Gambar</th><th>Aksi</th></tr>
<?php while ($row = $menus->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($row['nama']) ?></td>
  <td><?= htmlspecialchars($row['deskripsi']) ?></td>
  <td><?= $row['harga'] ?></td>
  <td><img src="img/<?= $row['gambar'] ?>" width="80"></td>
  <td><a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin?')">Hapus</a></td>
</tr>
<?php endwhile; ?>
</table>
