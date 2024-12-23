<?php
require_once 'Database.php';

// Mulai session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi field kosong
    if (empty($username) || empty($email) || empty($password)) {
        die('Semua field harus diisi!');
    }

    $db = new Database();

    try {
        $conn = $db->getConnection(); // Mendapatkan koneksi melalui metode getConnection
        $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->bind_result($userId, $fetchedUsername, $hashedPassword);

        if ($stmt->fetch()) {
            // Verifikasi password
            if (password_verify($password, $hashedPassword)) {
                // Simpan informasi ke session
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $fetchedUsername;

                // Set cookie untuk menyimpan username
                setcookie('username', $fetchedUsername, time() + (86400 * 30), "/"); // 30 hari

                // Redirect ke halaman atlet
                header('Location: atlet.php');
                exit();
            } else {
                echo 'Password salah.';
            }
        } else {
            echo 'Username atau Email tidak ditemukan.';
        }

        $stmt->close();
    } catch (Exception $e) {
        die('Terjadi kesalahan: ' . $e->getMessage());
    } finally {
        $db->close();
    }
} else {
    die('Metode tidak diizinkan.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script>
        // Simpan username ke localStorage
        function saveToLocalStorage(username) {
            localStorage.setItem('username', username);
        }

        // Ambil username dari localStorage
        function getFromLocalStorage() {
            return localStorage.getItem('username');
        }

        // Tampilkan pesan selamat datang jika username ada di localStorage
        document.addEventListener('DOMContentLoaded', function () {
            const username = getFromLocalStorage();
            if (username) {
                document.getElementById('welcomeMessage').innerText = `Selamat datang kembali, ${username}!`;
            }
        });
    </script>
</head>
<body>
    <!-- Tampilkan pesan selamat datang -->
    <div id="welcomeMessage"></div>

    <!-- Form login -->
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit" onclick="saveToLocalStorage(document.getElementById('username').value)">
            Login
        </button>
    </form>
</body>
</html>
