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
        $sql = "SELECT * FROM pegawai WHERE uname = :username LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        $pegawai = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pegawai ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM pegawai WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $pegawai = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pegawai ?: null;
    }

    public function createPasswordReset(int $idPegawai, string $tokenHash): void
    {
        $this->db->beginTransaction();

        try {
            $delete = $this->db->prepare(
                "DELETE FROM password_resets WHERE id_pegawai = :id_pegawai"
            );
            $delete->execute(['id_pegawai' => $idPegawai]);

            $sql = "INSERT INTO password_resets (id_pegawai, token, expired_at)
                    VALUES (:id_pegawai, :token, CURRENT_TIMESTAMP + INTERVAL '30 minutes')";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_pegawai' => $idPegawai,
                'token' => $tokenHash
            ]);

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function findValidPasswordReset(string $tokenHash): ?array
    {
        $sql = "SELECT id_reset, id_pegawai
                FROM password_resets
                WHERE token = :token
                AND expired_at > CURRENT_TIMESTAMP
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $tokenHash]);
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);

        return $reset ?: null;
    }

    public function updatePasswordAndDeleteReset(
        int $idReset,
        int $idPegawai,
        string $passwordHash
    ): void {
        $this->db->beginTransaction();

        try {
            $sql = "UPDATE pegawai
                    SET password = :password
                    WHERE id_pegawai = :id_pegawai";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'password' => $passwordHash,
                'id_pegawai' => $idPegawai
            ]);

            $sql = "DELETE FROM password_resets
                    WHERE id_reset = :id_reset
                    AND id_pegawai = :id_pegawai";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_reset' => $idReset,
                'id_pegawai' => $idPegawai
            ]);

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
