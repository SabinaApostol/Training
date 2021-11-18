<?php


function translate($data) {
    return $data;
}


$servername =  DATABASE['servername'];
$username =  DATABASE['username'];
$password =  DATABASE['password'];
$dbname =  DATABASE['dbname'];

$conn = new PDO("mysql:host=$servername;dbname={$dbname}", $username, $password);

return $conn;