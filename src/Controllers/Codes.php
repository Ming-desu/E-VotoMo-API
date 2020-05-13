<?php

namespace EVotoMo\Controllers;

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
}