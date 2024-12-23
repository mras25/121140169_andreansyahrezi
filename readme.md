Bagian 1: Client-side Programming (Bobot: 30%)
1.1 Manipulasi DOM dengan JavaScript (15%)
a. Form dengan minimal 4 elemen input
✔ Memenuhi: Form di file daftar.html memiliki 4 elemen input (teks, radio, select, dan password).
Kode:

contoh:1
<form id="userForm" action="daftar.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <label>Jenis Kelamin:</label>
    <label class="gender-label">
        <input type="radio" id="male" name="gender" value="male" required>
        Laki-laki
    </label>
    <label class="gender-label">
        <input type="radio" id="female" name="gender" value="female" required>
        Perempuan
    </label><br>

    <label for="country">Negara:</label>
    <select id="country" name="country" required>
        <option value="indonesia">Indonesia</option>
        <option value="malaysia">Malaysia</option>
        <option value="singapore">Singapura</option>
    </select><br>
</form>

contoh 2:

function openEditModal(data) {
    document.getElementById('edit-id').value = data.id;
    document.getElementById('edit-name').value = data.name;
    document.getElementById('edit-speed').value = data.speed;
    document.getElementById('edit-technical').value = data.technical;
    document.getElementById('edit-intelligence').value = data.intelligence;
    document.getElementById('edit-shooting').value = data.shooting;
    document.getElementById('edit-passing').value = data.passing;
    document.getElementById('edit-defending').value = data.defending;
    document.getElementById('overlay').classList.add('active');
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('overlay').classList.remove('active');
    document.getElementById('editModal').classList.remove('active');
}



b. Tampilkan data dari server ke tabel HTML
✔ Validasi dilakukan di sisi server menggunakan PHP:
if (empty($username) || empty($email) || empty($password) || empty($gender) || empty($country)) {
    die('Semua field harus diisi!');
}




c. Manipulasi DOM dengan JavaScript
✔ Memenuhi: File daftar.html memiliki manipulasi DOM sederhana untuk validasi.
Kode:

document.getElementById('userForm').addEventListener('submit', function(event) {
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!username || !email || !password) {
        alert('Username, Email, dan Password harus diisi!');
        event.preventDefault(); // Mencegah pengiriman form jika validasi gagal
    }
});


1.2 Event Handling (15%)
a. Minimal 3 event pada form
✔ Memenuhi: Terdapat submit, DOMContentLoaded, dan onclick.

Letak Kode: Event onclick untuk membuka modal edit
<button type="button" onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>

Fungsi openEditModal digunakan untuk menampilkan modal dengan data yang sudah ada:
function openEditModal(data) {
    document.getElementById('edit-id').value = data.id;
    ...
    document.getElementById('editModal').classList.add('active');
}

Event validasi dan penghapusan data juga ditangani di sisi klien:
<a href="atlet.php?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>

b. Validasi input sebelum diproses PHP
✔ Memenuhi: Validasi input di sisi client menggunakan JavaScript.
Kode:

document.addEventListener('DOMContentLoaded', function () {
    const username = localStorage.getItem('username');
    if (username) {
        document.getElementById('welcomeMessage').innerText = `Selamat datang kembali, ${username}!`;
    }
});

document.getElementById('userForm').addEventListener('submit', function(event) {
    alert('Form sedang dikirim!');
    event.preventDefault();
});

Bagian 2: Server-side Programming (Bobot: 30%)
2.1 Pengelolaan Data dengan PHP (20%)
a. Gunakan metode POST atau GET
✔ Memenuhi: Letak Kode: Implementasi pengelolaan data menggunakan metode POST di daftar.php dan atlet.php:

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atlet->addAtlet($_POST['name'], $_POST['speed'], $_POST['technical'], ...);
}


b. Validasi sisi server
✔ Memenuhi: Validasi input di server.

kode:

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        die('Semua field harus diisi!');
    }
}


c. Simpan data ke database (termasuk IP dan browser)
✔ Memenuhi: Browser dan IP dapat ditambahkan dengan:

$ipAddress = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$sql = "INSERT INTO users (username, email, password, ip_address, browser) VALUES (?, ?, ?, ?, ?)";

2.2 Objek PHP Berbasis OOP (10%)
✔ Memenuhi: Objek Database dengan metode getConnection.
Kode:

class Database {
    public function getConnection() {
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}


Class OOP: Atlet digunakan untuk CRUD pada database:

class Atlet {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }
    ...
    public function addAtlet($name, $speed, ...) {
        $sql = "INSERT INTO atlet (name, speed, ...) VALUES (?, ?, ...)";
        ...
    }
}

Bagian 3: Database Management (Bobot: 20%)
3.1 Pembuatan Tabel Database (5%)
✔ Memenuhi: Tabel users dapat dibuat dengan:

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    browser VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


3.2 Konfigurasi Koneksi Database (5%)
✔ Memenuhi: Konfigurasi koneksi di kelas Database.

class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "atlet_db";
    private $conn;

    public function __construct() {
        // Membuat koneksi ke database
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Periksa koneksi
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }
}


3.3 Manipulasi Data pada Database (10%)
✔ Memenuhi: Query SELECT dan INSERT digunakan dengan Prepared Statement.

Contoh Query INSERT (Tambah Data Atlet):
$sql = "INSERT INTO atlet (name, speed, technical, intelligence, shooting, passing, defending, browser, ip_address) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $this->db->prepareStatement($sql);
$stmt->bind_param("siiiiisss", $name, $speed, $technical, $intelligence, $shooting, $passing, $defending, $browser, $ip_address);
$stmt->execute();


Bagian 4: State Management (Bobot: 20%)
4.1 State Management dengan Session (10%)
✔ Memenuhi: Penggunaan $_SESSION di login.php.
Kode:

$_SESSION['user_id'] = $userId;
$_SESSION['username'] = $fetchedUsername;

4.2 Cookie dan Browser Storage (10%)
✔ Memenuhi: Cookie dan localStorage diterapkan.
Kode untuk cookie:

setcookie('username', $fetchedUsername, time() + (86400 * 30), "/");

Kode untuk localStorage:

localStorage.setItem('username', username);
