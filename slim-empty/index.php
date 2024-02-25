<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/vendor/autoload.php';

cors();
// $cors = new \Cors;

$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response) {
    return 'index';
});
$app->group('/v1', function () use ($app) {
    $app->group('/bloodbank', function () use ($app) {
        $app->post('/insert', function (Request $request, Response $response, $args) {
            $p = json_decode($request->getBody(), true);
            $p['method'] = 'bloodbank_insert';
            $p['controller_name'] = 'bloodbank';
            $func = new Middleware(['controller' => $p['controller_name']]);
            $func->response = $response;
            $func->preCall($p);
            $rs = $func->callMethod($p);
            return $rs;
        });
        $app->get('/select/{id}', function (Request $request, Response $response, $args) {
            $p = $args;
            $p['method'] = 'bloodbank_select';
            $p['controller_name'] = 'bloodbank';

            $func = new Middleware(['controller' => $p['controller_name']]);
            $func->response = $response;
            $func->preCall($p);
            $rs = $func->callMethod($p);
            return $rs;
        });
    });
});

$app->group('/trasfusion-form', function () use ($app) {
    $app->get('/getAllReactionCategory', function (Request $request, Response $response, $args) {
        $p = $args;
        $p['method'] = 'ReactionCategory';
        $p['controller_name'] = 'bloodbank';

        $func = new Middleware(['controller' => $p['controller_name']]);
        $func->response = $response;
        $func->preCall($p);
        $rs = $func->callMethod($p);
        return $rs;
    });
    $app->get('/getAllSignsAndSymptoms', function (Request $request, Response $response, $args) {
        $p = $args;
        $p['method'] = 'SignsAndSymptomsName';
        $p['controller_name'] = 'bloodbank';

        $func = new Middleware(['controller' => $p['controller_name']]);
        $func->response = $response;
        $func->preCall($p);
        $rs = $func->callMethod($p);
        return $rs;
    });
    $app->post('/getBloodTransfDetail', function (Request $request, Response $response, array $args) {
        try {
            $getTokenEndpoint = "http://iservice.med.cmu.ac.th/gateway/bb/get_token.php";

            // Prepare cURL request to get token
            $ch1 = curl_init($getTokenEndpoint);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            $apiResponse = curl_exec($ch1);

            // Check for errors in obtaining token
            if (curl_errno($ch1)) {
                throw new Exception("cURL request failed: " . curl_error($ch1));
            }
            curl_close($ch1);

            // Decode the response to get token
            $result = json_decode($apiResponse, true);
            $token = $result['v']['token'];
            $requestData = $request->getParsedBody();
            $bloodTransfId = $requestData['blood_transf_id'];
            $requestData = [
                "blood_transf_id" => $bloodTransfId
            ];

            // Extract token and endpoint
            $endpoint = "http://iservice.med.cmu.ac.th/gateway/bb/blood_transf_detail.php";

            // Prepare cURL request
            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
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
            $jsonContent = file_get_contents("C:\Users\Acer\bloodbank_js\blood_request\slim-empty\blood_allergy.postman_collection.json");
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
            $jsonContent = file_get_contents("C:\Users\Acer\bloodbank_js\blood_request\slim-empty\blood_allergy.postman_collection.json");
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
});
$app->get('/getUserLogin', function (Request $request, Response $response, array $args) {
    try {
        $getTokenEndpoint = "http://iservice.med.cmu.ac.th/gateway/bb/get_token.php";
        //Approve ID : 9325
        //Doctor ID : 1254
        //BloodBank ID : 7895
        //Nurse ID : 451236
        $idUser = "1254";
        $checkTokenEndpoint = "http://iservice.med.cmu.ac.th/gateway/bb/check_token.php?uid=$idUser";

        // Prepare cURL request to get token
        $ch1 = curl_init($getTokenEndpoint);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $apiResponse = curl_exec($ch1);

        // Check for errors in obtaining token
        if (curl_errno($ch1)) {
            throw new Exception("cURL request failed: " . curl_error($ch1));
        }
        curl_close($ch1);

        // Decode the response to get token
        $result = json_decode($apiResponse, true);
        $token = $result['v'];

        // Prepare cURL request to check token validity
        $ch2 = curl_init($checkTokenEndpoint);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($token));
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: GET, POST, PUT, DELETE',
            'Access-Control-Allow-Headers: Content-Type',
            'Access-Control-Allow-Credentials: true',
        ));
        $checkTokenResponse = curl_exec($ch2);

        // Check for errors in checking token validity
        if (curl_errno($ch2)) {
            throw new Exception("cURL request failed: " . curl_error($ch2));
        }
        curl_close($ch2);
        $resultChecktoken = json_decode($checkTokenResponse, true);
        $UserInfo = $resultChecktoken['v'];

        // Process the check token response as needed
        // For example, you can return it as the response from this endpoint
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($UserInfo));
        return $response;
    } catch (Exception $error) {
        // Handle errors
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(["error" => $error->getMessage()]));
        return $response->withStatus(500);
    }
});
$app->get('/getToken', function (Request $request, Response $response, array $args) {
    try {

        $endpoint = "http://iservice.med.cmu.ac.th/gateway/bb/get_token.php";

        // Prepare cURL request
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

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

$app->get('/getListBloodTransf', function (Request $request, Response $response, array $args) use ($app) {
    try {
        $getTokenEndpoint = "http://iservice.med.cmu.ac.th/gateway/bb/get_token.php";

        $getListBloodTransfEndpoint = "http://iservice.med.cmu.ac.th/gateway/bb/list_blood_transf.php";

        $body = json_encode(array("hn" => "1235"));

        // Prepare cURL request to get token
        $ch1 = curl_init($getTokenEndpoint);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $apiResponse = curl_exec($ch1);

        // Check for errors in obtaining token
        if (curl_errno($ch1)) {
            throw new Exception("cURL request failed: " . curl_error($ch1));
        }
        curl_close($ch1);

        // Decode the response to get token
        $result = json_decode($apiResponse, true);
        $token = $result['v']['token'];
        $tokenEncode = json_encode($token);
        /* echo json_encode($token); */

        // Prepare cURL request to check token validity
        $ch2 = curl_init($getListBloodTransfEndpoint);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: GET, POST, PUT, DELETE',
            'Access-Control-Allow-Headers: Content-Type',
            'Access-Control-Allow-Credentials: true',
        ));
        $listBloodTransf = curl_exec($ch2);

        // Check for errors in checking token validity
        if (curl_errno($ch2)) {
            throw new Exception("cURL request failed: " . curl_error($ch2));
        }
        curl_close($ch2);
        $resultListBloodTransf = json_decode($listBloodTransf, true);
        $ListBloodValue = $resultListBloodTransf['v'];

        // Process the check token response as needed
        // For example, you can return it as the response from this endpoint
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($ListBloodValue));
        return $response;
    } catch (Exception $error) {
        // Handle errors
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(["error" => $error->getMessage()]));
        return $response->withStatus(500);
    }
});



$app->run();

function cors()
{

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

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit;
        }
    }
}
