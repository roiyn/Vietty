
<?php
// tutorial credits: Dave Hollingworth on YouTube 

$formUsername = $_POST['username'];
$formPassword = $_POST['password'];
$year = $_POST['year'];

$host = "127.0.0.1";
$dbname = "vietty_db";
$dbUsername = "root";
$dbPassword = "";

$conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbname);


if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}


$sql = "INSERT INTO users (username, password, year) 
        VALUES (? , ? , ?)";

// to create a prepared statement object
$stmt = mysqli_stmt_init($conn); 

if ( ! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_error($conn));
}

// blind 
mysqli_stmt_bind_param($stmt, "sss", $formUsername, $formPassword, $year);

mysqli_stmt_execute($stmt);

echo "User created successfully!";




?>