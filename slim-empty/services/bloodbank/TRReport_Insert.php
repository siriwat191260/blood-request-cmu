<?php
function TRReport_Insert($p)
{
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
    $data = $formData['data'];
    $BloodBagCharacteristic = $formData['BloodBagCharacteristic'];
    $indicators = $formData['indicator'];
    $GramStainAndCulture = $formData['GramStainAndCulture'];

    $con_db->beginTransaction();
    $status = 0;
    if ($data !== null) {
        $sqldata = "INSERT INTO blood_request.TR_Report 
        (`HN`,`title`, `firstName`, `lastName`, `createdDate`, `ward`,`bloodGroup_Patient`, 
        `blood_component`, `bloodGroup_Donor`,`bloodBagNumber`, `primaryPhysicianName`,
        `nurse`, `interpretation`, `testedBy`, `testedDateTime`, `reportedBy`, `reportedDateTime`,`status`)
        VALUES 
        (:HN, :title, :firstName, :lastName, :createdDate, :ward, :bloodGroup_Patient, 
        :blood_component, :bloodGroup_Donor, :bloodBagNumber, :primaryPhysicianName,
        :nurse, :interpretation, :testedBy, :testedDateTime, :reportedBy, :reportedDateTime, 0)";

        $stmtdata = $con_db->prepare($sqldata);

        foreach ($data as $key => $value) {
            if ($value !== null && ($key === 'createdDate' || $key === 'testedDateTime' || $key === 'reportedDateTime')) {
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

        if ($data['interpretation'] !== null) {
            $status += 5;
        }
        if ($data['reportedBy'] !== null) {
            $status += 5;
        }
        if ($data['testedBy'] !== null) {
            $status += 5;
        }
    }

    // Get the last inserted ID
    $idTR_Report = $con_db->lastInsertId();
    $tableNameBloodBagCharacteristic = "blood_request.BloodBagCharacteristic";
    $tableNameindicator = "blood_request.Indicator";
    $tableNameGramStainAndCulture = "blood_request.GramStainAndCulture";

    if ($idTR_Report) {
        //BloodBagCharacteristic
        if (
            $BloodBagCharacteristic !== null && ($BloodBagCharacteristic['isTransfusionSet'] !== null ||
                $BloodBagCharacteristic['needleStatus'] !== null || $BloodBagCharacteristic['plasmaCharacteristicStatus'] !== null ||
                $BloodBagCharacteristic['isLeakagePosition'] !== null || $BloodBagCharacteristic['leakagePosition'] !== null ||
                $BloodBagCharacteristic['volumeOfBag'] !== null || $BloodBagCharacteristic['TransfusionVolume'] !== null)
        ) {
            if (insertIntoTable($con_db, $tableNameBloodBagCharacteristic, $BloodBagCharacteristic, $idTR_Report)) {
                $rep['s'] = true;
            } else {
                $rep['s'] = false;
            }
            if ($BloodBagCharacteristic['isTransfusionSet'] !== null) {
                $status += 5;
            }
            if ($BloodBagCharacteristic['needleStatus'] !== null) {
                $status += 5;
            }
            if ($BloodBagCharacteristic['plasmaCharacteristicStatus'] !== null) {
                $status += 5;
            }
            if (($BloodBagCharacteristic['isLeakagePosition'] !== null && $BloodBagCharacteristic['isLeakagePosition'] != 1) 
            || $BloodBagCharacteristic['leakagePosition'] !== null) {
                $status += 5;
            }
            if ($BloodBagCharacteristic['volumeOfBag'] !== null && $BloodBagCharacteristic['TransfusionVolume'] !== null) {
                $status += 5;
            }
        }

        //indicator


        foreach ($indicators as $indicator) {
            if (
                $indicator !== null && (
                    $indicator['PreTransfusionSample'] !== null || $indicator['PostTransfusionSample'] !== null ||
                    $indicator['bloodBagNumber'] !== null || $indicator['Remarks'] !== null)
            ) {
                // $stmt = $con_db->prepare("INSERT INTO $tableNameindicator (idindicatorName, PreTransfusionSample, PostTransfusionSample, bloodBagNumber, Remarks, idTR_Report) VALUES (?, ?, ?, ?, ?, ?)");
                // $stmt->bindParam(':idindicatorName', $indicator['idindicatorName']);
                // $stmt->bindParam(':PreTransfusionSample', $indicator['PreTransfusionSample']);
                // $stmt->bindParam(':PostTransfusionSample', $indicator['PostTransfusionSample']);
                // $stmt->bindParam(':bloodBagNumber', $indicator['bloodBagNumber']);
                // $stmt->bindParam(':Remarks', $indicator['Remarks']);
                // $stmt->bindParam(':idTR_Report', $idTR_Report);
                // $stmt->execute();
                // if ($stmt) {
                //     $rep['s'] = true;
                // } else {
                //     $rep['s'] = false;
                // }
                var_dump($indicator);
                if (insertIntoTable($con_db, $tableNameindicator, $indicator, $idTR_Report)) {
                    $rep['s'] = true;
                } else {
                    $rep['s'] = false;
                }
                $status += 5;
            }
        }

        //GramStainAndCulture
        if (
            $GramStainAndCulture !== null && ($GramStainAndCulture['isSubmittingGramStain'] !== null ||
                $GramStainAndCulture['gramNegativeOrPositive'] != null || $GramStainAndCulture['isSubmittingCulture'] !== null
            || $GramStainAndCulture['cultureNegativeOrPositive'] !== null)
        ) {
            if (insertIntoTable($con_db, $tableNameGramStainAndCulture, $GramStainAndCulture, $idTR_Report)) {
                $rep['s'] = true;
            } else {
                $rep['s'] = false;
            }

            if (
                ($GramStainAndCulture['isSubmittingGramStain'] != 1 && $GramStainAndCulture['isSubmittingGramStain'] !== null) ||
                ($GramStainAndCulture['resultGramStain'] !== null || $GramStainAndCulture['gramNegativeOrPositive'] == 0)
            ) {
                $status += 5;
            }
            if (
                ($GramStainAndCulture['isSubmittingCulture'] != 1 && $GramStainAndCulture['isSubmittingCulture'] !== null)||
                ($GramStainAndCulture['resultCulture'] !== null || $GramStainAndCulture['cultureNegativeOrPositive'] == 0)
            ) {
                $status += 5;
            }
        }

    }

    if ($status !== 0 && $idTR_Report) {
        $sqlStatus = "UPDATE blood_request.TR_Report SET `status`=:status WHERE idTR_Report = :idTR_Report ";
        $stmtStatus = $con_db->prepare($sqlStatus);

        $stmtStatus->bindValue(":status", $status, PDO::PARAM_STR);
        $stmtStatus->bindValue(":idTR_Report", $idTR_Report, PDO::PARAM_STR);
        $stmtStatus->execute();
        if ($stmtStatus) {
            $rep['s'] = true;
        } else {
            $rep['s'] = false;
        }
    }

    $con_db->commit();

    return $rep;
}
//dynamic insert
function insertIntoTable($con_db, $tableName, $data, $idTR_Report)
{
    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO $tableName (";
        $sql .= implode(', ', array_keys($data)); // Column names
        $sql .= ", idTR_Report) VALUES (";
        $sql .= implode(', ', array_map(function ($item) {
            return ':' . $item;
        }, array_keys($data))); // Parameter placeholders
        $sql .= ", :idTR_Report)";

        $stmt = $con_db->prepare($sql);

        // Bind values
        foreach ($data as $key => $value) {
            if ($value === "" || $value === null) {
                $stmt->bindValue(":$key", null, PDO::PARAM_NULL);
            } else if ($key === 'toDateCulture' || $key === 'toDateGram') {
                $formattedDate = (DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $value))->setTimezone(new DateTimeZone('+07:00'))->format('Y-m-d H:i:s');
                $stmt->bindValue(":$key", $formattedDate, PDO::PARAM_STR);
            } else {
                $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }
        $stmt->bindValue(":idTR_Report", $idTR_Report, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        error_log("Error inserting data into table $tableName: " . $e->getMessage());

        // Return false if an exception occurred
        return false;
    }
}
