<?php
include 'koneksi.php';

$pesan = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $pesan = "<div class='success'>Register berhasil. <a href='login.php'>Login sekarang</a></div>";
    } else {
        $pesan = "<div class='error'>Gagal register: " . htmlspecialchars($stmt->error) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Admin - Wave Cafe</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
            background: url('video/wave-cafe-video-bg.mp4') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .register-container {
            background-color: rgba(255, 255, 255, 0.9);
            width: 350px;
            padding: 30px;
            margin: 100px auto;
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            color: #2c7a6e;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2c7a6e;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #24655b;
        }

        .bottom-text {
            text-align: center;
            margin-top: 15px;
        }

        .error {
            background-color: #f8d7da;
            padding: 10px;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .success {
            background-color: #d4edda;
            padding: 10px;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register Admin</h2>
    
    <?= $pesan ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>

    <div class="bottom-text">
        Sudah punya akun? <a href="login.php">Login sekarang</a>
    </div>
</div>

</body>
</html>
