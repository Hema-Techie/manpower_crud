<?php
// Database connection file
$dbconn = pg_connect("host=localhost dbname=manpower user=postgres password=Admin");
if (!$dbconn) {
    die("Database connection error: " . pg_last_error());
}
?>
