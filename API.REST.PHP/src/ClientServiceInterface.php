<?php

namespace App;

use App\Client;

interface ClientServiceInterface
{
    public function all(): array;
    public function create(Client $client): Client;
    public function find(int $id): Client|null;
    public function update(Client $client): void;
    public function delete(int $id): void;
}
