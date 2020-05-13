<?php

namespace EVotoMo\Helpers;

use Psr\Http\Message\ResponseInterface;

class Response {
    public static function Success(ResponseInterface $response, array $data = array()) : ResponseInterface {
        $newResponse = $response->withStatus(200);
        $newResponse->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $newResponse;
    }

    public static function Error(ResponseInterface $response, array $data = array(), int $errorCode = 400) : ResponseInterface {
        $newResponse = $response->withStatus($errorCode);
        $newResponse->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $newResponse;
    }
}