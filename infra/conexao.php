<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'restaurante_icaro';
$usuario = 'root';
$senha = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão realizada com sucesso!";
} catch (PDOException $e) {
    die("<h1>Erro de Conexão com o Banco:</h1> " . $e->getMessage());
}
?>