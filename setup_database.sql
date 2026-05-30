-- ============================================================
--  setup_database.sql — Script inisialisasi database
--  File BARU: jalankan sekali di phpMyAdmin atau CLI MySQL
--  untuk membuat database dan tabel yang dibutuhkan
-- ============================================================

-- Buat database (kalau belum ada)
CREATE DATABASE IF NOT EXISTS moodies_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE moodies_db;

-- Buat tabel testimoni
CREATE TABLE IF NOT EXISTS testimoni (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    emoji      VARCHAR(10)   NOT NULL,
    pesan      VARCHAR(500)  NOT NULL,
    rating     TINYINT       NOT NULL CHECK (rating BETWEEN 1 AND 5),
    created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at DESC)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- OPSIONAL: Buat user database khusus (LEBIH AMAN dari root)
-- Jalankan sebagai root MySQL, ganti 'password_kuat' dengan
-- password yang benar-benar kuat
-- ============================================================
-- CREATE USER 'moodies_user'@'localhost' IDENTIFIED BY 'password_kuat';
-- GRANT SELECT, INSERT ON moodies_db.testimoni TO 'moodies_user'@'localhost';
-- FLUSH PRIVILEGES;
