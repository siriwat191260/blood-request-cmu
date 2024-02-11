<?php
    // DataController.php
    use Slim\Psr7\Stream;

class SignsAndSymptomsNameController {
    private $databaseHandler;

    public function __construct($dbname) {
        $this->databaseHandler = new DatabaseHandler($dbname);
    }

    public function getData($response) {
        $sql = "SELECT * FROM blood_request.SignsAndSymptomsName";
        $data = $this->databaseHandler->getData($sql);

        if ($data === null) {
            return $response->withJson(['error' => 'Failed to retrieve data from the database']);
        }

        // Convert data to JSON
        $jsonData = json_encode(['data' => $data]);

        // Create a new stream with the JSON data
        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write($jsonData);

        // Output the data
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200)
            ->withBody($stream);
    }
}
