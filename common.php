<?php

function translate($data) {
    return $data;
}


$servername =  DATABASE['servername'];
$username =  DATABASE['username'];
$password =  DATABASE['password'];
$dbname =  DATABASE['dbname'];

try {
    return $conn = new PDO("mysql:host={$servername};dbname={$dbname}", $username, $password);
}
catch (PDOException $e) {
    die($e->getMessage());
}