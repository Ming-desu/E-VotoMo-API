<?php

namespace EVotoMo\Controllers;

use EVotoMo\Models\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Sessions {
    public function __construct() {
        @header("content-type: application/json");
    }

    public function index(Request $request, Response $response, $args) : Response {
        $sessions = Session::with('user', 'code')->get();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $sessions,
            "message" => "OK"
        ));
    }

    public function store(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $name = $_POST['name'];
        $vote_start = $_POST['vote_start'];
        $vote_end = $_POST['vote_end'];
        $added_by = $_POST['added_by'];

        $sessionExists = Session::where('name', $name)->first();
        if ($sessionExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Session name already exists."
            ));

        $session = Session::create([
            "name" => $name,
            "vote_start" => date('Y-m-d h:i a', strtotime($vote_start)),
            "vote_end" => date('Y-m-d h:i a', strtotime($vote_end)),
            "added_by" => $added_by
        ]);

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $session,
            "message" => "Session successfully created."
        ));
    }

    public function update(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $id = $_POST['id'];
        $name = $_POST['name'];
        $vote_start = $_POST['vote_start'];
        $vote_end = $_POST['vote_end'];

        $sessionExists = Session::where('name', $name)
                                ->where('id', '!=', $id)
                                ->first();

        if ($sessionExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Session name already exists."
            ));

        $session = Session::find($id);
        $session->name = $name;
        $session->vote_start = date('Y-m-d h:i a', strtotime($vote_start));
        $session->vote_end = date('Y-m-d h:i a', strtotime($vote_end));
        $session->save();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $session,
            "message" => "Session successfully updated."
        ));
    }

    public function delete(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $id = $_POST['id'];

        $session = Session::find($id);
        $session->delete();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "message" => "Session successfully deleted."
        ));
    }
}