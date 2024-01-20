<?php
/* use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->run(); */
use Slim\Routing\RouteCollectorProxy;

$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write("Hello Slim Framework!");
    return $response;
});

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/data', function ($request, $response, $args) {
        $data = ['message' => 'Hello from Slim API'];
        return $response->withJson($data);
    });
});

$app->run();