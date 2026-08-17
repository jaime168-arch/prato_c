<?php
require_once 'conexao.php';

$id = $_GET['id'] ?? null;

if ($id){
    $stmt = $pdo->prepare("DELETE FROM pratos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

header("Location: index.php");
exit;
?>