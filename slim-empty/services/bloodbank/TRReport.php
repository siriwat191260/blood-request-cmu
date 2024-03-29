<?php
function TRReport($p)
{

    // Extracting id from input parameters
    $id = $p['id'];

    $conn_db = new PDOConnect('db');
    $con_db = $conn_db->Open();
    $con_db->exec("USE blood_request");

    // select data from TR_Report
    $sql = "SELECT *
            FROM TR_Report
            LEFT JOIN TransfusionMedicalDirectorReview ON TransfusionMedicalDirectorReview.idTR_Report = TR_Report.idTR_Report
            WHERE TR_Report.idTR_Report = :id";
    $stmt = $con_db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $rep['data'] = $data[0];

    // select data from GramStainAndCulture
    $sql = "SELECT *
            FROM GramStainAndCulture
            WHERE idTR_Report = :id";
    $stmt = $con_db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($data)) {
        $rep['GramStainAndCulture'] = $data[0];
    } else {
        $temp = array(
            'isSubmittingGramStain' => "",
            'gramNegativeOrPositive' => "",
            'resultGramStain' => "",
            'toDateGram' => "",
            'isSubmittingCulture' => "",
            'cultureNegativeOrPositive' => "",
            'resultCulture' => "",
            'toDateCulture' => "",
        );
        $rep['GramStainAndCulture'] = $temp;
    }

    // select data from BloodBagCharacteristic
    $sql = "SELECT *, NeedleStatus.name AS NeedleName, PlasmaCharacteristicStatus.name AS PlasmaCharacteristicStatusName
            FROM BloodBagCharacteristic
            LEFT JOIN PlasmaCharacteristicStatus ON BloodBagCharacteristic.plasmaCharacteristicStatus = PlasmaCharacteristicStatus.idPlasmaCharacteristicStatus
            LEFT JOIN NeedleStatus ON NeedleStatus.idNeedleStatus = BloodBagCharacteristic.needleStatus
            WHERE idTR_Report = :id";
    $stmt = $con_db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($data)) {
        $rep['BloodBagCharacteristic'] = $data[0];
    } else {
        $temp = array(
            'isTransfusionSet' => "",
            'needleStatus' => "",
            'plasmaCharacteristicStatus' => "",
            'isLeakagePosition' => "",
            'leakagePosition' => "",
            'volumeOfBag' => "",
            'TransfusionVolume' => "",
        );
        $rep['BloodBagCharacteristic'] = $temp;
    }

    // select data from Indicator
    $ind = array();
    for ($i = 1; $i <= 10; $i++) {
        $sql = "SELECT * FROM Indicator
                WHERE idTR_Report = :id AND Indicator.idIndicatorName = :i";
        $stmt = $con_db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":i", $i, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            $temp = array(
                'idIndicatorName' => $result[0]['idIndicatorName'],
                'PreTransfusionSample' => $result[0]['PreTransfusionSample'],
                'PostTransfusionSample' => $result[0]['PostTransfusionSample'],
                'bloodBagNumber' => $result[0]['bloodBagNumber'],
                'Remarks' => $result[0]['Remarks'],
            );
        } else {
            $temp = array(
                'idIndicatorName' => $i,
                'PreTransfusionSample' => null,
                'PostTransfusionSample' => null,
                'bloodBagNumber' => null,
                'Remarks' => null
            );
        }
        $ind[] = $temp;
    }

    $rep['indicator'] = $ind;

    return $rep;
}
?>