<?php
// ============================================================
//  koneksi.php — Konfigurasi Database
//  CATATAN KEAMANAN:
//  - Ganti $pass dengan password yang kuat di server production
//  - Gunakan user DB khusus (bukan root) dengan hak akses terbatas
//  - Idealnya simpan kredensial di .env atau file di luar web root
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // ⚠️ Ganti dengan user DB khusus di production
define('DB_PASS', '');           // ⚠️ Ganti dengan password yang kuat
define('DB_NAME', 'moodies_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    // Jangan tampilkan detail error ke user (bisa bocorkan info server)
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal.']);
    exit;
}
