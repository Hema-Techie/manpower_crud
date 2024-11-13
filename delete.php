<?php
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "DELETE FROM manpower WHERE manid = $1";
    $result = pg_query_params($dbconn, $query, [$id]);

    echo $result ? "Deleted successfully" : "Error: " . pg_last_error($dbconn);
}
?>
