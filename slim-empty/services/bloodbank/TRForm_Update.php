<?php
function TRForm_Update($p)
{

    $cf_m = $p['cf']['mt']['cf'];
    $cf_s = $p['cf']['mt']['services'];

    $rep = array(
        "s" => false,
        "c" => "",
        "v" => array()
    );

    $conn_db = new PDOConnect('db');
    $con_db = $conn_db->Open();

    // Selecting the database
    $con_db->exec("USE blood_request");

    $formData = $p['formData'];
    $id = $p['id'];
    $idTR_Form = $id['id'];
    // Extract individual sections of the form data
    $PatientInfo = $formData['PatientInfo'];
    $BloodTransfusionTest = $formData['BloodTransfusionTest'];
    $VitalSigns = $formData['VitalSigns'];
    $SignsAndSymptoms = $formData['SignsAndSymptomsObject'];
    $DetailRecordIn24Hrs = $formData['DetailRecordIn24Hrs'];
    $SubmittingTest = $formData['SubmittingTest'];
     echo '<pre>';
        print_r($idTR_Form);
        echo '</pre>'; 
 
    $status = 0;
    if ($PatientInfo !== null && ($PatientInfo['medicationHistory'] !== null || $PatientInfo['isReactionHistory'] !== null || $PatientInfo['reactionCategory'] !== null)) {
        $sqlPatientInfo = "UPDATE blood_request.TR_Form SET medicationHistory = :medicationHistory, 
        isReactionHistory = :isReactionHistory, reactionCategory = :reactionCategory 
        WHERE idTR_Form = :idTR_Form";
        $stmtPatientInfo = $con_db->prepare($sqlPatientInfo);
        $stmtPatientInfo->bindValue(":medicationHistory", $PatientInfo['medicationHistory'], PDO::PARAM_STR);
        $stmtPatientInfo->bindValue(":isReactionHistory", $PatientInfo['isReactionHistory'], PDO::PARAM_STR);
        $stmtPatientInfo->bindValue(":reactionCategory", $PatientInfo['reactionCategory'], PDO::PARAM_STR);
        $stmtPatientInfo->bindValue(":idTR_Form", $idTR_Form, PDO::PARAM_INT);
        if ($stmtPatientInfo->execute()) {
            $rep['s'] = true;
            // Increment status if both medicationHistory and isReactionHistory are not null
            if ($PatientInfo['medicationHistory'] !== null && $PatientInfo['isReactionHistory'] !== null) {
                $status += 5;
            }
        } else {
            $rep['s'] = false;
        }
    }



    // Get the last inserted ID
    $tableNameBloodTransfusionTest = "blood_request.BloodTransfusionTest";
    $tableNameVitalSigns = "blood_request.VitalSigns";
    $tableNameSignsAndSymptoms = "blood_request.SignsAndSymptoms";
    $tableNameDetailRecordIn24Hrs = "blood_request.DetailRecordIn24Hrs";
    $tableNameSubmittingTest = "blood_request.SubmittingTest";

    if ($idTR_Form) {
        //BloodTransfusionTest
        if ($BloodTransfusionTest !== null && ($BloodTransfusionTest['isCorrectBloodBagNumber'] !== null ||
            $BloodTransfusionTest['isCorrectBloodComponent'] !== null || $BloodTransfusionTest['isCorrectBloodGroupDonor'] !== null ||
            $BloodTransfusionTest['isCorrectBloodGroupPatient'] !== null || $BloodTransfusionTest['isCorrectBloodTransfusionRec'] !== null ||
            $BloodTransfusionTest['isCorrectPatientName'] !== null || $BloodTransfusionTest['isWithin24hrsFever'] !== null)) {
            if (updateTable($con_db, $tableNameBloodTransfusionTest, $BloodTransfusionTest, $idTR_Form, $status)) {
                $rep['s'] = true;
            } else {
                $rep['s'] = false;
            }
            if (
                $BloodTransfusionTest['isCorrectBloodBagNumber'] !== null && $BloodTransfusionTest['isCorrectBloodComponent'] !== null &&
                $BloodTransfusionTest['isCorrectBloodGroupDonor'] !== null && $BloodTransfusionTest['isCorrectBloodGroupPatient'] !== null &&
                $BloodTransfusionTest['isCorrectBloodTransfusionRec'] !== null && $BloodTransfusionTest['isCorrectPatientName'] !== null &&
                $BloodTransfusionTest['isWithin24hrsFever'] !== null
            ) {
                $status += 15;
            }
        }

        //VitalSigns
        if ($VitalSigns !== null && ($VitalSigns['afterReactionBP'] !== null ||
            $VitalSigns['afterReactionPulse'] !== null || $VitalSigns['afterReactionTemp'] !== null ||
            $VitalSigns['afterReactionTime'] !== null || $VitalSigns['beforeReactionBP'] !== null ||
            $VitalSigns['beforeReactionPulse'] !== null || $VitalSigns['beforeReactionTemp'] !== null || $VitalSigns['beforeReactionTime'] !== null)) {
            /* print($VitalSigns['afterReactionTime']); */
            if (updateTable($con_db, $tableNameVitalSigns, $VitalSigns, $idTR_Form)) {
                $rep['s'] = true;
            } else {
                $rep['s'] = false;
            }
            if (
                $VitalSigns['afterReactionBP'] !== null && $VitalSigns['afterReactionPulse'] !== null && $VitalSigns['afterReactionTemp'] !== null &&
                $VitalSigns['afterReactionTime'] !== null && $VitalSigns['beforeReactionBP'] !== null && $VitalSigns['beforeReactionPulse'] !== null &&
                $VitalSigns['beforeReactionTemp'] !== null && $VitalSigns['beforeReactionTime'] !== null
            ) {
                $status += 20;
            }
        }

        //SignsAndSymptoms
        if ($SignsAndSymptoms !== null && (($SignsAndSymptoms['idSignsAndSymptomsName'] !== null &&
            count($SignsAndSymptoms['idSignsAndSymptomsName']) > 0) || $SignsAndSymptoms['Other'] !== null)) {
                $stmtDelete = $con_db->prepare("DELETE FROM $tableNameSignsAndSymptoms WHERE idTR_Form =:idTR_Form");
                $stmtDelete->bindParam(':idTR_Form', $idTR_Form);
                $stmtDelete->execute();
            foreach ($SignsAndSymptoms['idSignsAndSymptomsName'] as $idSignsAndSymptomsName) {
                $stmt = $con_db->prepare("INSERT INTO $tableNameSignsAndSymptoms (idSignsAndSymptomsName, idTR_Form) VALUES (:idSignsAndSymptomsName, :idTR_Form)");
                $stmt->bindParam(':idSignsAndSymptomsName', $idSignsAndSymptomsName);
                $stmt->bindParam(':idTR_Form', $idTR_Form);
                $stmt->execute();
                if ($stmt) {
                    $rep['s'] = true;
                } else {
                    $rep['s'] = false;
                }
            }
            if ($SignsAndSymptoms['Other'] !== null) {
                $stmt = $con_db->prepare("INSERT INTO SignsAndSymptoms (Other, idTR_Form) VALUES (:Other, :idTR_Form)");
                $stmt->bindParam(':Other', $SignsAndSymptoms['Other']);
                $stmt->bindParam(':idTR_Form', $idTR_Form);
                $stmt->execute();
                if ($stmt) {
                    $rep['s'] = true;
                } else {
                    $rep['s'] = false;
                }
            }
            if (count($SignsAndSymptoms['idSignsAndSymptomsName']) > 0 || $SignsAndSymptoms['Other'] !== null) {
                $status += 20;
            }
        }

        //DetailRecordIn24Hrs
        if ($DetailRecordIn24Hrs !== null && count($DetailRecordIn24Hrs) > 0) {
            $keyMap = [
                "dtm" => "startTransfusion",
                "dtm_off" => "endTransfusion",
                "packid" => "bloodBagNumber",
                "product" => "bloodComponent",
                "blood_grp" => "bloodGroup",
                "blood_rh" => "bloodRH",
                "vol" => "volume",
                "isReaction" => "isReaction"
            ];
            $modifiedData = changeKeyNames($DetailRecordIn24Hrs, $keyMap);
            foreach ($modifiedData as $row) {
                if (updateTable($con_db, $tableNameDetailRecordIn24Hrs, $row, $idTR_Form)) {
                    $rep['s'] = true;
                } else {
                    $rep['s'] = false;
                }
                if ($row['isReaction'] !== null) {
                    $status += (20 / count($DetailRecordIn24Hrs));
                }
            }
        }

        //SubmittingTest
        if ($SubmittingTest !== null && ($SubmittingTest['isBloodBagReaction'] !== null ||
            $SubmittingTest['isBloodSample'] !== null || $SubmittingTest['nurseDateTime'] !== null ||
            $SubmittingTest['nurseName'] !== null || $SubmittingTest['physicianDateTime'] !== null ||
            $SubmittingTest['physicianName'] !== null)) {

            if (updateTable($con_db, $tableNameSubmittingTest, $SubmittingTest, $idTR_Form)) {
                $rep['s'] = true;
            } else {
                $rep['s'] = false;
            }
            if (
                $SubmittingTest['isBloodBagReaction'] !== null && $SubmittingTest['isBloodSample'] !== null &&
                $SubmittingTest['nurseDateTime'] !== null && $SubmittingTest['nurseName'] !== null &&
                $SubmittingTest['physicianDateTime'] !== null && $SubmittingTest['physicianName'] !== null
            ) {
                $status += 20;
            }
        }
    }

    if ($status !== 0 && $idTR_Form) {
        $sqlStatus = "UPDATE blood_request.TR_Form SET `status`=:status WHERE idTR_Form = :idTR_Form ";
        $stmtStatus  = $con_db->prepare($sqlStatus);
        //bind to TR_Form
        $stmtStatus->bindValue(":status", $status, PDO::PARAM_STR);
        $stmtStatus->bindValue(":idTR_Form", $idTR_Form, PDO::PARAM_STR);
        $stmtStatus->execute();
        if ($stmtStatus) {
            $rep['s'] = true;
        } else {
            $rep['s'] = false;
        }
    }




    


    return $rep;
}
//dynamic insert
function updateTable($con_db, $tableName, $data, $idTR_Form)
{
    try {
        // Prepare the SQL statement
        $sql = "UPDATE $tableName SET ";
        $setValues = [];
        $idDetailRecordIn24Hrs = [];
        foreach ($data as $key => $value) {
            $setValues[] = "$key = :$key";
            if ($key === "idDetailRecordIn24Hrs") {
                $idDetailRecordIn24Hrs[] = "AND $value";
            }
        }
        $sql .= implode(', ', $setValues);
        if ($tableName === "blood_request.DetailRecordIn24Hrs") {
            $sql .= " WHERE idTR_Form = :idTR_Form ";
            $sql .= implode(', ', $idDetailRecordIn24Hrs);
        } else {
            $sql .= " WHERE idTR_Form = :idTR_Form";
        }

        // Check if the row already exists in the table
        $checkSql = "SELECT COUNT(*) FROM $tableName WHERE idTR_Form = :idTR_Form";
        $checkStmt = $con_db->prepare($checkSql);
        $checkStmt->bindValue(":idTR_Form", $idTR_Form, PDO::PARAM_INT);
        $checkStmt->execute();
        $rowExists = ($checkStmt->fetchColumn() > 0);

        // If the row exists, update it; otherwise, insert a new row
        if ($rowExists) {
            $sql = "INSERT INTO $tableName (" . implode(', ', array_keys($data)) . ") VALUES (" . implode(', ', array_map(function ($item) {
                return ':' . $item;
            }, array_keys($data))) . ") ON DUPLICATE KEY UPDATE " . implode(', ', $setValues);
        }

        $stmt = $con_db->prepare($sql);

        // Bind values
        foreach ($data as $key => $value) {
            if ($value === "") {
                $stmt->bindValue(":$key", null, PDO::PARAM_NULL);
            } else if ($key === 'beforeReactionTime' || $key === 'afterReactionTime') {
                // Append a date to the time value
                $time = DateTime::createFromFormat('H:i', $value);
                $formattedDateTime = $time->setTimezone(new DateTimeZone('+07:00'))->format('Y-m-d H:i:s');
                $stmt->bindValue(":$key", $formattedDateTime, PDO::PARAM_STR);
            } else if ($key === 'nurseDateTime' || $key === 'physicianDateTime') {
                $formattedDate = DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $value)->setTimezone(new DateTimeZone('+07:00'))->format('Y-m-d H:i:s');
                $stmt->bindValue(":$key", $formattedDate, PDO::PARAM_STR);
            } else {
                $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }
        $stmt->bindValue(":idTR_Form", $idTR_Form, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        error_log("Error updating data in table $tableName: " . $e->getMessage());
        
        // Return false if an exception occurred
        return false;
    }
}





function changeKeyNames($data, $keyMap)
{
    $modifiedData = [];
    foreach ($data as $item) {
        $newItem = [];
        foreach ($item as $key => $value) {
            if (array_key_exists($key, $keyMap)) {
                $newItem[$keyMap[$key]] = $value;
            } else {
                $newItem[$key] = $value;
            }
        }
        $modifiedData[] = $newItem;
    }
    return $modifiedData;
}
