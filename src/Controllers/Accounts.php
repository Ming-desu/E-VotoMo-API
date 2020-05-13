<?php

namespace EVotoMo\Controllers;

use EVotoMo\Models\Account;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Accounts {
    public function __construct() {
        @header("content-type: application/json");
    }

    public function index(Request $request, Response $response, $args) : Response {
        if (isset($args['id']) && !empty($args['id'])) {
            $account = Account::find($args['id']);
            return $account === NULL ?
                \EVotoMo\Helpers\Response::Error($response, array(
                    "message" => "Account does not exists."
                )) : \EVotoMo\Helpers\Response::Success($response, array(
                    "response" => $account,
                    "message" => "OK"
                ));
        }

        $accountCount = Account::all()->count();
        $hasAccount = false;

        if ($accountCount > 0)
            $hasAccount = true;

        return \EVotoMo\Helpers\Response::Success($response, array(
            "hasAccount" => $hasAccount
        ));
    }

    public function store(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // INSERT VALIDATION HERE ...

        $accountExists = Account::where('username', $username)->first();
        if ($accountExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Username is already taken."
            ));

        $account = Account::create([
            "firstname" => $firstname,
            "lastname" => $lastname,
            "username" => $username,
            "password" => password_hash($password, PASSWORD_BCRYPT)
        ]);

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $account,
            "message" => "OK"
        ));
    }
}