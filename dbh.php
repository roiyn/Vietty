<?php 
//DataBase Handler

// database connection parameters
$host = "sql104.infinityfree.com";
$dbname = "if0_42580852_vietty_db";
$dbUsername = "if0_42580852";
$dbPassword = "SJtS9JjBLCFwMC";

try {
    // create a new PDO instance to connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
    // set the PDO error mode to exception to handle any errors that may occur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "something went wrong: " . $e->getMessage(); // for debugging
}