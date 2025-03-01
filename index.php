<!DOCTYPE html>
<?php
session_start();
require 'config.php';

// Ambil riwayat pencarian dari database
$history = [];
$result = $conn->query("SELECT * FROM search_history ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAKKU</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">RAKKU</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Form Upload -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title text-center">Cari Rak Baju atau Celana</h3>
                    </div>
                    <div class="card-body">
                        <form action="process.php" method="POST" enctype="multipart/form-data">
                            <!-- Input Gambar -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Gambar Baju atau Celana</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            </div>

                            <!-- Pilihan Rak -->
                            <div class="mb-3">
                                <label class="form-label">Cari Rak Untuk:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="baju" value="baju" checked>
                                    <label class="form-check-label" for="baju">Baju</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="celana" value="celana">
                                    <label class="form-check-label" for="celana">Celana</label>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <button type="submit" class="btn btn-primary w-100">Cari Rak</button>
                        </form>

                        <!-- Notifikasi -->
                        <?php if (isset($_GET['status'])): ?>
                            <div class="alert alert-<?php echo $_GET['status']; ?> mt-4">
                                <?php echo htmlspecialchars($_GET['message']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Riwayat Pencarian -->
<div class="card shadow mt-4">
    <div class="card-header bg-secondary text-white">
        <h3 class="card-title text-center">Riwayat Pencarian</h3>
    </div>
    <div class="card-body">
        <?php if (count($history) > 0): ?>
            <div class="list-group">
                <?php foreach ($history as $item): ?>
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="<?php echo $item['image_path']; ?>" class="img-fluid rounded" alt="Gambar">
                            </div>
                            <div class="col-md-7">
                                <p class="mb-1"><?php echo ucfirst($item['type']); ?> ditemukan di Rak <?php echo $item['rack']; ?></p>
                                <small class="text-muted"><?php echo date('d M Y H:i:s', strtotime($item['created_at'])); ?></small>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <!-- Form untuk menghapus riwayat -->
                                <form action="delete_history.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');">
                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">Belum ada riwayat pencarian.</p>
        <?php endif; ?>
    </div>
</div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">© 2025 Rak Finder. All rights reserved.</p>
            <p class="mb-0">Contact: info@rakfinder.com</p>
        </div>
    </footer>

    <!-- Bootstrap JS (opsional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>