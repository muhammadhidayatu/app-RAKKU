<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = $_FILES['image']['type'];

        if (in_array($fileType, $allowedTypes)) {
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $filePath = 'uploads/' . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $type = $_POST['type'];

                $racks = [
                    'baju' => ['A', 'B', 'C'],
                    'celana' => ['D', 'E', 'F']
                ];

                $randomRack = $racks[$type][array_rand($racks[$type])];
                $message = ucfirst($type) . " Anda berada di Rak $randomRack";

                // Simpan ke database
                $stmt = $conn->prepare("INSERT INTO search_history (image_path, type, rack) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $filePath, $type, $randomRack);
                $stmt->execute();
                $stmt->close();

                header("Location: index.php?status=success&message=" . urlencode($message));
                exit;
            }
        }
    }
}
?>