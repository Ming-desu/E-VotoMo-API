<?php

namespace EVotoMo\Helpers;

use Exception;
use Firebase\JWT\JWT;

class Security {
    public static function generateJWT($model) {
        $payload = array(
            'iss' => 'http://localhost/e-votomo-api',
            'iat' => time(),
            'exp' => time() + 60 * 60 * 3, // Valid for 3 hours
            'user' => $model
        );

        return JWT::encode($payload, getenv('SECRET'));
    }

    public static function validateJWT(String $token) {
        try {
            $decoded = JWT::decode($token, getenv('SECRET'), array('HS256'));
            return array("response" => json_encode($decoded->user), "message" => "OK");
        }
        catch (Exception $e) {
            return array("message" => "Access token is either invalid, tampered, or expired.");
        }
    }
}