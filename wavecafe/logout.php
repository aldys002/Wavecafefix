<?php
session_start();
session_destroy();
header("Location: index.php"); // arahkan kembali ke dashboard atau halaman utama
exit;
