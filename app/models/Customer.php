<?php

class Customer
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllCustomer(): array
    {
        $sql = " SELECT nama, no_hp FROM customer ORDER BY nama ASC ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
