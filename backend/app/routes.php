<?php

declare(strict_types=1);
require_once __DIR__ . '/PDOConnect.php';

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Psr7\Stream;
require_once __DIR__  .'/../controller/Databasehandler.php'; // Assuming the DatabaseHandler file is included
require_once __DIR__ . '/../controller/SignsAndSymptomsName.Controller.php';
require_once __DIR__ . '/../controller/ReactionCategory.Controller.php';

return function (App $app) {


    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/getAllSignsAndSymptoms', function ($request, $response) {
        $dataController = new SignsAndSymptomsNameController('dbname');
        return $dataController->getData($response);
    });
    $app->get('/getAllReactionCategory', function ($request, $response) {
        $dataController = new ReactionCategoryController('dbname');
        return $dataController->getData($response);
    });


    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
