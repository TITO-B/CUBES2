<?php
include("db_connect.php");
getProducts();

function getProducts()
{
	global $db_b;
	$sql = $db_b->prepare
	('SELECT temp,hum,heure FROM data ORDER BY id desc LIMIT 20 ');
	$sql->execute();
	$data = $sql->fetchAll(PDO::FETCH_ASSOC);

	header('Content-Type: application/json');
	echo json_encode($data, JSON_PRETTY_PRINT);
}

include("close_pdo.php");
?>












