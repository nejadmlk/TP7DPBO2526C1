<?php
class Produk
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function tampil()
    {
        $sql = "SELECT p.*, b.nama_brand, k.nama_kategori
                FROM produk p
                INNER JOIN brand b ON p.id_brand = b.id_brand
                INNER JOIN kategori k ON p.id_kategori = k.id_kategori
                ORDER BY p.nama_produk ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function cari($id)
    {
        $sql = "SELECT p.*, b.nama_brand, k.nama_kategori
                FROM produk p
                INNER JOIN brand b ON p.id_brand = b.id_brand
                INNER JOIN kategori k ON p.id_kategori = k.id_kategori
                WHERE p.id_produk=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function tambah($data)
    {
        $sql = "INSERT INTO produk
                (id_brand, id_kategori, nama_produk, karakter, harga_beli,
                jumlah_dimiliki, skala, kondisi, gambar, deskripsi, tanggal_diperoleh)
                VALUES
                (:brand, :kategori, :nama, :karakter, :harga, :jumlah,
                :skala, :kondisi, :gambar, :deskripsi, :tanggal)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':brand' => $data['id_brand'],
            ':kategori' => $data['id_kategori'],
            ':nama' => $data['nama_produk'],
            ':karakter' => $data['karakter'],
            ':harga' => $data['harga_beli'],
            ':jumlah' => $data['jumlah_dimiliki'],
            ':skala' => $data['skala'],
            ':kondisi' => $data['kondisi'],
            ':gambar' => $data['gambar'],
            ':deskripsi' => $data['deskripsi'],
            ':tanggal' => $data['tanggal_diperoleh']
        ]);
    }

    public function ubah($id, $data)
    {
        $sql = "UPDATE produk SET id_brand=:brand, id_kategori=:kategori,
                nama_produk=:nama, karakter=:karakter, harga_beli=:harga,
                jumlah_dimiliki=:jumlah, skala=:skala, kondisi=:kondisi,
                gambar=:gambar, deskripsi=:deskripsi, tanggal_diperoleh=:tanggal
                WHERE id_produk=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':brand' => $data['id_brand'],
            ':kategori' => $data['id_kategori'],
            ':nama' => $data['nama_produk'],
            ':karakter' => $data['karakter'],
            ':harga' => $data['harga_beli'],
            ':jumlah' => $data['jumlah_dimiliki'],
            ':skala' => $data['skala'],
            ':kondisi' => $data['kondisi'],
            ':gambar' => $data['gambar'],
            ':deskripsi' => $data['deskripsi'],
            ':tanggal' => $data['tanggal_diperoleh'],
            ':id' => $id
        ]);
    }

    public function hapus($id)
    {
        $stmt = $this->db->prepare("DELETE FROM produk WHERE id_produk=:id");
        return $stmt->execute([':id' => $id]);
    }

    public function statistik()
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total_jenis, COALESCE(SUM(jumlah_dimiliki),0) AS total_unit,
             COALESCE(SUM(harga_beli * jumlah_dimiliki),0) AS total_nilai FROM produk"
        );
        $stmt->execute();
        return $stmt->fetch();
    }
}
