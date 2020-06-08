<?php

namespace EVotoMo\Controllers;

use EVotoMo\Models\SessionVoter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SessionVoters {
    public function __construct() {
        @header("content-type: application/json");
    }

    // TODO :: Implement checking of voter if they had already voted
    public function index(Request $request, Response $response, $args) : Response {
        return $response;
    }

    public function store(Request $request, Response $response, $args) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents("php://input", true));

        $session_id = $args['id'];
        $student_id = $_POST['student_id'];

        $sessionVoterExists = SessionVoter::where('session_id', $session_id)
                                          ->where('student_id', $student_id)
                                          ->first();

        if ($sessionVoterExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "You are already joined this voting session."
            ));

        $sessionVoter = SessionVoter::create([
            "session_id" => $session_id,
            "student_id" => $student_id,
            "voted" => 0
        ]);

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $sessionVoter,
            "message" => "You are now a voter of this voting session."
        ));
    }

    public function update(Request $request, Response $response, $args) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents("php://input", true));

        $session_id = $args['id'];
        $session_voter_id = $_POST['id'];

        $sessionVoter = SessionVoter::find($session_voter_id);
        $sessionVoter->voted = 1;
        $sessionVoter->save();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $sessionVoter,
            "message" => "You had successfully voted for this voting session."
        ));
    }

}