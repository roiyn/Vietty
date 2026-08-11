<?php

require_once 'dbh.php'; 

$username = $_GET['username'] ?? ''; 
if (!$username) {
    echo json_encode(['error' => 'missing username']);
    exit;
}

function get_username(object $pdo, string $username) { 
    $query = "SELECT username FROM users WHERE username = :username;"; 
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username); 
    $stmt->execute(); 

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

$usernameResult = get_username($pdo, $username);

echo json_encode([
    'usernameTaken' => $usernameResult !== false
]);
