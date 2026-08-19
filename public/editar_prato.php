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
    $usuario_id = trim($_POST['usuario_id']);

    if (!empty($nome) && !empty($descricao) && !empty($preco) && !empty($categoria) && !empty($usuario_id)) {
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

if (!$prato) {
    header('Location: index.php');
    exit;
}

$stmt_users = $pdo->query("SELECT * FROM usuarios ORDER BY nome ASC");
$usuarios = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Prato</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="card" style="max-width: 500px; margin: 40px auto;">
        <div class="card-header">
            <h2>Editar Prato</h2>
        </div>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nome do Prato</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($prato['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea name="descricao" required><?= htmlspecialchars($prato['descricao']) ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Preço (R$)</label>
                    <input type="number" step="0.01" name="preco" value="<?= $prato['preco'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Categoria</label>
                    <input type="text" name="categoria" value="<?= htmlspecialchars($prato['categoria']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Responsável</label>
                <select name="usuario_id" required>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $prato['usuario_id'] == $u['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-primary">Salvar Alterações</button>
            <a href="index.php" style="display: block; text-align: center; margin-top: 15px; color: var(--text-muted); text-decoration: none;">Cancelar</a>
        </form>
    </div>

</body>
</html>