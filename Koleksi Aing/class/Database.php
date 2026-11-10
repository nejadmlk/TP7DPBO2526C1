<?php
class Database
{
    private $host = "localhost";
    private $dbname = "db_figure";
    private $username = "root";
    private $password = "";

    public function connect()
    {
        try {
            $db = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $db;
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . htmlspecialchars($e->getMessage()));
        }
    }
}
