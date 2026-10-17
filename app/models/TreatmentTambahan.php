<?php

class TreatmentTambahan
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getTreatmentTambahan(): array
    {
        $stmt = $this->db->prepare("SELECT id_treatment_tambahan, nama_treatment, harga_min, harga_max, estimasi_min_hari, estimasi_max_hari, keterangan FROM treatment_tambahan ORDER BY id_treatment_tambahan ASC");
        $stmt->execute();

        $treatmentAll = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $treatmentKecepatan = array_filter($treatmentAll, function ($item) {
            return in_array($item['nama_treatment'], ['Express', 'Prioritas'], true);
        });

        $treatmentTambahan = array_filter($treatmentAll, function ($item) {
            return !in_array($item['nama_treatment'], ['Express', 'Prioritas'], true);
        });

        return [
            'kecepatan' => array_values($treatmentKecepatan),
            'tambahan' => array_values($treatmentTambahan)
        ];
    }
}
