-- Buat database
CREATE DATABASE IF NOT EXISTS bukutamu;
USE bukutamu;

-- Buat tabel entries
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contoh data pengguna (username: admin, password: admin123)
-- Password sudah di-hash menggunakan PASSWORD_DEFAULT (bisa pakai password_hash() di PHP)
