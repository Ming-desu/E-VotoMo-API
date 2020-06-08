<?php

namespace EVotoMo\Controllers;

use EVotoMo\Models\Vote;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Votes {
    public function __construct() {
        @header("content-type: application/json");
    }

    // TODO :: Implement checking if the voter is currently a member of this session
    // Also do check if the voter has already voted
    public function store(Request $request, Response $response, $args) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents("php://input", true));

        $session_id = $args['id'];
        $student_id = $_POST['student_id'];
        $votes = $_POST['votes'];

        foreach ($votes as $vote) {
            $vote = Vote::create([
                "session_id" => $session_id,
                "student_id" => $student_id,
                "session_candidate_id" => $vote
            ]);
        }

        return \EVotoMo\Helpers\Response::Success($response, array(
            "message" => "You had successfully voted for this voting session."
        ));
    }
}