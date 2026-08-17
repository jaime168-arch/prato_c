<?php
require_once 'conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = trim($_POST['preco']);
    $categoria = trim($_POST['categoria']);
    $usuario_id = trim($_POST['usuarios_id']);

    if(!empty($nome) && !empty($descricao) && !empty($preco) && !empty($categoria) && !empty($usuario_id)) {
        $stmt = $pdo->prepare("
           UPDATE pratos
           SET nome = :nome, descricao = :descricao, preco = :preco, categoria = :categoria, usuario_id = :usuario_id
           WHERE id = :id
        ");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header("Location: index.php");
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM pratos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$prato = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prato ){
    header('Location: index.php')  
    exit;  
}

$stmt_users = $pdo->query("SELECT * FROM usuarios ORDER BY nome ASC");
$usuarios = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>