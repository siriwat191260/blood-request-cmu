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
$app->get('/getBloodTransfDetail', function (Request $request, Response $response, array $args) {
    try {
        // Fetch the JSON file content
        $jsonContent = file_get_contents("blood_allergy.postman_collection.json");
        $jsonData = json_decode($jsonContent, true);
        // Find blood_transf_detail item
        $bloodTransfDetail = null;
        foreach ($jsonData['item'] as $item) {
            if ($item['name'] === 'blood_transf_detail') {
                $bloodTransfDetail = $item;
                break;
            }
        }

        if (!$bloodTransfDetail) {
            throw new Exception("Blood transfusion detail item not found");
        }

        // Extract token and endpoint
        $token = $bloodTransfDetail['request']['auth']['bearer'][0]['value'];
        $endpoint = $bloodTransfDetail['request']['url']['raw'];
        $bodyBlood = json_decode($bloodTransfDetail['request']['body']['raw'], true);

        // Prepare cURL request
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bodyBlood));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: GET, POST, PUT, DELETE',
            'Access-Control-Allow-Headers: Content-Type',
            'Access-Control-Allow-Credentials: true',
        ));

        // Execute the request
        $apiResponse = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            throw new Exception("cURL request failed: " . curl_error($ch));
        }

        // Close cURL resource
        curl_close($ch);

        // Decode the response
        $result = json_decode($apiResponse, true);

        // Process $result as needed
        $resultV = $result['v'];
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($resultV));
        return $response;
    } catch (Exception $error) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(["error" => $error->getMessage()]));

        return $response->withStatus(500);
    }
});
$app->get('/getUserDoctor', function (Request $request, Response $response, array $args) {
    try {
        // Fetch the JSON file content
        $jsonContent = file_get_contents("blood_allergy.postman_collection.json");
        $jsonData = json_decode($jsonContent, true);
        // Find blood_transf_detail item
        $doctor = null;
        foreach ($jsonData['item'] as $item) {
            if ($item['name'] === 'list_user_doctor') {
                $doctor = $item;
                break;
            }
        }

        if (!$doctor) {
            throw new Exception("Blood transfusion detail item not found");
        }

        // Extract token and endpoint
        $token = $doctor['request']['auth']['bearer'][0]['value'];
        $endpoint = $doctor['request']['url']['raw'];

        // Prepare cURL request
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: GET, POST, PUT, DELETE',
            'Access-Control-Allow-Headers: Content-Type',
            'Access-Control-Allow-Credentials: true',
        ));

        // Execute the request
        $apiResponse = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            throw new Exception("cURL request failed: " . curl_error($ch));
        }

        // Close cURL resource
        curl_close($ch);

        // Decode the response
        $result = json_decode($apiResponse, true);

        // Process $result as needed
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($result));
        return $response;
    } catch (Exception $error) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(["error" => $error->getMessage()]));

        return $response->withStatus(500);
    }
});
$app->get('/getUserNurse', function (Request $request, Response $response, array $args) {
    try {
        // Fetch the JSON file content
        $jsonContent = file_get_contents("blood_allergy.postman_collection.json");
        $jsonData = json_decode($jsonContent, true);
        // Find blood_transf_detail item
        $nurses = null;
        foreach ($jsonData['item'] as $item) {
            if ($item['name'] === 'list_user_nurse') {
                $nurses = $item;
                break;
            }
        }

        if (!$nurses) {
            throw new Exception("Blood transfusion detail item not found");
        }

        // Extract token and endpoint
        $token = $nurses['request']['auth']['bearer'][0]['value'];
        $endpoint = $nurses['request']['url']['raw'];

        // Prepare cURL request
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: GET, POST, PUT, DELETE',
            'Access-Control-Allow-Headers: Content-Type',
            'Access-Control-Allow-Credentials: true',
        ));

        // Execute the request
        $apiResponse = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            throw new Exception("cURL request failed: " . curl_error($ch));
        }

        // Close cURL resource
        curl_close($ch);

        // Decode the response
        $result = json_decode($apiResponse, true);

        // Process $result as needed
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($result));
        return $response;
    } catch (Exception $error) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(["error" => $error->getMessage()]));

        return $response->withStatus(500);
    }
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