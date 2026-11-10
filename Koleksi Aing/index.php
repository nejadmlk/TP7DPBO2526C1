<?php
require_once __DIR__ . '/config/database.php';

// Fungsi untuk mengamankan output HTML
function e($nilai)
{
    return htmlspecialchars((string)($nilai ?? ''), ENT_QUOTES, 'UTF-8');
}

// Menentukan halaman yang sedang dibuka
$halaman = $_GET['halaman'] ?? 'dashboard';

$pesan = '';
$error = '';

// Memproses data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    try {
        // ================================
        // CRUD BRAND
        // ================================
        if ($aksi === 'tambah_brand') {
            $brand->tambah(
                trim($_POST['nama_brand']),
                trim($_POST['negara_asal']),
                trim($_POST['deskripsi'])
            );

            $pesan = 'Brand berhasil ditambahkan.';
            $halaman = 'brand';

        } elseif ($aksi === 'ubah_brand') {
            $brand->ubah(
                (int)$_POST['id_brand'],
                trim($_POST['nama_brand']),
                trim($_POST['negara_asal']),
                trim($_POST['deskripsi'])
            );

            $pesan = 'Brand berhasil diperbarui.';
            $halaman = 'brand';

        } elseif ($aksi === 'hapus_brand') {
            $brand->hapus((int)$_POST['id_brand']);

            $pesan = 'Brand berhasil dihapus.';
            $halaman = 'brand';

        // ================================
        // CRUD KATEGORI
        // ================================
        } elseif ($aksi === 'tambah_kategori') {
            $kategori->tambah(
                trim($_POST['nama_kategori']),
                trim($_POST['deskripsi'])
            );

            $pesan = 'Kategori berhasil ditambahkan.';
            $halaman = 'kategori';

        } elseif ($aksi === 'ubah_kategori') {
            $kategori->ubah(
                (int)$_POST['id_kategori'],
                trim($_POST['nama_kategori']),
                trim($_POST['deskripsi'])
            );

            $pesan = 'Kategori berhasil diperbarui.';
            $halaman = 'kategori';

        } elseif ($aksi === 'hapus_kategori') {
            $kategori->hapus((int)$_POST['id_kategori']);

            $pesan = 'Kategori berhasil dihapus.';
            $halaman = 'kategori';

        // ================================
        // CRUD PRODUK
        // ================================
        } elseif ($aksi === 'tambah_produk' || $aksi === 'ubah_produk') {

            // Menentukan gambar lama
            $gambar = trim($_POST['gambar_lama'] ?? '');

            // Proses upload gambar
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $ekstensi = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $izin = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($ekstensi, $izin, true)) {
                    throw new Exception('Format gambar harus JPG, JPEG, PNG, atau WEBP.');
                }

                if ($_FILES['gambar']['size'] > 3 * 1024 * 1024) {
                    throw new Exception('Ukuran gambar maksimal 3 MB.');
                }

                $namaFile = uniqid('figure_', true) . '.' . $ekstensi;

                if (!move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/uploads/' . $namaFile)) {
                    throw new Exception('Gambar gagal diunggah.');
                }

                $gambar = $namaFile;
            }

            // Memulai transaksi database
            $pdo->beginTransaction();

            try {
                // Menentukan ID Brand
                if (($_POST['pilihan_brand'] ?? '') === 'baru') {
                    $namaBrand = trim($_POST['nama_brand_baru'] ?? '');
                    $negaraAsal = trim($_POST['negara_asal_baru'] ?? '');
                    $deskripsiBrand = trim($_POST['deskripsi_brand_baru'] ?? '');

                    if ($namaBrand === '' || $negaraAsal === '') {
                        throw new Exception('Nama brand dan negara asal wajib diisi.');
                    }

                    $brand->tambah($namaBrand, $negaraAsal, $deskripsiBrand);

                    $daftarBrand = $brand->tampil();
                    $idBrand = 0;

                    foreach ($daftarBrand as $b) {
                        if ($b['nama_brand'] === $namaBrand) {
                            $idBrand = (int)$b['id_brand'];
                            break;
                        }
                    }

                    if ($idBrand <= 0) {
                        throw new Exception('Brand baru gagal ditemukan.');
                    }

                } else {
                    $idBrand = (int)($_POST['pilihan_brand'] ?? 0);

                    if (!$brand->cari($idBrand)) {
                        throw new Exception('Brand yang dipilih tidak ditemukan.');
                    }
                }

                // Menentukan ID Kategori
                if (($_POST['pilihan_kategori'] ?? '') === 'baru') {
                    $namaKategori = trim($_POST['nama_kategori_baru'] ?? '');
                    $deskripsiKategori = trim($_POST['deskripsi_kategori_baru'] ?? '');

                    if ($namaKategori === '') {
                        throw new Exception('Nama kategori wajib diisi.');
                    }

                    $kategori->tambah($namaKategori, $deskripsiKategori);

                    $daftarKategori = $kategori->tampil();
                    $idKategori = 0;

                    foreach ($daftarKategori as $k) {
                        if ($k['nama_kategori'] === $namaKategori) {
                            $idKategori = (int)$k['id_kategori'];
                            break;
                        }
                    }

                    if ($idKategori <= 0) {
                        throw new Exception('Kategori baru gagal ditemukan.');
                    }

                } else {
                    $idKategori = (int)($_POST['pilihan_kategori'] ?? 0);

                    if (!$kategori->cari($idKategori)) {
                        throw new Exception('Kategori yang dipilih tidak ditemukan.');
                    }
                }

                // Mengumpulkan data produk
                $data = [
                    'id_brand' => $idBrand,
                    'id_kategori' => $idKategori,
                    'nama_produk' => trim($_POST['nama_produk'] ?? ''),
                    'karakter' => trim($_POST['karakter'] ?? ''),
                    'harga_beli' => (float)($_POST['harga_beli'] ?? 0),
                    'jumlah_dimiliki' => (int)($_POST['jumlah_dimiliki'] ?? 0),
                    'skala' => trim($_POST['skala'] ?? ''),
                    'kondisi' => trim($_POST['kondisi'] ?? 'Baru'),
                    'gambar' => $gambar,
                    'deskripsi' => trim($_POST['deskripsi'] ?? ''),
                    'tanggal_diperoleh' => !empty($_POST['tanggal_diperoleh'])
                        ? $_POST['tanggal_diperoleh']
                        : null
                ];

                // Validasi data produk
                if (
                    $data['nama_produk'] === '' ||
                    $data['karakter'] === '' ||
                    $data['jumlah_dimiliki'] < 0 ||
                    $data['harga_beli'] < 0
                ) {
                    throw new Exception('Periksa kembali nama, karakter, harga, dan jumlah koleksi.');
                }

                // Menyimpan atau memperbarui produk
                if ($aksi === 'tambah_produk') {
                    $produk->tambah($data);
                    $pesan = 'Produk berhasil ditambahkan.';
                } else {
                    $produk->ubah((int)$_POST['id_produk'], $data);
                    $pesan = 'Produk berhasil diperbarui.';
                }

                // Menyelesaikan transaksi
                $pdo->commit();

                $halaman = 'produk';

            } catch (Throwable $ex) {
                // Membatalkan semua perubahan jika terjadi kesalahan
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                throw $ex;
            }

        // ================================
        // HAPUS PRODUK
        // ================================
        } elseif ($aksi === 'hapus_produk') {
            $produk->hapus((int)$_POST['id_produk']);

            $pesan = 'Produk berhasil dihapus.';
            $halaman = 'produk';
        }

    } catch (PDOException $ex) {
        $error = 'Operasi gagal. Data mungkin masih digunakan oleh produk lain.';

    } catch (Exception $ex) {
        $error = $ex->getMessage();
    }
}

// ================================
// MENGAMBIL DATA DARI DATABASE
// ================================
$daftarBrand = $brand->tampil();
$daftarKategori = $kategori->tampil();
$daftarProduk = $produk->tampil();

$statistik = $produk->statistik();

// ================================
// DATA UNTUK EDIT DAN DETAIL
// ================================
$editBrand = ($halaman === 'edit_brand')
    ? $brand->cari((int)($_GET['id'] ?? 0))
    : false;

$editKategori = ($halaman === 'edit_kategori')
    ? $kategori->cari((int)($_GET['id'] ?? 0))
    : false;

$editProduk = (in_array($halaman, ['edit_produk', 'detail_produk'], true))
    ? $produk->cari((int)($_GET['id'] ?? 0))
    : false;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Aing</title>

    <!-- Terapkan tema sebelum halaman tampil agar tidak berkedip -->
    <script>
        (function () {
            const temaTersimpan = localStorage.getItem('tema') || 'light';
            document.documentElement.setAttribute('data-theme', temaTersimpan);
        })();
    </script>

    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="layout">

    <!-- NAVBAR UTAMA -->
    <header class="navbar">

        <!-- Logo -->
        <div class="logo">
            Koleksi Aing
        </div>

        <!-- Menu navigasi -->
        <nav class="nav-menu">
            <a
                class="<?= $halaman === 'dashboard' ? 'active' : '' ?>"
                href="index.php">
                Dashboard
            </a>

            <a
                class="<?= in_array($halaman, ['produk', 'tambah_produk', 'edit_produk', 'detail_produk']) ? 'active' : '' ?>"
                href="index.php?halaman=produk">
                Koleksi Produk
            </a>

            <a
                class="<?= in_array($halaman, ['brand', 'edit_brand']) ? 'active' : '' ?>"
                href="index.php?halaman=brand">
                Brand
            </a>

            <a
                class="<?= in_array($halaman, ['kategori', 'edit_kategori']) ? 'active' : '' ?>"
                href="index.php?halaman=kategori">
                Kategori
            </a>
        </nav>

        <!-- Tombol Dark Mode -->
        <button type="button" id="themeToggle" class="theme-toggle">
            <span class="theme-icon">🌙</span>
            <span class="theme-label">Dark Mode</span>
        </button>

    </header>

    <!-- KONTEN UTAMA -->
    <main class="main">

        <!-- Judul halaman -->
        <header class="topbar">
            <div>
                <div class="eyebrow">
                    PERSONAL COLLECTION MANAGER
                </div>

                <h1>
                    <?= $halaman === 'dashboard'
                        ? 'Dashboard'
                        : 'Manajemen ' . e(ucwords(str_replace('_', ' ', $halaman))) ?>
                </h1>
            </div>

        </header>

        <!-- NOTIFIKASI -->
        <?php if ($pesan): ?>
            <div class="alert success">
                <?= e($pesan) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <!-- PEMANGGILAN HALAMAN -->
        <?php if ($halaman === 'dashboard'): ?>
            <?php include __DIR__ . '/view/dashboard.php'; ?>

        <?php elseif ($halaman === 'produk'): ?>
            <?php include __DIR__ . '/view/produk.php'; ?>

        <?php elseif ($halaman === 'tambah_produk' || $halaman === 'edit_produk'): ?>
            <?php include __DIR__ . '/view/form_produk.php'; ?>

        <?php elseif ($halaman === 'detail_produk'): ?>
            <?php include __DIR__ . '/view/detail_produk.php'; ?>

        <?php elseif ($halaman === 'brand' || $halaman === 'edit_brand'): ?>
            <?php include __DIR__ . '/view/brand.php'; ?>

        <?php elseif ($halaman === 'kategori' || $halaman === 'edit_kategori'): ?>
            <?php include __DIR__ . '/view/kategori.php'; ?>

        <?php else: ?>
            <div class="panel">
                Halaman tidak ditemukan.
            </div>
        <?php endif; ?>

    </main>
</div>

<!-- JavaScript Dark Mode -->
<script>
    const tombolTema = document.getElementById('themeToggle');
    const ikonTema = tombolTema.querySelector('.theme-icon');
    const labelTema = tombolTema.querySelector('.theme-label');

    function terapkanTema(tema) {
        document.documentElement.setAttribute('data-theme', tema);

        if (tema === 'dark') {
            ikonTema.textContent = '☀️';
            labelTema.textContent = 'Light Mode';
            tombolTema.setAttribute('aria-label', 'Aktifkan Light Mode');
        } else {
            ikonTema.textContent = '🌙';
            labelTema.textContent = 'Dark Mode';
            tombolTema.setAttribute('aria-label', 'Aktifkan Dark Mode');
        }
    }

    let temaAktif = localStorage.getItem('tema') || 'light';
    terapkanTema(temaAktif);

    tombolTema.addEventListener('click', function () {
        temaAktif = temaAktif === 'light' ? 'dark' : 'light';

        localStorage.setItem('tema', temaAktif);
        terapkanTema(temaAktif);
    });
</script>

</body>
</html>
