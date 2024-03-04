<?php
function TRForm($p)
{

    // Extracting id from input parameters
    $id = $p['id'];


    $rep = new stdClass();

    $conn_db = new PDOConnect('db');
    $con_db = $conn_db->Open();

    // Selecting the database
    $con_db->exec("USE blood_request");

    // Using a prepared statement to prevent SQL injection
    $sql = "SELECT *
            FROM TR_Form
            LEFT JOIN BloodTransfusionTest ON BloodTransfusionTest.idTR_Form = TR_Form.idTR_Form
            LEFT JOIN VitalSigns ON VitalSigns.idTR_Form = TR_Form.idTR_Form
            LEFT JOIN ( SELECT * FROM DetailRecordIn24Hrs WHERE DetailRecordIn24Hrs.idTR_Form = :id) 
            AS DetailRecordIn24Hrs ON DetailRecordIn24Hrs.idTR_Form = TR_Form.idTR_Form
            LEFT JOIN ( SELECT * FROM SignsAndSymptoms WHERE SignsAndSymptoms.idTR_Form = :id) 
            AS SignsAndSymptoms ON SignsAndSymptoms.idTR_Form = TR_Form.idTR_Form
            LEFT JOIN SubmittingTest ON SubmittingTest.idTR_Form = TR_Form.idTR_Form
            WHERE TR_Form.idTR_Form = :id";
    $stmt = $con_db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT); // Assuming id is an integer, adjust if it's a string
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rep = $result;

    return $rep;
}