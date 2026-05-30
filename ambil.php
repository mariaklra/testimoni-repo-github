<?php
// ============================================================
//  ambil.php — Endpoint mengambil daftar testimoni
//  File BARU: memisahkan logika fetch dari simpan
//  Mengembalikan JSON array testimoni dari database
// ============================================================

include 'koneksi.php';

header('Content-Type: application/json');

// Hanya terima GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan.']);
    exit;
}

// Ambil testimoni terbaru, limit 100 (bisa di-page nanti jika perlu)
$result = $conn->query(
    "SELECT id, emoji, pesan, rating, created_at
     FROM testimoni
     ORDER BY created_at DESC
     LIMIT 100"
);

if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data.']);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id'         => (int) $row['id'],
        'emoji'      => $row['emoji'],      // Emoji sudah whitelist, aman ditampilkan
        'pesan'      => htmlspecialchars($row['pesan'], ENT_QUOTES, 'UTF-8'), // Sanitasi output
        'rating'     => (int) $row['rating'],
        'created_at' => $row['created_at'],
    ];
}

echo json_encode(['status' => 'success', 'data' => $data]);

$conn->close();
