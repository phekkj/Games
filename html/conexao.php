<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = '127.0.0.1';   
$port = '8';            
$db   = 'games';
$user = 'root';
$pass = '';             

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}