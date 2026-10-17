<?php

class DropPoint
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getDropPoint(): array
    {
        $sql = "SELECT * FROM drop_point ORDER BY id_drop_point ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}