<?php
require_once 'db.php';

$query = "SELECT m.*, s.skillset FROM manpower m LEFT JOIN mst_skillsets s ON m.skill_code = s.sid";
$result = pg_query($dbconn, $query);
$data = pg_fetch_all($result);

echo json_encode($data ?: []);
?>
