<?php

require_once 'config_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $password = $_POST['password'];

    try {
        require_once 'dbh.php'; // loads the DataBase Handler file

        // Lookup the user by username and get their id, stored password, and year
        $query = 'SELECT id, password, year FROM users WHERE username = ? LIMIT 1;';
        $stmt = $pdo->prepare($query); // prepare the query to prevent SQL injection
        $stmt->execute([$user]); // execute the prepared statement with the username as a parameter
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC); // fetch the result as an associative array

        // if the user is not found, redirect to login.html
        if (!$userRow) {
            header('Location: login.html');
            die();
        }

        // successful login — store user id, username, and year in session
        $userId = $userRow['id'];
        $_SESSION["user_id"] = $userId;
        $_SESSION["username"] = $user;
        $_SESSION["year"] = $userRow['year'];


        // clean up
        $pdo = null; //closes off the connection, sets everything to nothing
        $stmt = null;

        header('Location: index.php');

        die();
    } catch (PDOException $e) {
        die("crumbs:(( something went wrong... here's what: ". $e);
    }
}else {
    header('Location: login.html');
}