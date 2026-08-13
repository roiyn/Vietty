<?php
    require_once 'dbh.php';
    require_once 'config_session.php';

// check if the user is logged in, if not redirect to login.php
try{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  // check if the request method is POST
        // get the user id from the session, and the title and body from the POST request
        $userid = $_SESSION['user_id']; 
        $title = $_POST['title'];
        $body = $_POST['body'];

        // check if the title or body is empty, if so redirect to index.php
        if ($title === '' || $body === '') {
        header('Location: index.php');
        die();
        }
        // prepare the SQL query to insert the new post into the database
        try {
            // SQL query to insert the new post into the database
            $query = "INSERT INTO posts (userid, title, body)
                    VALUES (?, ?, ?)";
            // prepare the query and execute it with the user id, title, and body as parameters
            $stmt = $pdo->prepare($query);
            // execute the prepared statement with the user id, title, and body as parameters
            $stmt->execute([$userid, $title, $body]);

            // cleanup the database connection and statement
            $pdo = null;
            $stmt = null;

            // redirect to index.php after the post is created
            header('Location: index.php');
            die();

        // catch any PDO exceptions and display an error message
        } catch (PDOException $e) {

            die("something went wrong:" . $e );
        }

    // if the request method is not POST, redirect to index.php    
    }else { 
        header('Location: index.php');
    }
}catch (PDOException $e) {
    die("something went wrong:" . $e );
}