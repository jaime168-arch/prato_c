<?php
$host = "localhost";
$usuario = "root";
$senha = "root";
$banco = "restaurante_icaro";

$conn = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>