<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $username = $_POST['username'];
    $password = $_POST['password'];
    $year = $_POST['year'];

    if ($username === '' || $password === '' || $year === '') {
    header('Location: signup.html');
    die();
    }

    try {
        require_once 'dbh.php';

        $query = "INSERT INTO users (username, password, year)
                VALUES (?, ?, ?)";

        $stmt = $pdo->prepare($query);

        $stmt->execute([$username, $password, $year]);

        $pdo = null;
        $stmt = null;

        header('Location: index.html');
        die();

} catch (PDOException $e) {

     die("something went wrong. here's what:" . $e );
}

}else { 
    header('Location: signup.html');
}