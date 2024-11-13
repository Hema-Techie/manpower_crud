<?php
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM manpower WHERE manid = $1";
    $result = pg_query_params($dbconn, $query, [$id]);
    $data = pg_fetch_assoc($result);

    echo json_encode($data ?: ["error" => "Record not found"]);
}
?>
