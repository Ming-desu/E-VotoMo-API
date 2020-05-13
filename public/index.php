<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../src/bootstrap.php';

// Create the slim app object
$app = AppFactory::create();

// Set the basepath of the project
$app->setBasePath("/evotomo/public");

// Add the routing middleware
$app->addRoutingMiddleware();

// Display the errors with style using the Slim debugger
$app->addErrorMiddleware(true, true, true);

$app->get("/", function (Request $request, Response $response, $args) {
    @header("content-type: application/json");
    return \EVotoMo\Helpers\Response::Success($response, array("error" => false, "response" => "Success"));
});

$app->get("/accounts[/{id:[0-9]+}]", \EVotoMo\Controllers\Accounts::class . ":index");
$app->post("/accounts", \EVotoMo\Controllers\Accounts::class . ":store");

$app->get('/sessions[/{id:[0-9]+}]', \EVotoMo\Controllers\Sessions::class. ":index");
$app->post('/sessions', \EVotoMo\Controllers\Sessions::class . ":store");
$app->patch('/sessions[/{id:[0-9]+}]', \EVotoMo\Controllers\Sessions::class . ":update");
$app->delete('/sessions[/{id:[0-9]+}]', \EVotoMo\Controllers\Sessions::class . ":delete");

$app->get('/sessions/{id:[0-9]+}/voters', \EVotoMo\Controllers\SessionVoters::class. ":index");
$app->post('/sessions/{id:[0-9]+}/voters', \EVotoMo\Controllers\SessionVoters::class . ":store");
$app->patch('/sessions/{id:[0-9]+}/voters', \EVotoMo\Controllers\SessionVoters::class . ":update");

$app->post('/sessions/{id:[0-9]+}/vote', \EVotoMo\Controllers\Votes::class . ":store");

$app->get('/codes', \EVotoMo\Controllers\Codes::class . ":index");

$app->get('/students[/{student_number}]', \EVotoMo\Controllers\Students::class. ":index");
$app->post('/students', \EVotoMo\Controllers\Students::class . ":store");
$app->patch('/students[/{id:[0-9]+}]', \EVotoMo\Controllers\Students::class . ":update");
$app->delete('/students[/{id:[0-9]+}]', \EVotoMo\Controllers\Students::class . ":delete");

$app->get('/positions', \EVotoMo\Controllers\Positions::class. ":index");
$app->post('/positions', \EVotoMo\Controllers\Positions::class . ":store");
$app->patch('/positions[/{id:[0-9]+}]', \EVotoMo\Controllers\Positions::class . ":update");
$app->delete('/positions[/{id:[0-9]+}]', \EVotoMo\Controllers\Positions::class . ":delete");

$app->get('/candidates', \EVotoMo\Controllers\Candidates::class. ":index");
$app->post('/candidates', \EVotoMo\Controllers\Candidates::class . ":store");
$app->patch('/candidates[/{id:[0-9]+}]', \EVotoMo\Controllers\Candidates::class . ":update");
$app->delete('/candidates[/{id:[0-9]+}]', \EVotoMo\Controllers\Candidates::class . ":delete");

$app->run();