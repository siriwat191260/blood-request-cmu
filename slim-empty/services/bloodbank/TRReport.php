<?php
function TRReport($p){

    // Extracting id from input parameters
    $id = $p['id'];

    $rep = new stdClass();

    $conn_db = new PDOConnect('db');
    $con_db = $conn_db->Open();

    // Selecting the database
    $con_db->exec("USE blood_request");

    // Using a prepared statement to prevent SQL injection
    $sql = "SELECT *, NeedleStatus.name AS NeedleName, PlasmaCharacteristicStatus.name AS PlasmaCharacteristicStatusName,
            IndicatiorName.name AS IndicatiorName, Indicatior.bloodBagNumber AS IndicatiorBBNum 
            FROM TR_Report
            LEFT JOIN GramStainAndCulture ON GramStainAndCulture.idTR_Report = TR_Report.idTR_Report
            LEFT JOIN TransfusionMedicalDirectorReview ON TransfusionMedicalDirectorReview.idTR_Report = TR_Report.idTR_Report
            LEFT JOIN BloodBagCharacteristic ON BloodBagCharacteristic.idTR_Report = TR_Report.idTR_Report
            LEFT JOIN PlasmaCharacteristicStatus ON BloodBagCharacteristic.needleStatus = PlasmaCharacteristicStatus.idPlasmaCharacteristicStatus
            LEFT JOIN NeedleStatus ON NeedleStatus.idNeedleStatus = BloodBagCharacteristic.needleStatus
            LEFT JOIN TransfusionMedicalDirectorReview AS Rv ON TransfusionMedicalDirectorReview.idTR_Report = TR_Report.idTR_Report
            LEFT JOIN Indicatior ON Indicatior.idTR_Report = TR_Report.idTR_Report
            LEFT JOIN IndicatiorName ON IndicatiorName.idIndicatiorName = Indicatior.idIndicatiorName
            WHERE TR_Report.idTR_Report = :id";
    $stmt = $con_db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT); // Assuming id is an integer, adjust if it's a string
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rep = $result;

    return $rep;
}
?>
