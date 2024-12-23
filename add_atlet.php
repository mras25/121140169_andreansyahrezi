<?php
require_once 'atlet.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi dan sanitasi input
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $speed = filter_input(INPUT_POST, 'speed', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    $technical = filter_input(INPUT_POST, 'technical', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    $intelligence = filter_input(INPUT_POST, 'intelligence', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    $shooting = filter_input(INPUT_POST, 'shooting', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    $passing = filter_input(INPUT_POST, 'passing', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    $defending = filter_input(INPUT_POST, 'defending', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);

    if ($name && $speed !== false && $technical !== false && $intelligence !== false && $shooting !== false && $passing !== false && $defending !== false) {
        $atlet = new Atlet();
        if ($atlet->addAtlet($name, $speed, $technical, $intelligence, $shooting, $passing, $defending)) {
            header("Location: atlet.html");
            exit();
        } else {
            echo "Gagal menambahkan data atlet.";
        }
    } else {
        die("Data yang dimasukkan tidak valid!");
    }
} else {
    die("Akses tidak valid.");
}
?>
