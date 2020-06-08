<?php

namespace EVotoMo\Controllers;

use EVotoMo\Helpers\Random;
use EVotoMo\Models\Code;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Codes {
    public function __construct() {
        @header("content-type: application/json");
    }

    public function index(Request $request, Response $response, $args) : Response {
        $isExisting = false;

        if (isset($args['code']) && !empty($args['code'])) {
            $codeString = $_POST['code'];

            $codeExists = Code::where('code', $codeString)->first();
            if ($codeExists !== NULL)
                $isExisting = true;
        }

        return \EVotoMo\Helpers\Response::Success($response, array(
            "isExisting" => $isExisting
        ));
    }

    public function generate(Request $request, Response $response) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents("php://input", true));

        $session_id = $_POST['session_id'];

        do {
            $randomString = Random::Generate();
            $code = Code::where('code', $randomString)->first();
        }
        while($code !== NULL);

        $code = Code::create([
            "session_id" => $session_id,
            "code" => $randomString
        ]);

        return \EVotoMo\Helpers\Response::Success($response, $code);
    }
}