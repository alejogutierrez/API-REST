<?php

namespace App;

use App\Client;
use App\ClientServiceInterface;

class ClientController
{
    public function __construct(
        protected readonly ClientServiceInterface $service
    ) {}

    public function getClients(): void
    {
        $clients = $this->service->all();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($clients);
    }

    public function createClient(): void
    {
        try {
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);
            $expected = ["email", "name", "city", "telephone"];
            $missingParams = $this->checkRequired($expected, $data);

            if (count($missingParams)) {
                $this->badRequest([
                    "No se puede crear el cliente. Falta información: " .
                    implode(",", $missingParams),
                ]);
            }

            if (
                !$this->validString($data["email"]) ||
                !$this->validString($data["name"]) ||
                !$this->validString($data["city"]) ||
                !$this->validString($data["telephone"])
            ) {
                $this->badRequest([
                    "Alguno de los parámetros supera el límite de caracteres permitido.",
                ]);
            }

            if (!$this->validEmail($data["email"])) {
                $this->badRequest([
                    "El correo recibido no es un correo válido.",
                ]);
            }

            $client = new Client(
                $data["email"],
                $data["name"],
                $data["city"],
                $data["telephone"]
            );

            http_response_code(201);
            header("Content-Type: application/json");
            echo json_encode($this->service->create($client));
        } catch (\Throwable $th) {
        }
    }

    public function updateClient(): void
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $missingParams = $this->checkRequired(["id"], $data);

        if (count($missingParams)) {
            $this->badRequest([
                "No se puede crear el cliente. Falta información: " .
                implode(",", $missingParams),
            ]);
        }

        if (!$this->validEmail($data["email"])) {
            $this->badRequest(["El correo recibido no es un correo válido."]);
        }
    }

    public function deleteClient(int $id): void
    {
        $client = $this->service->find($id);
        if (is_null($client)) {
            $this->badRequest(["No fue posible eliminar el recurso."]);
        }
    }

    private function checkRequired(array $required, array $data): array
    {
        $missing = [];
        foreach ($required as $param) {
            if (!array_key_exists($param, $data)) {
                array_push($missing, $param);
            }
        }
        return $missing;
    }

    private function validEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    private function validString(string $string): bool
    {
        if (strlen($string) > 250) {
            return false;
        }

        return true;
    }

    private function badRequest(array $errors): void
    {
        http_response_code(400);
        header("Content-Type: application/json");
        echo json_encode(
            (object) [
                "errors" => $errors,
            ]
        );
    }
}
