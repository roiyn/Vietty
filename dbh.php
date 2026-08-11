<?php // no closing off this bc this is a pure php file!! meaning the whole file wil be written in php:D
//this is code for a DataBase Handler, hence the name.

$host = "sql104.infinityfree.com";
$dbname = "if0_42580852_vietty_db";
$dbUsername = "if0_42580852";
$dbPassword = "SJtS9JjBLCFwMC";


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "crumbs:( something bwoke : " . $e->getMessage();
}