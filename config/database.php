<?php
date_default_timezone_set('Asia/Kolkata');

$host = "localhost";
$user = "root";
$password = "";
$database = "Intruder_safety";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Connection Failed");
}