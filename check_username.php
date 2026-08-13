<?php
require_once 'dbh.php'; 

$username = $_GET['username'] ?? ''; 
// if the username is empty
if (!$username) {
    echo json_encode(['error' => 'missing username']); 
    exit;
}

// check if the username exists in the database
function get_username(object $pdo, string $username) { 
    // SQL query to check if the users database has a username that matches the inputted username
    $query = "SELECT username FROM users WHERE username = :username;"; 
    // prepare the query, bind the username parameter, and execute the query
    $stmt = $pdo->prepare($query);
    // bind the username parameter to the prepared statement
    $stmt->bindParam(":username", $username); 
    // execute the prepared statement
    $stmt->execute(); 

    // fetch the result of the query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
// call the get_username function
$usernameResult = get_username($pdo, $username);

// tell the javascript if the username is taken or not
echo json_encode([
    'usernameTaken' => $usernameResult !== false
]);
