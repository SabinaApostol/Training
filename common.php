<?php

session_start();

require_once 'config.php';

if (! isset($_SESSION['ids'])) {
    $_SESSION['ids'] = array();
}

function translate($data) 
{
    return $data;
}

$servername =  DATABASE['servername'];
$username =  DATABASE['username'];
$password =  DATABASE['password'];
$dbname =  DATABASE['dbname'];

$smEmail = SMEMAIL;

try {
    $conn = new PDO("mysql:host={$servername};dbname={$dbname}", $username, $password);
} catch (PDOException $e) {
    die($e->getMessage());
}