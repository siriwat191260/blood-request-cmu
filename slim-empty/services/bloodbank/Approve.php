<?php
function Approve($p)
{
    $rep = array(
        "s" => false,
        "c" => "",
        "v" => array()
    );

    $conn_db = new PDOConnect('db');
    $con_db = $conn_db->Open();
    $con_db->exec("USE blood_request");

    $formData = $p['formData'];
    $idTR_Report = $formData['idTR_Report'];
    $con_db->beginTransaction();

    if ($formData !== null) {
        //select data
        $sqldata = "SELECT COUNT(*) FROM blood_request.TransfusionMedicalDirectorReview WHERE idTR_Report = :idTR_Report";
        $stmtdata = $con_db->prepare($sqldata);
        $stmtdata->bindValue(":idTR_Report", $idTR_Report, PDO::PARAM_INT);
        $stmtdata->execute();
        $rowExists = ($stmtdata->fetchColumn() > 0);

        if ($rowExists) {
             // Row exists, update it
            $sqldata = "UPDATE blood_request.TransfusionMedicalDirectorReview SET
            reaction = :reaction, caseDefinitionCriteria = :caseDefinitionCriteria, severity = :severity, 
            imputability = :imputability, approvedBy = :approvedBy, approvedDateTime = :approvedDateTime, other = :other
            WHERE idTR_Report = :idTR_Report";
        } else {
            // Row does not exist, insert a new row
            $sqldata = "INSERT INTO blood_request.TransfusionMedicalDirectorReview 
            (reaction, caseDefinitionCriteria, severity, imputability, approvedBy, approvedDateTime, other, idTR_Report) 
            VALUES 
            (:reaction, :caseDefinitionCriteria, :severity, :imputability, :approvedBy, :approvedDateTime, :other, :idTR_Report)";
        }

        // Bind values
        $stmtdata = $con_db->prepare($sqldata);
        foreach ($formData as $key => $value) {
            if ($value === "" || $value === null) {
                $stmtdata->bindValue(":$key", null, PDO::PARAM_NULL);
            } else if ($key === 'approvedDateTime') {
                $formattedDate = (DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $value))->setTimezone(new DateTimeZone('+07:00'))->format('Y-m-d H:i:s');
                $stmtdata->bindValue(":$key", $formattedDate, PDO::PARAM_STR);
            } else {
                $stmtdata->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }
        $stmtdata->execute();

        if ($stmtdata) {
            $rep['s'] = true;
        } else {
            $rep['s'] = false;
        }
    }

    $con_db->commit();

    return $rep;
}

?>