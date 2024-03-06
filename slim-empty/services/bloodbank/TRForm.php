<?php
function TRForm($p)
{

    $cf_m = $p['cf']['mt']['cf'];
    $cf_s = $p['cf']['mt']['services'];

    // Extracting id from input parameters
    $idTR_Form = $p['idTR_Form'];


    $rep = new stdClass();

    $conn_db = new PDOConnect('db');
    $con_db = $conn_db->Open();

    // Selecting the database
    $con_db->exec("USE blood_request");

    // Using a prepared statement to prevent SQL injection
    $sql = "SELECT 
    IFNULL (
        (
            SELECT JSON_OBJECT(
                'idBloodTransfusion_Test', BloodTransfusionTest.idBloodTransfusion_Test,
                'isCorrectPatientName', BloodTransfusionTest.isCorrectPatientName,
                'isWithin24hrsFever', BloodTransfusionTest.isWithin24hrsFever,
                'isCorrectBloodComponent', BloodTransfusionTest.isCorrectBloodComponent,
                'isCorrectBloodTransfusionRec', BloodTransfusionTest.isCorrectBloodTransfusionRec,
                'isCorrectBloodBagNumber', BloodTransfusionTest.isCorrectBloodBagNumber,
                'isCorrectBloodGroupDonor', BloodTransfusionTest.isCorrectBloodGroupDonor,
                'isCorrectBloodGroupPatient', BloodTransfusionTest.isCorrectBloodGroupPatient
            )
            FROM BloodTransfusionTest
            WHERE BloodTransfusionTest.idTR_Form = TR_Form.idTR_Form
        ), 
        JSON_OBJECT()
    ) AS BloodTransfusionTest,
    IFNULL (
        (
            SELECT JSON_OBJECT(
                'idVitalSigns',VitalSigns.idVitalSigns,
                'beforeReactionTime',VitalSigns.beforeReactionTime,
                'beforeReactionTemp',VitalSigns.beforeReactionTemp,
                'beforeReactionBP',VitalSigns.beforeReactionBP,
                'beforeReactionPulse',VitalSigns.beforeReactionPulse,
                'afterReactionTime',VitalSigns.afterReactionTime,
                'afterReactionTemp',VitalSigns.afterReactionTemp,
                'afterReactionBP',VitalSigns.afterReactionBP,
                'afterReactionPulse',VitalSigns.afterReactionPulse
            )
            FROM VitalSigns
            WHERE VitalSigns.idTR_Form = TR_Form.idTR_Form
        ), 
        JSON_OBJECT()
    ) AS VitalSigns,
    IFNULL (
        (
            SELECT 
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'idDetailRecordIn24Hrs',DetailRecordIn24Hrs.idDetailRecordIn24Hrs,
                        'bloodBagNumber',DetailRecordIn24Hrs.bloodBagNumber,
                        'bloodComponent',DetailRecordIn24Hrs.bloodComponent,
                        'bloodGroup',DetailRecordIn24Hrs.bloodGroup,
                        'bloodRH',DetailRecordIn24Hrs.bloodRH,
                        'startTransfusion',DetailRecordIn24Hrs.startTransfusion,
                        'endTransfusion',DetailRecordIn24Hrs.endTransfusion,
                        'volume',DetailRecordIn24Hrs.volume,
                        'isReaction',DetailRecordIn24Hrs.isReaction
                    )
                )
            FROM DetailRecordIn24Hrs
            WHERE DetailRecordIn24Hrs.idTR_Form = TR_Form.idTR_Form
        ), JSON_ARRAY()) 
    AS DetailRecordIn24Hrs,
    IFNULL (
        (
            SELECT JSON_OBJECT(
                'idSignsAndSymptomsName', JSON_ARRAYAGG(SignsAndSymptoms.idSignsAndSymptomsName),
                'Other', (
                    SELECT SignsAndSymptoms.Other
                    FROM SignsAndSymptoms
                    WHERE SignsAndSymptoms.idTR_Form = TR_Form.idTR_Form AND SignsAndSymptoms.Other IS NOT NULL
                )
            )
            FROM SignsAndSymptoms
            WHERE SignsAndSymptoms.idTR_Form = TR_Form.idTR_Form
        ), JSON_OBJECT('idSignsAndSymptomsName', JSON_ARRAY(), 'Other', NULL)) 
    AS SignsAndSymptomsObject,
    IFNULL (
        (
            SELECT JSON_OBJECT(
                'idSubmittingTest',SubmittingTest.idSubmittingTest,
                'isBloodSample',SubmittingTest.isBloodSample,
                'isBloodBagReaction',SubmittingTest.isBloodBagReaction,
                'nurseName',SubmittingTest.nurseName,
                'nurseDateTime',SubmittingTest.nurseDateTime,
                'physicianName',SubmittingTest.physicianName,
                'physicianDateTime',SubmittingTest.physicianDateTime
            )
            FROM SubmittingTest
            WHERE SubmittingTest.idTR_Form = TR_Form.idTR_Form
        ), JSON_OBJECT()
    ) AS SubmittingTest,
    JSON_OBJECT(
        'idTR_Form', TR_Form.idTR_Form,
        'title', TR_Form.title,
        'firstName', TR_Form.firstName,
        'lastName', TR_Form.lastName,
        'HN', TR_Form.HN,
        'TXN', TR_Form.TXN,
        'pt_type', TR_Form.pt_type,
        'createdDate', TR_Form.createdDate,
        'ward_code', TR_Form.ward_code,
        'ward', TR_Form.ward,
        'phoneNumber', TR_Form.phoneNumber,
        'diagnosis', TR_Form.diagnosis,
        'primaryPhysicianName', TR_Form.primaryPhysicianName,
        'bloodGroup_Patient', TR_Form.bloodGroup_Patient,
        'Rh_Patient', TR_Form.Rh_Patient,
        'blood_component', TR_Form.blood_component,
        'bloodGroup_Donor', TR_Form.bloodGroup_Donor,
        'Rh_Donor', TR_Form.Rh_Donor,
        'bloodBagNumber', TR_Form.bloodBagNumber,
        'volume', TR_Form.volume,
        'medicationHistory', TR_Form.medicationHistory,
        'isReactionHistory', TR_Form.isReactionHistory,
        'reactionCategory', TR_Form.reactionCategory,
        'status', TR_Form.status
    ) AS PatientInfo
FROM TR_Form
WHERE TR_Form.idTR_Form = :idTR_Form;";

    $stmt = $con_db->prepare($sql);
    $stmt->bindValue(":idTR_Form", $idTR_Form, PDO::PARAM_INT); // Assuming id is an integer, adjust if it's a string
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result[0]['PatientInfo'] = json_decode($result[0]['PatientInfo'], true);
    $result[0]['BloodTransfusionTest'] = json_decode($result[0]['BloodTransfusionTest'], true);
    $result[0]['VitalSigns'] = json_decode($result[0]['VitalSigns'], true);
    $result[0]['DetailRecordIn24Hrs'] = json_decode($result[0]['DetailRecordIn24Hrs'], true);
    $result[0]['SignsAndSymptomsObject'] = json_decode($result[0]['SignsAndSymptomsObject'], true);
    $result[0]['SubmittingTest'] = json_decode($result[0]['SubmittingTest'], true);


    return $result[0];
}
