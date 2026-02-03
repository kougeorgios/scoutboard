<?php
$mysqli = new mysqli("localhost", "root", "root", "analytics");

if ($mysqli->connect_errno) {
    die("Failed: " . $mysqli->connect_error);
}

$res = $mysqli->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    echo $row[0] . "<br>";
}
?>
