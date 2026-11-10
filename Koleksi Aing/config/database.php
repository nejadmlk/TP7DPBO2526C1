
<?php
require_once __DIR__ . '/../class/Database.php';

// Membuat koneksi database
$database = new Database();
$pdo = $database->connect();

// Membuat objek class lainnya
require_once __DIR__ . '/../class/Brand.php';
require_once __DIR__ . '/../class/Kategori.php';
require_once __DIR__ . '/../class/Produk.php';

$brand = new Brand($pdo);
$kategori = new Kategori($pdo);
$produk = new Produk($pdo);
