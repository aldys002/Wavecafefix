<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
include 'koneksi.php';

// EDIT
$editMode = false;
$editData = null;
if (isset($_GET['edit'])) {
    $editMode = true;
    $stmt = $conn->prepare("SELECT * FROM menu WHERE id=?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// HAPUS
if (isset($_GET['hapus'])) {
    $conn->query("DELETE FROM menu WHERE id=" . intval($_GET['hapus']));
    header("Location: kelolamenu.php");
}

// TAMBAH / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];

    $gambar = '';
    if ($_FILES['gambar']['error'] === 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/' . $gambar);
    }

    if ($editMode) {
        if ($gambar == '' && $editData) $gambar = $editData['gambar'];
        $stmt = $conn->prepare("UPDATE menu SET nama=?, deskripsi=?, harga=?, kategori=?, gambar=? WHERE id=?");
        $stmt->bind_param("ssdssi", $nama, $deskripsi, $harga, $kategori, $gambar, $editData['id']);
    } else {
        $stmt = $conn->prepare("INSERT INTO menu (nama, deskripsi, harga, kategori, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $nama, $deskripsi, $harga, $kategori, $gambar);
    }
    $stmt->execute();
    header("Location: kelolamenu.php");
}

$menus = $conn->query("SELECT * FROM menu");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Menu</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #e7f7f2;
            margin: 0;
            padding: 30px;
            color: #2c3e50;
        }

        h2, h3 {
            color: #2c7a6e;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input[type=text], input[type=number], select, textarea, input[type=file] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #2c7a6e;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #24655b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        img.menu-img {
            width: 80px;
            border-radius: 6px;
        }

        .actions a {
            margin-right: 10px;
            color: #2c7a6e;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .logout {
            text-align: right;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logout">
        <p>
            Selamat datang, <?= $_SESSION['admin'] ?> | 
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </p>
    </div>

    <h2>Kelola Menu</h2>

    <h3><?= $editMode ? "Edit Menu" : "Tambah Menu" ?></h3>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="nama" placeholder="Nama Menu" required value="<?= $editData['nama'] ?? '' ?>">
        <textarea name="deskripsi" placeholder="Deskripsi"><?= $editData['deskripsi'] ?? '' ?></textarea>
        <input type="number" step="0.01" name="harga" placeholder="Harga" required value="<?= $editData['harga'] ?? '' ?>">
        <select name="kategori" required>
            <?php
            $kategoriList = ['cold', 'hot', 'juice', 'special'];
            foreach ($kategoriList as $k) {
                $selected = ($editData['kategori'] ?? '') == $k ? 'selected' : '';
                echo "<option value='$k' $selected>$k</option>";
            }
            ?>
        </select>
        <input type="file" name="gambar" accept="image/*">
        <?php if ($editMode && $editData['gambar']): ?>
            <p><img src="img/<?= $editData['gambar'] ?>" class="menu-img"></p>
        <?php endif; ?>
        <button type="submit"><?= $editMode ? "Simpan Perubahan" : "Tambah Menu" ?></button>
    </form>

    <h3>Daftar Menu</h3>
    <table>
        <tr>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php while ($m = $menus->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if ($m['gambar']): ?>
                    <img src="img/<?= $m['gambar'] ?>" class="menu-img">
                <?php else: ?>
                    <small>—</small>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($m['nama']) ?></td>
            <td><?= htmlspecialchars($m['deskripsi']) ?></td>
            <td><?= $m['harga'] ?></td>
            <td><?= $m['kategori'] ?></td>
            <td class="actions">
                <a href="?edit=<?= $m['id'] ?>">Edit</a>
                <a href="?hapus=<?= $m['id'] ?>" onclick="return confirm('Hapus menu ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>

</html>
