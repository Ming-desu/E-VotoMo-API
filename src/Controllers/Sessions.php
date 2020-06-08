<?php

namespace EVotoMo\Controllers;

use EVotoMo\Models\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Sessions {
    public function __construct() {
        @header("content-type: application/json");
        date_default_timezone_set("Asia/Manila");
    }

    public function index(Request $request, Response $response, $args) : Response {
        if (!isset($_GET['query']))
            $sessions = Session::with('user', 'code')->orderBy('id', 'desc')->get();
        else
            $sessions = Session::with('user', 'code')->where('name', 'LIKE', $_GET['query'] . '%')->orderBy('name')->orderBy('vote_start')->get();

        return \EVotoMo\Helpers\Response::Success($response, $sessions);
    }

    public function store(Request $request, Response $response) : Response {
        if (count($_POST) == 0)
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

        $current_date_time = date('Y-m-d');
        $date_vote_start = date('Y-m-d', strtotime($vote_start));
        $date_vote_end = date('Y-m-d', strtotime($vote_end));

        if ($date_vote_start < $current_date_time)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Vote start date had already passed, pick a recent date."
            ));

        if ($date_vote_end < $current_date_time)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Vote end date had already passed, pick a recent date."
            ));

        if ($date_vote_end < $date_vote_start)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Vote end date cannot be before vote starts."
            ));

        $inserted_session = Session::create([
            "name" => $name,
            "vote_start" => date('Y-m-d h:iA', strtotime($vote_start)),
            "vote_end" => date('Y-m-d h:iA', strtotime($vote_end)),
            "added_by" => $added_by
        ]);

        $session = Session::with('user', 'code')->where('id', $inserted_session->id)->first();

        return \EVotoMo\Helpers\Response::Success($response, $session);
    }

    public function update(Request $request, Response $response) : Response {
        if (count($_POST) == 0)
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
        $session->vote_start = date('Y-m-d h:iA', strtotime($vote_start));
        $session->vote_end = date('Y-m-d h:iA', strtotime($vote_end));
        $session->save();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $session,
            "message" => "Session successfully updated."
        ));
    }

    public function delete(Request $request, Response $response) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents("php://input", true));

        $id = $_POST['id'];

        $session = Session::find($id);
        $session->delete();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "message" => "Session successfully deleted."
        ));
    }
}