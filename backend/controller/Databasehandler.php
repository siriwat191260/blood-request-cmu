<?php

class DatabaseHandler {
    private $pdoConnect;

    public function __construct($dbname) {
        $this->pdoConnect = new PDOConnect($dbname);
    }

    public function getData($sql) {
        // Open the database connection
        $con = $this->pdoConnect->Open();

        // Check if the connection was successful
        if ($con === null) {
            return null; // Or throw an exception if preferred
        }

        $result = $con->query($sql);

        if ($result === false) {
            // Handle query error
            return null; // Or throw an exception if preferred
        }

        // Fetch data as an associative array
        $data = $result->fetchAll(PDO::FETCH_ASSOC);

        // Close the database connection
        $this->pdoConnect->Close();

        return $data;
    }
}
?>
