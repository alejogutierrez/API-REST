<?php

namespace App;

use App\Client;
use App\ClientException;
use App\ClientServiceInterface;
use OpenApi\Attributes as OA;

#[OA\Info(title: "API Listado de clientes", version: "0.1")]
class ClientController
{
    public function __construct(
        protected readonly ClientServiceInterface $service
    ) {}

    #[OA\Get(path: "/clients", operationId: "get clients list")]
    #[OA\Response(response: "200", description: "List clients data")]
    public function getClients(): void
    {
        try {
            $clients = $this->service->all();
            http_response_code(200);
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($clients);
        } catch (ClientException $err) {
            $err->printFormattedErrors(
                "Ha ocurrido un error al consultar los datos de clientes."
            );
        }
    }

    #[OA\Get(path: "/clients/:id", operationId: "get specific client")]
    #[OA\Response(response: "200", description: "Get client details")]
    #[OA\Response(response: "400", description: "Client information error")]
    public function findClient(int $id): void
    {
        try {
            $client = $this->service->find($id);
            if (is_null($client)) {
                throw new ClientException(
                    "No se encontró el cliente solicitado.",
                    400,
                    null
                );
            }

            http_response_code(200);
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($client);
        } catch (ClientException $err) {
            $err->printFormattedErrors(
                "Ha ocurrido un error al consultar los datos de clientes."
            );
        }
    }

    #[OA\Post(path: "/clients", operationId: "create client")]
    #[OA\Response(response: "201", description: "Client created")]
    #[OA\Response(response: "400", description: "Client information error")]
    public function createClient(): void
    {
        try {
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);
            $expected = ["email", "name", "city", "telephone"];
            $missingParams = $this->checkRequired($expected, $data);

            if (count($missingParams)) {
                throw new ClientException(
                    "No se puede crear el cliente. Falta información: " .
                        implode(",", $missingParams),
                    400,
                    null
                );
            }

            $this->validateCommonParams($data);

            $client = new Client(
                $data["email"],
                $data["name"],
                $data["city"],
                $data["telephone"]
            );

            http_response_code(201);
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($this->service->create($client));
        } catch (ClientException $err) {
            $err->printFormattedErrors(
                "Ha ocurrido un error al crear el cliente."
            );
        }
    }

    #[OA\Put(path: "/clients", operationId: "update client")]
    #[OA\Response(response: "200", description: "Client updated")]
    #[OA\Response(response: "400", description: "Client information error")]
    public function updateClient(): void
    {
        try {
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);
            $expected = ["id", "email", "name", "city", "telephone"];
            $missingParams = $this->checkRequired($expected, $data);

            if (count($missingParams)) {
                throw new ClientException(
                    "No se puede actualizar el cliente. Falta información: " .
                        implode(",", $missingParams),
                    400,
                    null
                );
            }

            $this->validateCommonParams($data);
            $client = $this->service->find(intval($data["id"]));
            if (is_null($client)) {
                throw new ClientException(
                    "No se encontró el recurso a editar",
                    400,
                    null
                );
            }

            $client->setEmail($data["email"]);
            $client->setName($data["name"]);
            $client->setCity($data["city"]);
            $client->setTelephone($data["telephone"]);
            $this->service->update($client);
            http_response_code(200);
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($client);
        } catch (ClientException $err) {
            $err->printFormattedErrors(
                "Ha ocurrido un error al actualizar el cliente."
            );
        }
    }

    #[OA\Patch(path: "/clients/:id", operationId: "patch client")]
    #[OA\Response(response: "200", description: "Client patch done")]
    #[OA\Response(response: "400", description: "Client information error")]
    public function patchClient(int $id): void
    {
        try {
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);

            if (empty($data)) {
                throw new ClientException(
                    "No se encontraron datos a actualizar para el cliente.",
                    400,
                    null
                );
            }

            if (!is_int($id) || $id === 0) {
                throw new ClientException(
                    "No se puede actualizar el cliente solicitado.",
                    400,
                    null
                );
            }

            $client = $this->service->find($id);
            if (is_null($client)) {
                throw new ClientException(
                    "No se encontró el recurso a editar",
                    400,
                    null
                );
            }

            if (array_key_exists("email", $data)) {
                if (
                    !$this->validString($data["email"]) ||
                    !$this->validEmail($data["email"])
                ) {
                    throw new ClientException(
                        "El correo recibido no es un correo válido.",
                        400,
                        null
                    );
                }
                $client->setEmail($data["email"]);
            }

            if (array_key_exists("name", $data)) {
                if (!$this->validString($data["name"])) {
                    throw new ClientException(
                        "Alguno de los parámetros supera el límite de caracteres permitido.",
                        400,
                        null
                    );
                }
                $client->setName($data["name"]);
            }

            if (array_key_exists("city", $data)) {
                if (!$this->validString($data["city"])) {
                    throw new ClientException(
                        "Alguno de los parámetros supera el límite de caracteres permitido.",
                        400,
                        null
                    );
                }
                $client->setCity($data["city"]);
            }

            if (array_key_exists("telephone", $data)) {
                if (!$this->validString($data["telephone"])) {
                    throw new ClientException(
                        "Alguno de los parámetros supera el límite de caracteres permitido.",
                        400,
                        null
                    );
                }
                $client->setTelephone($data["telephone"]);
            }
            $this->service->update($client);
            http_response_code(200);
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($client);
        } catch (ClientException $err) {
            $err->printFormattedErrors(
                "Ha ocurrido un error al actualizar parcialmente el cliente."
            );
        }
    }

    #[OA\Delete(path: "/clients/:id", operationId: "delete client")]
    #[OA\Response(response: "204", description: "Client deleted")]
    #[OA\Response(response: "400", description: "Client information error")]
    public function deleteClient(int $id): void
    {
        try {
            $client = $this->service->find($id);
            if (is_null($client)) {
                throw new ClientException(
                    "No se encontró el recurso a eliminar",
                    400,
                    null
                );
            }

            $this->service->delete($client->id);
            http_response_code(204);
        } catch (ClientException $err) {
            $err->printFormattedErrors(
                "Ha ocurrido un error al eliminar el cliente."
            );
        }
    }

    private function validateCommonParams(array $data): void
    {
        if (
            !$this->validString($data["email"]) ||
            !$this->validString($data["name"]) ||
            !$this->validString($data["city"]) ||
            !$this->validString($data["telephone"])
        ) {
            throw new ClientException(
                "Alguno de los parámetros supera el límite de caracteres permitido.",
                400,
                null
            );
        }

        if (!$this->validEmail($data["email"])) {
            throw new ClientException(
                "El correo recibido no es un correo válido.",
                400,
                null
            );
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
}
