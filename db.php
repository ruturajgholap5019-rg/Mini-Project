<?php
// db.php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'examportal';

$conn = new mysqli($host, $user, $pass, $dbname);

if (!$conn) {
    echo "Database Connection Failed";
}

?>