<?php

header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', '0');

ob_start();
require_once 'dbh.php';
ob_clean();

$username = $_GET['username'] ?? ''; //?? '' means if there is no user, set to empty string.
$password = $_GET['password'] ?? ''; // fallback if password is missing
if (!$username || !$password) {
    echo json_encode(['error' => 'missing username or password']);
    exit;
}

// function to get the password for a given username from the database
function get_password(object $pdo, string $username) { //creates a function which will be called on. $pdo is an object.
    $query = "SELECT password FROM users WHERE username = :username;"; //queries the data using sql. sql keywords are pretty straightforawrd.
    $stmt = $pdo->prepare($query);//seperates the data from the query, PREVENTING SQL INJECTION 
    $stmt->bindParam(":username", $username); //binds parameters
    $stmt->execute(); // self explanatory

    $result = $stmt->fetch(PDO::FETCH_ASSOC); // fetches the result as an associative array
    return $result ? $result['password'] : false; // returns the password if found, otherwise returns false
}

// function to check if the provided password matches the stored password for the given username
function is_password_correct(object $pdo, string $username, string $password) {
    $storedPwd = get_password($pdo, $username);
    if ($storedPwd === false) {
        return false;
    }

    if (password_verify($password, $storedPwd)) {
        return true;
    }

    return $password === $storedPwd;
}
// call the is_password_correct function
$passwordCorrect = is_password_correct($pdo, $username, $password);

echo json_encode(['passwordCorrect' => $passwordCorrect]);
