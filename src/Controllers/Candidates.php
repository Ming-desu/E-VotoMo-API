<?php

namespace EVotoMo\Controllers;

use EVotoMo\Models\Candidate;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Candidates {
    public function __construct() {
        @header("content-type: application/json");
    }

    public function index(Request $request, Response $response) : Response {
        $candidates = Candidate::with('student', 'position')->get();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $candidates,
            "message" => "OK"
        ));
    }

    public function store(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $session_id = $_POST['session_id'];
        $student_id = $_POST['student_id'];
        $position_id = $_POST['position_id'];

        $candidateExists = Candidate::where('session_id', $session_id)
                                    ->where('student_id', $student_id)
                                    ->first();

        if ($candidateExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Candidate already exists."
            ));

        $candidate = Candidate::create([
            "session_id" => $session_id,
            "student_id" => $student_id,
            "position_id" => $position_id
        ]);

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $candidate,
            "message" => "Candidate successfully created."
        ));
    }

    public function update(Request $request, Response $response) : Response {
        return $response;
    }

    public function delete(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $id = $_POST['id'];

        $candidate = Candidate::find($id);
        $candidate->delete();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "message" => "Candidate successfully deleted."
        ));
    }
}