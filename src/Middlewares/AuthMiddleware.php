<?php

namespace EVotoMo\Middlewares;

use EVotoMo\Helpers\Security;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware implements MiddlewareInterface {
    public function process(Request $request, RequestHandler $handler) : Response {
        $response = $handler->handle($request);

        // Check if the request has the authorization key
        if (!isset($request->getHeader('Authorization')[0])) {
            @header('HTTP/1.0 400 Bad Request');
            @header('content-type: application/json');
            echo json_encode(array(
                "response" => "Access token is empty."
            ), JSON_PRETTY_PRINT);
            exit;
        }

        $authorizationHeader = $request->getHeader('Authorization')[0];
        $token = explode(' ', $authorizationHeader)[count(explode(' ', $authorizationHeader)) - 1];

        $decoded_token = Security::validateJWT($token);

        // Check if the authorization key is valid
        if (!isset($decoded_token['response'])) {
            @header('HTTP/1.0 401 Unauthorized');
            @header('content-type: application/json');
            echo json_encode(array(
                "response" => $decoded_token['message']
            ), JSON_PRETTY_PRINT);
            exit;
        }

        return $response;
    }
}