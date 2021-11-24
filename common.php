<?php

session_start();

require_once 'config.php';

if (! isset($_SESSION['ids'])) {
    $_SESSION['ids'] = [];
}

function prepareSelectAll($conn) 
{
    return  $conn->prepare('SELECT * FROM products');
}

function createArrayToBind ($arr) 
{
    return implode(', ', array_fill(0, count($arr), '?'));
}

function prepareAndFetchAll($conn) 
{
    $stmt = prepareSelectAll($conn);
    $stmt->execute();
    return $stmt->fetchALL(PDO::FETCH_CLASS);
}

function execAndFetch($stmt, $arr)
{
    $stmt->execute($arr);
    return $stmt->fetchALL(PDO::FETCH_CLASS);
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