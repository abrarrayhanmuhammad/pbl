<?php

class Pembayaran
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getTotalPendapatan(): float
    {
        $sql = "
            SELECT COALESCE(SUM(total), 0)
            FROM pembayaran
        ";

        return (float) $this->db
            ->query($sql)
            ->fetchColumn();
    }
}