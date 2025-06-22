

-- Tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') NOT NULL DEFAULT 'admin'
);

-- Tabel menu
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2),
    gambar VARCHAR(255),
    kategori ENUM('cold','hot','juice','special') NOT NULL
);

