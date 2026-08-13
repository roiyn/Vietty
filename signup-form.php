<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $username = $_POST['username'];
    $password = $_POST['password'];
    $year = $_POST['year'];

    // check if the username, password, or year is empty, if so redirect to signup.html
    if ($username === '' || $password === '' || $year === '') {
    header('Location: signup.html');
    die();
    }

    try {
        require_once 'dbh.php';
        require_once 'config_session.php';


        // prepare the SQL query to insert the new user into the database
        $query = "INSERT INTO users (username, password, year)
                VALUES (?, ?, ?)";

        $stmt = $pdo->prepare($query); // prepare the query to prevent SQL injection

        $stmt->execute([$username, $password, $year]); // execute the prepared statement with the username, password, and year as parameters

        // get the id of the newly created user
        $userId = $pdo->lastInsertId();

        // store the user id, username, and year in the session
        $_SESSION["user_id"] = $userId;
        $_SESSION["username"] = $username;
        $_SESSION["year"] = $year;

        // cleanup the database connection and statement
        $pdo = null;
        $stmt = null;

        // redirect to index.php after the user is created
        header('Location: index.php');
        die();

} catch (PDOException $e) {

     die("something went wrong:" . $e );
}

}else { 
    header('Location: signup.html'); // redirect to signup.html if the request method is not POST
}