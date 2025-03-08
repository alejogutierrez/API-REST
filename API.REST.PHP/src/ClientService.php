<?php

namespace App;

use App\Client;

class ClientService implements ClientServiceInterface
{
    public function __construct(
        private readonly ClientRepository $repository
    ) {}

    public function all(): array
    {
        return $this->repository->getAll();
    }
    public function create(Client $client): Client
    {
        $clientId = $this->repository->create($client);
        $client->setId($clientId);
        return $client;
    }

    public function find(int $id): Client|null
    {
        $data = $this->repository->findById($id);
        if (!$data) {
            return null;
        }
        $client = new Client(
            $data["email"],
            $data["name"],
            $data["city"],
            $data["telephone"]
        );
        $client->setId($data["id"]);
        return $client;
    }

    public function update(Client $client): void
    {
        $this->repository->update($client);
    }

    public function delete(int $id): void
    {
        $this->repository->deleteById($id);
    }
}
