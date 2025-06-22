<?php
session_start();
include 'koneksi.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin'] = $user['username'];
            header("Location: kelolamenu.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Wave Cafe</title>
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

        .login-container {
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
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login Admin</h2>
    
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <div class="bottom-text">
        Belum punya akun? <a href="register.php">Register</a>
    </div>
</div>

</body>
</html>
