<?php

require_once 'config_session.php';


$_SESSION = [];

// destroy the session (logs the user out)
session_destroy();

// go back to home page, logged out
header('Location: index.php');
die();