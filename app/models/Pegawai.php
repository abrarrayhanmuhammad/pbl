<?php

class Pegawai
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM pegawai WHERE username = :username LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        $pegawai = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pegawai ?: null;
    }
}