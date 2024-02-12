<?php
function ReactionCategory($p){

    $cf_m = $p['cf']['mt']['cf'];
    $cf_s = $p['cf']['mt']['services'];

    $rep = new stdClass();

    $conn_db=new PDOConnect('db');
    $con_db=$conn_db->Open();

    /* $sql = "SELECT * FROM `TEST` WHERE `Field1` = :Field1 "; */
    $sql = "SELECT * FROM blood_request.ReactionCategory";
    $stmt = $con_db->prepare($sql);
    /* $stmt->bindValue(":Field1", $Field1, PDO::PARAM_STR); */
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rep = $result;

    return $rep;
}
?>