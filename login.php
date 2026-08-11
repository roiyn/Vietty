<?php

// establish variables
$host = "sql104.infinityfree.com";
$dbname = "if0_42580852_vietty_db";
$dbUsername = "if0_42580852";
$dbPassword = "SJtS9JjBLCFwMC";

// connect to database
$pdo = new PDO(
    "mysql:host=$host;dbname=$dbname",
    $dbUsername,
    $dbPassword
);

// tell PDO to throw errors
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// get information from login form
$username = $_POST['username'];
$password = $_POST['password'];

// find the user
$query = "SELECT * FROM users WHERE username = ?";

$stmt = $pdo->prepare($query);
$stmt->execute([$username]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// check if username exists
if (!$user) {
    header("Location: login.html?error=usernotfound");
    die();
}

// check password
if ($password != $user['password']) {
    header("Location: login.html?error=wrongpassword");
    die();
}

// login successful
header("Location: index.html");
die();