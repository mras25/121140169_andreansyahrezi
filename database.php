<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "atlet_db";
    private $conn; // Properti koneksi

    public function __construct() {
        // Membuat koneksi ke database
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Periksa koneksi
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    // Fungsi untuk mengembalikan koneksi database
    public function getConnection() {
        return $this->conn;
    }

    // Fungsi untuk menjalankan query
    public function executeQuery($sql) {
        $result = $this->conn->query($sql);
        if ($result === false) {
            throw new Exception("Error executing query: " . $this->conn->error);
        }
        return $result;
    }

    // Fungsi untuk menyiapkan query yang menggunakan prepared statement
    public function prepareStatement($sql) {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $this->conn->error);
        }
        return $stmt;
    }

    // Fungsi untuk menutup koneksi database
    public function close() {
        if ($this->conn) {
            $this->conn->close();
            $this->conn = null; // Atur koneksi menjadi null setelah ditutup
        }
    }

    // Destructor memastikan koneksi ditutup
    public function __destruct() {
        $this->close();
    }
}
?>
