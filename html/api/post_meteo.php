<?php
include("db_connect.php");
AddProduct();
function AddProduct()
{
    global $db_b;
    $temp = $_POST["temp"];
    $hum = $_POST["hum"];
    $id_capteur = $_POST["id_capteur"];


    $sql = $db_b->prepare("INSERT INTO data (id_capteur,temp, hum)VALUES (:id_capteur,:temp,:hum) ");
    $sql->execute(["id_capteur" => $id_capteur, "temp" => $temp,"hum" => $hum]);

}

include("close_pdo.php");
?>


























