<?php
function TRForm_Insert($p)
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
    // Extract individual sections of the form data
    $PatientInfo = $formData['PatientInfo'];
    $BloodTransfusionTest = $formData['BloodTransfusionTest'];
    $VitalSigns = $formData['VitalSigns'];
    $SignsAndSymptoms = $formData['SignsAndSymptomsObject'];
    $DetailRecordIn24Hrs = $formData['DetailRecordIn24Hrs'];
    $SubmittingTest = $formData['SubmittingTest'];
    /* echo '<pre>';
        print_r($PatientInfo);
        echo '</pre>'; 
 */
    $con_db->beginTransaction();
    $status = 0;
    if ($PatientInfo !== null) {
        $sqlPatientInfo = "INSERT INTO blood_request.TR_Form 
                        (`title`, `firstName`, `lastName`, `HN`, `TXN`, `createdDate`, `ward`, `phoneNumber`, 
                        `diagnosis`, `primaryPhysicianName`, `bloodGroup_Patient`, `Rh_Patient`, `blood_component`, 
                        `bloodGroup_Donor`, `Rh_Donor`, `bloodBagNumber`, `volume`, `medicationHistory`, 
                        `isReactionHistory`, `reactionCategory`)
                        VALUES 
                        (:title, :firstName, :lastName, :HN, :TXN, :createdDate, :ward, :phoneNumber, 
                        :diagnosis, :primaryPhysicianName, :bloodGroup_Patient, :Rh_Patient, :blood_component, 
                        :bloodGroup_Donor, :Rh_Donor, :bloodBagNumber, :volume, :medicationHistory, 
                        :isReactionHistory, :reactionCategory)";

        $stmtPatientInfo  = $con_db->prepare($sqlPatientInfo);
        if($PatientInfo['medicationHistory'] !== null && $PatientInfo['isReactionHistory'] !== null){
            $status += 5;
        }
        //bind to TR_Form
        foreach ($PatientInfo as $key => $value) {
            if ($key === 'createdDate') {
                $formattedDate = (DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $value))->setTimezone(new DateTimeZone('+07:00'))->format('Y-m-d H:i:s');
                $stmtPatientInfo->bindValue(":$key", $formattedDate, PDO::PARAM_STR);
            } else {
                $stmtPatientInfo->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }
        $stmtPatientInfo->execute();
        if ($stmtPatientInfo) {
            $rep['s'] = true;
        }else {
            $rep['s'] = false;
        }
    }

    // Get the last inserted ID
    $idTR_Form = $con_db->lastInsertId();
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
            if(insertIntoTable($con_db, $tableNameBloodTransfusionTest, $BloodTransfusionTest, $idTR_Form)){
                $rep['s'] = true;
                } else {
                    $rep['s'] = false;
                }
            if($BloodTransfusionTest['isCorrectBloodBagNumber'] !== null && $BloodTransfusionTest['isCorrectBloodComponent'] !== null &&
                $BloodTransfusionTest['isCorrectBloodGroupDonor'] !== null && $BloodTransfusionTest['isCorrectBloodGroupPatient'] !== null && 
                $BloodTransfusionTest['isCorrectBloodTransfusionRec'] !== null && $BloodTransfusionTest['isCorrectPatientName'] !== null && 
                $BloodTransfusionTest['isWithin24hrsFever'] !== null){
                    $status += 15;
            }
        }

        //VitalSigns
        if ($VitalSigns !== null && ($VitalSigns['afterReactionBP'] !== null ||
            $VitalSigns['afterReactionPulse'] !== null || $VitalSigns['afterReactionTemp'] !== null ||
            $VitalSigns['afterReactionTime'] !== null || $VitalSigns['beforeReactionBP'] !== null ||
            $VitalSigns['beforeReactionPulse'] !== null || $VitalSigns['beforeReactionTemp'] !== null || $VitalSigns['beforeReactionTime'] !== null)) {
             /* print($VitalSigns['afterReactionTime']); */
                if(insertIntoTable($con_db, $tableNameVitalSigns, $VitalSigns, $idTR_Form)){
                $rep['s'] = true;
                } else {
                    $rep['s'] = false;
                }
                if($VitalSigns['afterReactionBP'] !== null && $VitalSigns['afterReactionPulse'] !== null && $VitalSigns['afterReactionTemp'] !== null && 
                $VitalSigns['afterReactionTime'] !== null && $VitalSigns['beforeReactionBP'] !== null && $VitalSigns['beforeReactionPulse'] !== null && 
                $VitalSigns['beforeReactionTemp'] !== null && $VitalSigns['beforeReactionTime'] !== null){
                    $status += 20;
                }
        }

        //SignsAndSymptoms
        if ($SignsAndSymptoms !== null && (($SignsAndSymptoms['idSignsAndSymptomsName'] !== null &&
            count($SignsAndSymptoms['idSignsAndSymptomsName']) > 0) || $SignsAndSymptoms['Other'] !== null)) {
            foreach ($SignsAndSymptoms['idSignsAndSymptomsName'] as $idSignsAndSymptomsName) {
                $stmt = $con_db->prepare("INSERT INTO $tableNameSignsAndSymptoms (idSignsAndSymptomsName, idTR_Form) VALUES (:idSignsAndSymptomsName, :idTR_Form)");
                $stmt->bindParam(':idSignsAndSymptomsName', $idSignsAndSymptomsName);
                $stmt->bindParam(':idTR_Form', $idTR_Form);
                $stmt->execute();
                if($stmt){
                    $rep['s'] = true;
                }else {
                    $rep['s'] = false;
                }
            }
            if ($SignsAndSymptoms['Other'] !== null) {
                $stmt = $con_db->prepare("INSERT INTO SignsAndSymptoms (Other, idTR_Form) VALUES (:Other, :idTR_Form)");
                $stmt->bindParam(':Other', $SignsAndSymptoms['Other']);
                $stmt->bindParam(':idTR_Form', $idTR_Form);
                $stmt->execute();
                if($stmt){
                    $rep['s'] = true;
                }else {
                    $rep['s'] = false;
                }
            }
            if(count($SignsAndSymptoms['idSignsAndSymptomsName']) > 0 || $SignsAndSymptoms['Other'] !== null){
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
                if(insertIntoTable($con_db, $tableNameDetailRecordIn24Hrs, $row, $idTR_Form)){
                    $rep['s'] = true;
                    }else {
                        $rep['s'] = false;
                    }
                if($row['isReaction'] !== null){
                    $status += (20/count($DetailRecordIn24Hrs));
                }
            }
        }

        //SubmittingTest
        if ($SubmittingTest !== null && ($SubmittingTest['isBloodBagReaction'] !== null ||
            $SubmittingTest['isBloodSample'] !== null || $SubmittingTest['nurseDateTime'] !== null ||
            $SubmittingTest['nurseName'] !== null || $SubmittingTest['physicianDateTime'] !== null ||
            $SubmittingTest['physicianName'] !== null)) {
            
            if(insertIntoTable($con_db, $tableNameSubmittingTest, $SubmittingTest, $idTR_Form)){
                $rep['s'] = true;
                }else{
                    $rep['s'] = false;
                }
            if($SubmittingTest['isBloodBagReaction'] !== null && $SubmittingTest['isBloodSample'] !== null && 
            $SubmittingTest['nurseDateTime'] !== null && $SubmittingTest['nurseName'] !== null && 
            $SubmittingTest['physicianDateTime'] !== null && $SubmittingTest['physicianName'] !== null){
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
        }else {
            $rep['s'] = false;
        }
    }



    $con_db->commit();


    return $rep;
}
//dynamic insert
function insertIntoTable($con_db, $tableName, $data, $idTR_Form)
{
    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO $tableName (";
        $sql .= implode(', ', array_keys($data)); // Column names
        $sql .= ", idTR_Form) VALUES (";
        $sql .= implode(', ', array_map(function ($item) {
            return ':' . $item;
        }, array_keys($data))); // Parameter placeholders
        $sql .= ", :idTR_Form)";

        $stmt = $con_db->prepare($sql);

        // Bind values
        foreach ($data as $key => $value) {
            if ($value === "") {
                $stmt->bindValue(":$key", null, PDO::PARAM_NULL);
            }else if ($key === 'beforeReactionTime' || $key === 'afterReactionTime') {
                // Append a date to the time value
                $time = DateTime::createFromFormat('H:i', $value);
                $formattedDateTime = $time->setTimezone(new DateTimeZone('+07:00'))->format('Y-m-d H:i:s');
                $stmt->bindValue(":$key", $formattedDateTime, PDO::PARAM_STR);
            }else if ($key === 'nurseDateTime' || $key === 'physicianDateTime') {
                $formattedDate = DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $value)->setTimezone(new DateTimeZone('+07:00'))->format('Y-m-d H:i:s');
                $stmt->bindValue(":$key", $formattedDate, PDO::PARAM_STR);
            }else {
                $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }
        $stmt->bindValue(":idTR_Form", $idTR_Form, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        error_log("Error inserting data into table $tableName: " . $e->getMessage());
        
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
