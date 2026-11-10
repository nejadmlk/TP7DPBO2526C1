<?php
class Kategori
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function tampil()
    {
        $stmt = $this->db->prepare("SELECT * FROM kategori ORDER BY nama_kategori ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function tambah($nama, $deskripsi)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO kategori (nama_kategori, deskripsi) VALUES (:nama, :deskripsi)"
        );
        return $stmt->execute([':nama' => $nama, ':deskripsi' => $deskripsi]);
    }

    public function ubah($id, $nama, $deskripsi)
    {
        $stmt = $this->db->prepare(
            "UPDATE kategori SET nama_kategori=:nama, deskripsi=:deskripsi WHERE id_kategori=:id"
        );
        return $stmt->execute([
            ':nama' => $nama,
            ':deskripsi' => $deskripsi,
            ':id' => $id
        ]);
    }

    public function hapus($id)
    {
        $stmt = $this->db->prepare("DELETE FROM kategori WHERE id_kategori=:id");
        return $stmt->execute([':id' => $id]);
    }

    public function cari($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM kategori WHERE id_kategori=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
