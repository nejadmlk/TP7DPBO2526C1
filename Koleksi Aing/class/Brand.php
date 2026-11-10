<?php
class Brand
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function tampil()
    {
        $stmt = $this->db->prepare("SELECT * FROM brand ORDER BY nama_brand ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function tambah($nama, $negara, $deskripsi)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO brand (nama_brand, negara_asal, deskripsi) VALUES (:nama, :negara, :deskripsi)"
        );
        return $stmt->execute([
            ':nama' => $nama,
            ':negara' => $negara,
            ':deskripsi' => $deskripsi
        ]);
    }

    public function ubah($id, $nama, $negara, $deskripsi)
    {
        $stmt = $this->db->prepare(
            "UPDATE brand SET nama_brand=:nama, negara_asal=:negara, deskripsi=:deskripsi WHERE id_brand=:id"
        );
        return $stmt->execute([
            ':nama' => $nama,
            ':negara' => $negara,
            ':deskripsi' => $deskripsi,
            ':id' => $id
        ]);
    }

    public function hapus($id)
    {
        $stmt = $this->db->prepare("DELETE FROM brand WHERE id_brand=:id");
        return $stmt->execute([':id' => $id]);
    }

    public function cari($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM brand WHERE id_brand=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
