<?php
// ============================================================
//  simpan.php — Endpoint menyimpan testimoni baru
//  Fix keamanan: Prepared Statements, validasi server-side,
//  sanitasi input, rate limiting via session, JSON response
// ============================================================

session_start();
include 'koneksi.php';

header('Content-Type: application/json');

// Hanya terima POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan.']);
    exit;
}

// --- RATE LIMITING SEDERHANA ---
// Batasi 1 submit per 30 detik per sesi (mencegah spam)
$now = time();
$cooldown = 30; // detik
if (isset($_SESSION['last_submit']) && ($now - $_SESSION['last_submit']) < $cooldown) {
    $sisa = $cooldown - ($now - $_SESSION['last_submit']);
    http_response_code(429);
    echo json_encode([
        'status'  => 'error',
        'message' => "Terlalu banyak permintaan. Coba lagi dalam {$sisa} detik."
    ]);
    exit;
}

// --- AMBIL & SANITASI INPUT ---
$emoji  = trim($_POST['emoji']  ?? '');
$pesan  = trim($_POST['pesan']  ?? '');
$rating = intval($_POST['rating'] ?? 0);

// --- VALIDASI SERVER-SIDE ---
$allowed_emojis = ['🪻', '💐', '🎀', '🎐', '🍡', '🍨', '🌸', '🐰', '🐱', '🎏'];

if (!in_array($emoji, $allowed_emojis, true)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Emoji tidak valid.']);
    exit;
}

if (empty($pesan)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Ulasan tidak boleh kosong.']);
    exit;
}

if (mb_strlen($pesan) > 500) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Ulasan maksimal 500 karakter.']);
    exit;
}

if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Rating harus antara 1 sampai 5.']);
    exit;
}

// --- SIMPAN KE DATABASE (Prepared Statement — aman dari SQL Injection) ---
$stmt = $conn->prepare(
    "INSERT INTO testimoni (emoji, pesan, rating, created_at) VALUES (?, ?, ?, NOW())"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan server.']);
    exit;
}

$stmt->bind_param('ssi', $emoji, $pesan, $rating);

if ($stmt->execute()) {
    // Update timestamp rate limiting
    $_SESSION['last_submit'] = $now;
    echo json_encode(['status' => 'success', 'message' => 'Testimoni berhasil disimpan!']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan testimoni.']);
}

$stmt->close();
$conn->close();
