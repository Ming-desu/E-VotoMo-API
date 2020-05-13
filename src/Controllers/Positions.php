<?php

namespace EVotoMo\Controllers;

use EVotoMo\Models\Position;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Positions {
    public function __construct() {
        @header("content-type: application/json");
    }

    public function index(Request $request, Response $response) : Response {
        $positions = Position::all();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $positions,
            "message" => "OK"
        ));
    }

    public function store(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $description = $_POST['description'];
        $max_vote = $_POST['max_vote'];

        $positionExists = Position::where('description', $description)->first();
        if ($positionExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Position description already exists."
            ));

        $position = Position::create([
            "description" => $description,
            "max_vote" => $max_vote
        ]);

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $position,
            "message" => "Position successfully created."
        ));
    }

    public function update(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $id = $_POST['id'];
        $description = $_POST['description'];
        $max_vote = $_POST['max_vote'];

        $positionExists = Position::where('description', $description)
                                  ->where('id', '!=', $id)
                                  ->first();

        if ($positionExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Position description already exists."
            ));

        $position = Position::find($id);
        $position->description = $description;
        $position->max_vote = $max_vote;
        $position->save();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $position,
            "message" => "Position successfully updated."
        ));
    }

    public function delete(Request $request, Response $response) : Response {
        $_POST = (array)json_decode(file_get_contents("php://input", true));

        $id = $_POST['id'];

        $position = Position::find($id);
        $position->delete();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "message" => "Position successfully deleted."
        ));
    }
}