<?php
function TRReport_Update($p)
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
    $id = $p['id'];
    $idTR_Report = $id['id'];

    // Extract individual sections of the form data
    $data = $formData['data'];
    $BloodBagCharacteristic = $formData['BloodBagCharacteristic'];
    $indicators = $formData['indicator'];
    $GramStainAndCulture = $formData['GramStainAndCulture'];
    echo '<pre>';
    print_r($idTR_Report);
    echo '</pre>'; 
    $con_db->beginTransaction();
    $status = 0;

    //TR_Report data
    if ($data !== null) {
        $sqldata = "UPDATE blood_request.TR_Report SET
        interpretation = :interpretation, testedBy = :testedBy, testedDateTime = :testedDateTime, 
        reportedBy = :reportedBy, reportedDateTime = :reportedDateTime, status = 0
        WHERE idTR_Report = :idTR_Report";

        $stmtdata = $con_db->prepare($sqldata);
        $stmtdata->bindValue(":idTR_Report", $idTR_Report, PDO::PARAM_INT);

        foreach ($data as $key => $value) {
            if (
                $key === 'reportedDateTime' || $key === 'testedDateTime' || $key === 'interpretation'
                || $key === 'testedBy' || $key === 'reportedBy'
            ) {
                if ($value === "" || $value === null) {
                    $stmtdata->bindValue(":$key", null, PDO::PARAM_NULL);
                } else if ($key === 'reportedDateTime' || $key === 'testedDateTime') {
                    $formattedDate = (DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $value))->setTimezone(new DateTimeZone('+07:00'))->format('Y-m-d H:i:s');
                    $stmtdata->bindValue(":$key", $formattedDate, PDO::PARAM_STR);
                } else {
                    $stmtdata->bindValue(":$key", $value, PDO::PARAM_STR);
                }
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
            if (updateTable($con_db, $tableNameBloodBagCharacteristic, $BloodBagCharacteristic, $idTR_Report)) {
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
                var_dump($indicator);
                if (updateTable($con_db, $tableNameindicator, $indicator, $idTR_Report)) {
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
            if (updateTable($con_db, $tableNameGramStainAndCulture, $GramStainAndCulture, $idTR_Report)) {
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

    //update status
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
function updateTable($con_db, $tableName, $data, $idTR_Report)
{
    try {
        // Check if the row already exists in the table
        $checkSql = "SELECT COUNT(*) FROM $tableName WHERE idTR_Report = :idTR_Report";

        // Add condition for blood_request.Indicator table
        if ($tableName === 'blood_request.Indicator' && isset($data['idIndicatorName'])) {
            $checkSql .= " AND idIndicatorName = :idIndicatorName";
        }

        $checkStmt = $con_db->prepare($checkSql);
        $checkStmt->bindValue(":idTR_Report", $idTR_Report, PDO::PARAM_INT);

        // Bind the idIndicatorName if applicable
        if ($tableName === 'blood_request.Indicator' && isset($data['idIndicatorName'])) {
            $checkStmt->bindValue(":idIndicatorName", $data['idIndicatorName'], PDO::PARAM_INT);
        }

        $checkStmt->execute();
        $rowExists = ($checkStmt->fetchColumn() > 0);

        // Prepare the SQL statement
        if ($rowExists) {
            // Row exists, update it
            $sql = "UPDATE $tableName SET ";
            $setValues = [];
            foreach ($data as $key => $value) {
                $setValues[] = "$key = :$key";
            }
            $sql .= implode(', ', $setValues);
            $sql .= " WHERE idTR_Report = :idTR_Report";

            // Add condition for blood_request.Indicator table
            if ($tableName === 'blood_request.Indicator' && isset($data['idIndicatorName'])) {
                $sql .= " AND idIndicatorName = :idIndicatorName";
            }
        } else {
            // Row does not exist, insert a new row
            $sql = "INSERT INTO $tableName (";
            $sql .= implode(', ', array_keys($data)); // Column names
            $sql .= ", idTR_Report) VALUES (";
            $sql .= implode(', ', array_map(function ($item) {
                return ':' . $item;
            }, array_keys($data))); // Parameter placeholders
            $sql .= ", :idTR_Report)";
        }

        // Execute the statement
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
        $stmt->bindValue(":idTR_Report", $idTR_Report, PDO::PARAM_INT);

        // Bind the idIndicatorName if applicable
        if ($tableName === 'blood_request.Indicator' && isset($data['idIndicatorName'])) {
            $stmt->bindValue(":idIndicatorName", $data['idIndicatorName'], PDO::PARAM_INT);
        }

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        error_log("Error updating/inserting data into table $tableName: " . $e->getMessage());

        // Return false if an exception occurred
        return false;
    }
}


?>