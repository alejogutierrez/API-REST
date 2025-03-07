<?php

namespace App;

use App\Client;
use App\DbConnInterface;
use PDO;

class ClientRepository
{
    private $db;

    public function __construct(DbConnInterface $db)
    {
        $this->db = $db::getInstance();
    }

    public function getAll(): array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM listado_clientes");
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        }
    }

    public function create(Client $client): int
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('INSERT INTO listado_clientes(id, email, name, city, telephone)
                        VALUES(NULL,:email, :name, :city, :telephone)');
        $stmt->bindParam(":email", $client->email);
        $stmt->bindParam(":name", $client->name);
        $stmt->bindParam(":city", $client->city);
        $stmt->bindParam(":telephone", $client->telephone);
        if ($stmt->execute()) {
            return $this->db->getInsertId($conn);
        }
    }

    public function findById(int $id): object|bool
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM listado_clientes WHERE id = :id");
        $stmt->execute(["id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(): void {}

    public function deleteById(int $id): void
    {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("DELETE FROM listado_clientes WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
}
