<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../src/bootstrap.php';

// Create the slim app object
$app = AppFactory::create();

// Set the basepath of the project
$app->setBasePath("/e-votomo-api/public");

// Add the routing middleware
$app->addRoutingMiddleware();

// Display the errors with style using the Slim debugger
$app->addErrorMiddleware(true, true, true);

$app->group('', function (RouteCollectorProxy $group) {
    $group->get('/auth/verify', \EVotoMo\Controllers\Authentication::class . ":verify");

    $group->get("/accounts[/{id:[0-9]+}]", \EVotoMo\Controllers\Accounts::class . ":index");
    $group->post("/accounts", \EVotoMo\Controllers\Accounts::class . ":store");

    $group->get('/sessions[/{id:[0-9]+}]', \EVotoMo\Controllers\Sessions::class. ":index");
    $group->post('/sessions', \EVotoMo\Controllers\Sessions::class . ":store");
    $group->patch('/sessions[/{id:[0-9]+}]', \EVotoMo\Controllers\Sessions::class . ":update");
    $group->delete('/sessions[/{id:[0-9]+}]', \EVotoMo\Controllers\Sessions::class . ":delete");

    $group->get('/sessions/{id:[0-9]+}/voters', \EVotoMo\Controllers\SessionVoters::class. ":index");
    $group->post('/sessions/{id:[0-9]+}/voters', \EVotoMo\Controllers\SessionVoters::class . ":store");
    $group->patch('/sessions/{id:[0-9]+}/voters', \EVotoMo\Controllers\SessionVoters::class . ":update");

    $group->post('/sessions/{id:[0-9]+}/vote', \EVotoMo\Controllers\Votes::class . ":store");

    $group->post('/codes', \EVotoMo\Controllers\Codes::class . ":generate");

    $group->get('/students[/{student_number}]', \EVotoMo\Controllers\Students::class. ":index");
    $group->post('/students', \EVotoMo\Controllers\Students::class . ":store");
    $group->patch('/students[/{id:[0-9]+}]', \EVotoMo\Controllers\Students::class . ":update");
    $group->delete('/students[/{id:[0-9]+}]', \EVotoMo\Controllers\Students::class . ":delete");

    $group->get('/positions', \EVotoMo\Controllers\Positions::class. ":index");
    $group->post('/positions', \EVotoMo\Controllers\Positions::class . ":store");
    $group->patch('/positions[/{id:[0-9]+}]', \EVotoMo\Controllers\Positions::class . ":update");
    $group->delete('/positions[/{id:[0-9]+}]', \EVotoMo\Controllers\Positions::class . ":delete");

    $group->get('/candidates', \EVotoMo\Controllers\Candidates::class. ":index");
    $group->post('/candidates', \EVotoMo\Controllers\Candidates::class . ":store");
    $group->patch('/candidates[/{id:[0-9]+}]', \EVotoMo\Controllers\Candidates::class . ":update");
    $group->delete('/candidates[/{id:[0-9]+}]', \EVotoMo\Controllers\Candidates::class . ":delete");
})->addMiddleware(new \EVotoMo\Middlewares\AuthMiddleware());

$app->post('/login', \EVotoMo\Controllers\Authentication::class . ":login");

$app->get('/test/check', function(Request $request, Response $response) {
    $count = \EVotoMo\Models\Account::where('id', '>', 0)->count();

    if ($count > 0)
        return \EVotoMo\Helpers\Response::Success($response, array(
            "has_account" => true
        ));
    return \EVotoMo\Helpers\Response::Success($response, array(
        "has_account" => false
    ));
});

$app->post('/test/check/account', function(Request $request, Response $response) {
    if (count($_POST) == 0)
        $_POST = (array)json_decode(file_get_contents("php://input", true));

    $count = \EVotoMo\Models\Account::where('id', '>', 0)->count();

    if ($count > 0)
        return $response;

    if (isset($_GET['create']))
        \EVotoMo\Models\Account::create([
            "firstname" => $_POST['firstname'],
            "lastname" => $_POST['lastname'],
            "username" => $_POST['username'],
            "password" => password_hash($_POST['password'], PASSWORD_BCRYPT)
        ]);

    return \EVotoMo\Helpers\Response::Success($response, array(
        "response" => null,
        "message" => "OK"
    ));
});

$app->run();