<?php
// MySQLi connection (professor's sample style)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventorysys";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Also expose DBModel instance for higher-level operations
require_once __DIR__ . '/db_model.php'; // creates $db
