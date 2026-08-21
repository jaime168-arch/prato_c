<?php
require_once 'infra/conexao.php';

// Processa Cadastro de Usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_usuario'])) {
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome'] ?? ''));
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));

    if (!empty($nome) && !empty($email)) {
        $sql = "INSERT INTO usuarios (nome, email) VALUES ('$nome', '$email')";
        mysqli_query($conn, $sql);
        header("Location: index.php");
        exit;
    }
}

// Processa Cadastro de Prato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_prato'])) {
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome'] ?? ''));
    $descricao = mysqli_real_escape_string($conn, trim($_POST['descricao'] ?? ''));
    $preco = mysqli_real_escape_string($conn, trim($_POST['preco'] ?? ''));
    $categoria = mysqli_real_escape_string($conn, trim($_POST['categoria'] ?? ''));
    $usuario_id = mysqli_real_escape_string($conn, trim($_POST['usuario_id'] ?? ''));

    if (!empty($nome) && !empty($descricao) && !empty($preco) && !empty($categoria) && !empty($usuario_id)) {
        $sql = "INSERT INTO pratos (nome, descricao, preco, categoria, usuario_id) 
                VALUES ('$nome', '$descricao', '$preco', '$categoria', '$usuario_id')";
        mysqli_query($conn, $sql);
        header("Location: index.php");
        exit;
    }
}

// Busca Usuários
$res_usuarios = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY nome ASC");

// Filtro e Busca de Pratos
$usuario_filtro = $_GET['usuario_id'] ?? '';
if (!empty($usuario_filtro)) {
    $usuario_filtro = mysqli_real_escape_string($conn, $usuario_filtro);
    $sql_pratos = "SELECT pratos.*, usuarios.nome AS autor 
                   FROM pratos 
                   JOIN usuarios ON pratos.usuario_id = usuarios.id 
                   WHERE pratos.usuario_id = '$usuario_filtro' 
                   ORDER BY pratos.id DESC";
} else {
    $sql_pratos = "SELECT pratos.*, usuarios.nome AS autor 
                   FROM pratos 
                   JOIN usuarios ON pratos.usuario_id = usuarios.id 
                   ORDER BY pratos.id DESC";
}
$res_pratos = mysqli_query($conn, $sql_pratos);
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
            <?php while ($u = mysqli_fetch_assoc($res_usuarios)): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
            <?php endwhile; ?>
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
            <?php if (mysqli_num_rows($res_pratos) > 0): ?>
                <?php while ($p = mysqli_fetch_assoc($res_pratos)): ?>
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
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum prato cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>