<?php
function TRForm($p){

    // Extracting id from input parameters
    $id = $p['id'];


    $rep = new stdClass();

    $conn_db = new PDOConnect('db');
    $con_db = $conn_db->Open();

    // Selecting the database
    $con_db->exec("USE blood_request");

    // Using a prepared statement to prevent SQL injection
    $sql = "SELECT * 
            FROM blood_request.TR_Form 
            WHERE idTR_Form = :id";
    $stmt = $con_db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT); // Assuming id is an integer, adjust if it's a string
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rep = $result;

    return $rep;

}
?>
