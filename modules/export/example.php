<?php 
include_once ( '../../init.php' );
include_once 'Export.php';
global $db;

$sql = "SELECT *
		FROM app_participation 
		WHERE 1";
$exporter = new Export($db);
$exporter->query_to_csv($sql);
?>