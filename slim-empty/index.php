<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/vendor/autoload.php';

cors();
// $cors = new \Cors;

$app = new \Slim\App;

$app->get('/',function(Request $request, Response $response){
	return 'index';
});
$app->group('/v1', function() use($app){
    $app->group('/bloodbank', function() use($app){
        $app->post('/insert', function (Request $request, Response $response, $args) {
            $p = json_decode($request->getBody(), true);
            $p['method']='bloodbank_insert';
            $p['controller_name'] = 'bloodbank';
            $func = new Middleware(['controller'=>$p['controller_name']]);
            $func->response = $response;
            $func->preCall($p);
            $rs = $func->callMethod($p);
            return $rs;
        });
        $app->get('/select/{id}', function (Request $request, Response $response, $args) {
            $p = $args;
            $p['method'] ='bloodbank_select';
            $p['controller_name'] = 'bloodbank';

            $func = new Middleware(['controller'=>$p['controller_name']]);
            $func->response = $response;
            $func->preCall($p);
            $rs = $func->callMethod($p);
            return $rs;
        });
    });
});

$app->get('/getAllReactionCategory', function (Request $request, Response $response, $args) {
    $p = $args;
    $p['method'] ='ReactionCategory';
    $p['controller_name'] = 'bloodbank';

    $func = new Middleware(['controller'=>$p['controller_name']]);
    $func->response = $response;
    $func->preCall($p);
    $rs = $func->callMethod($p);
    return $rs;
});
$app->get('/getAllSignsAndSymptoms', function (Request $request, Response $response, $args) {
    $p = $args;
    $p['method'] ='SignsAndSymptomsName';
    $p['controller_name'] = 'bloodbank';

    $func = new Middleware(['controller'=>$p['controller_name']]);
    $func->response = $response;
    $func->preCall($p);
    $rs = $func->callMethod($p);
    return $rs;
});


$app->run();

function cors() {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if (isset($_SERVER['REQUEST_METHOD'])) {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");         
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit;
        }
    }

}