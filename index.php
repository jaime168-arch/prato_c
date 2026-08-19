<?php
require_once __DIR__ . '/infra/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_usuario'])) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($nome) && !empty($email)) {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (:nome, :email)");
        $stmt->execute([':nome' => $nome, ':email' => $email]);
        header("Location: index.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_prato'])) {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = trim($_POST['preco'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $usuario_id = trim($_POST['usuario_id'] ?? '');

    if (!empty($nome) && !empty($descricao) && !empty($preco) && !empty($categoria) && !empty($usuario_id)) {
        $stmt = $pdo->prepare("INSERT INTO pratos (nome, descricao, preco, categoria, usuario_id) VALUES (:nome, :descricao, :preco, :categoria, :usuario_id)");
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':preco' => $preco,
            ':categoria' => $categoria,
            ':usuario_id' => $usuario_id
        ]);
        header("Location: index.php");
        exit;
    }
}

$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

$usuario_filtro = $_GET['usuario_id'] ?? '';
if (!empty($usuario_filtro)) {
    $stmt_pratos = $pdo->prepare("SELECT pratos.*, usuarios.nome AS autor FROM pratos JOIN usuarios ON pratos.usuario_id = usuarios.id WHERE pratos.usuario_id = :usuario_id ORDER BY pratos.id DESC");
    $stmt_pratos->execute([':usuario_id' => $usuario_filtro]);
} else {
    $stmt_pratos = $pdo->query("SELECT pratos.*, usuarios.nome AS autor FROM pratos JOIN usuarios ON pratos.usuario_id = usuarios.id ORDER BY pratos.id DESC");
}
$pratos = $stmt_pratos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão do Restaurante</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <h1>Sistema de Gestão do Restaurante</h1>

    <h2>Cadastrar Colaborador</h2>
    <form action="index.php" method="POST">
        <input type="text" name="nome" placeholder="Nome Completo" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <button type="submit" name="cadastrar_usuario">Cadastrar Colaborador</button>
    </form>

    <h2>Cadastrar Novo Prato</h2>
    <form action="index.php" method="POST">
        <input type="text" name="nome" placeholder="Nome do Prato" required>
        <textarea name="descricao" placeholder="Descrição" required></textarea>
        <input type="number" step="0.01" name="preco" placeholder="Preço" required>
        <input type="text" name="categoria" placeholder="Categoria" required>
        <select name="usuario_id" required>
            <option value="">Selecione o Responsável...</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="cadastrar_prato">Cadastrar Prato</button>
    </form>

    <h2>Pratos Cadastrados</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Prato</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Responsável</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pratos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= htmlspecialchars($p['descricao']) ?></td>
                    <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($p['categoria']) ?></td>
                    <td><?= htmlspecialchars($p['autor']) ?></td>
                    <td>
                        <a href="public/editar_prato.php?id=<?= $p['id'] ?>">Editar</a>
                        <a href="public/excluir_prato.php?id=<?= $p['id'] ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>