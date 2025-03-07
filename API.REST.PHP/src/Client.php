<?php

namespace App;

class Client
{
    public int $id;

    public function __construct(
        public string $email,
        public string $name,
        public string $city,
        public string $telephone
    ) {}

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
