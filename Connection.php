<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bukutamu"; 
$conn = "";


// Create connection
try {
    $conn = mysqli_connect($servername, $username, $password, $dbname,8111);
} catch (mysqli_sql_exception $e) {
    echo "Could not connect! Error: " . $e->getMessage();
    exit;
}

// Check connection
if ($conn) {
    echo "";
}
// $conn = new database();
?>