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

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }
}
