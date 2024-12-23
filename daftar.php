<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $country = $_POST['country'] ?? '';

    // Validasi data
    if (empty($username) || empty($email) || empty($password) || empty($gender) || empty($country)) {
        die('Semua field harus diisi!');
    }

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Format email tidak valid!');
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Simpan ke database
    try {
        $db = new Database(); // Membuat instance database

        $sql = "INSERT INTO users (username, email, password, gender, country) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepareStatement($sql); // Menggunakan metode prepareStatement dari kelas Database
        $stmt->bind_param('sssss', $username, $email, $hashedPassword, $gender, $country);

        if ($stmt->execute()) {
            echo 'Pendaftaran berhasil. <a href="login.html">Klik di sini untuk login</a>.';
        } else {
            echo 'Gagal mendaftar: ' . $stmt->error;
        }

        $stmt->close(); // Tutup statement
        $db->close();   // Tutup koneksi
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
} else {
    die('Metode tidak diizinkan.');
}
?>
