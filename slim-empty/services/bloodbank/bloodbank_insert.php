<?php
function bloodbank_insert($p){

    $cf_m = $p['cf']['mt']['cf'];
    $cf_s = $p['cf']['mt']['services'];

    $rep = array(
        "s"=>false,
        "c"=>"",
        "v"=>array()
    );

    $conn_db=new PDOConnect('db');
    $con_db=$conn_db->Open();

    $Field1 = "";

    $sql = "INSERT INTO `TEST` (`Field1`) VALUES (:Field1)";
    $stmt = $con_db->prepare($sql);
    $stmt->bindValue(":Field1", $Field1, PDO::PARAM_STR);
    $stmt->execute();


    return $rep;
}
?>