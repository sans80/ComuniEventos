<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "salon_comunitario";

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("Error MySQL: " . $mysqli->connect_error);
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /index.php'); 
        exit;
    }
}
