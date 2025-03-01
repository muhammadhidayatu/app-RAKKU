_<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil ID riwayat yang akan dihapus
    $id = $_POST['id'];

    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM search_history WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman utama dengan pesan sukses
        header("Location: index.php?status=success&message=Riwayat berhasil dihapus");
    } else {
        // Redirect kembali ke halaman utama dengan pesan error
        header("Location: index.php?status=danger&message=Gagal menghapus riwayat");
    }

    $stmt->close();
    exit;
}
?>