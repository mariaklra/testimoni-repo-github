<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitasi input dasar
    $emoji = $_POST['emoji'];
    $pesan = trim($_POST['pesan']);
    $rating = (int)$_POST['rating']; // Paksa jadi angka

    if (empty($pesan) || $rating < 1 || $rating > 5) {
        die("Data tidak valid");
    }

    // Menggunakan Prepared Statement (SANGAT AMAN)
    $stmt = $conn->prepare("INSERT INTO testimoni (emoji, pesan, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $emoji, $pesan, $rating);
    
    if ($stmt->execute()) {
        echo "Berhasil";
    } else {
        echo "Gagal: " . $stmt->error;
    }
    $stmt->close();
}
?>