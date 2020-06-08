<?php

namespace EVotoMo\Controllers;

use EVotoMo\Models\Student;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Students {
    public function __construct() {
        @header("content-type: application/json");
    }

    public function index(Request $request, Response $response, $args) : Response {
        if (isset($args['student_number']) && !empty($args['student_number'])) {
            $student = Student::where('student_number', $args['student_number'])->first();
            return $student !== NULL ?
                \EVotoMo\Helpers\Response::Error($response, array(
                    "message" => "Student does not exists."
                )) : \EVotoMo\Helpers\Response::Success($response, array(
                    "response" => $student,
                    "message" => "OK"
                ));
        }

        $students = Student::all();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $students,
            "message" => "OK"
        ));
    }

    public function store(Request $request, Response $response) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents("php://input", true));

        $student_number = $_POST['student_number'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $birthdate = $_POST['birthdate'];
        $sex = $_POST['sex'];

        // TODO :: In android app, convert the image into base64
        $image = $_POST['image'];

        $studentExists = Student::where('student_number', $student_number)->first();
        if ($studentExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Student number already exists."
            ));

        $student = Student::create([
            "student_number" => $student_number,
            "firstname" => $firstname,
            "middlename" => $middlename,
            "lastname" => $lastname,
            "birthdate" => date('Y-m-d', strtotime($birthdate)),
            "sex" => $sex,
            "image" => $image
        ]);

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $student,
            "message" => "Student successfully created."
        ));
    }

    public function update(Request $request, Response $response) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents("php://input", true));

        $id = $_POST['id'];
        $student_number = $_POST['student_number'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $birthdate = $_POST['birthdate'];
        $sex = $_POST['sex'];

        // TODO :: In android app, convert the image into base64
        $image = $_POST['image'];

        $studentExists = Student::where('student_number', $student_number)
                                ->where('id', '!=', $id)
                                ->first();

        if ($studentExists !== NULL)
            return \EVotoMo\Helpers\Response::Error($response, array(
                "message" => "Student number already exists."
            ));

        $student = Student::find($id);
        $student->student_number = $student_number;
        $student->firstname = $firstname;
        $student->middlename = $middlename;
        $student->lastname = $lastname;
        $student->birthdate = $birthdate;
        $student->sex = $sex;

        // TODO :: Delete the current image saved if it is not null
        // before updating it into a new one
        $student->image = $image;
        $student->save();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "response" => $student,
            "message" => "Student successfully updated."
        ));
    }

    public function delete(Request $request, Response $response) : Response {
        if (count($_POST) == 0)
            $_POST = (array)json_decode(file_get_contents("php://input", true));

        $id = $_POST['id'];

        $student = Student::find($id);
        $student->delete();

        return \EVotoMo\Helpers\Response::Success($response, array(
            "message" => "Student successfully deleted."
        ));
    }
}