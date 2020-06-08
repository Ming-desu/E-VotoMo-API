<?php

namespace EVotoMo\Controllers;

use EVotoMo\Helpers\Security;
use EVotoMo\Models\Account;
use EVotoMo\Models\Student;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Authentication {
    public function __construct() {
        @header("content-type: application/json");
    }

    public function login(Request $request, Response $response) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents('php://input', true));

        $username = $_POST['username'];
        $password = $_POST['password'];

        $account = Account::where('username', $username)->first();
        $student = Student::where('student_number', $username)
                          ->where('password', '!=', NULL)
                          ->first();

        if ($account === NULL && $student === NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Invalid username or password."
            ));

        if ($account !== NULL)
            if (!password_verify($password, $account->password))
                return \EVotoMo\Helpers\Response::Error($response, array(
                    "message" => "Invalid username or password."
                ));

        if ($student !== NULL)
            if (!password_verify($password, $student->password))
                return \EVotoMo\Helpers\Response::Error($response, array(
                    "message" => "Invalid username or password."
                ));

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => Security::generateJWT($account ?? $student),
            "type" => $account !== NULL ? 0 : 1,
            "message" => "OK"
        ));
    }

    public function verify(Request $request, Response $response) : Response {
        $authorizationHeader = $request->getHeader('Authorization')[0];
        $token = explode(' ', $authorizationHeader)[count(explode(' ', $authorizationHeader)) - 1];

        $decoded_token = Security::validateJWT($token);

        if (!isset($decoded_token['response']))
            return \EVotoMo\Helpers\Response::Error($response, array(
                "response" => $decoded_token['message']
            ));

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $decoded_token['response'],
            "type" => isset(json_decode($decoded_token['response'])->username) ? 0 : 1,
            "message" => "OK"
        ));
    }
}