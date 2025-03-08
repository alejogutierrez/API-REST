<?php

namespace App;

use Exception;
use Throwable;

class ClientException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function printFormattedErrors($name)
    {
        header("Content-Type: application/json; charset=utf-8");
        http_response_code($this->code);
        echo json_encode(
            (object) [
                "errors" => [
                    "name" => $name,
                    "description" => $this->__toString(),
                ],
            ]
        );
    }
}
