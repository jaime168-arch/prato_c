<?php
require_once 'conexao.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_usuario'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if (!empty($nome) && !empty($email)) {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (:nome, :email)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_prato'])) {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = trim($_POST['preco']);
    $categoria = trim($_POST['categoria']);
    $usuario_id = trim($_POST['usuario_id']);

    if (!empty($nome) && !empty($descricao) && !empty($preco) && !empty($categoria) && !empty($usuario_id)) {
        $stmt = $pdo->prepare("INSERT INTO pratos (nome, descricao, preco, categoria, usuario_id) VALUES (:nome, :descricao, :preco, :categoria, :usuario_id)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }
}


$stmt_users = $pdo->query("SELECT * FROM usuarios ORDER BY nome ASC");
$usuarios = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

$usuario_filtro = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : '';

if (!empty($usuario_filtro)) {
    $stmt_pratos = $pdo->prepare("
        SELECT pratos.*, usuarios.nome AS autor 
        FROM pratos 
        JOIN usuarios ON pratos.usuario_id = usuarios.id 
        WHERE pratos.usuario_id = :usuario_id 
        ORDER BY pratos.id DESC
    ");
    $stmt_pratos->bindParam(':usuario_id', $usuario_filtro);
    $stmt_pratos->execute();
} else {
    $stmt_pratos = $pdo->query("
        SELECT pratos.*, usuarios.nome AS autor 
        FROM pratos 
        JOIN usuarios ON pratos.usuario_id = usuarios.id 
        ORDER BY pratos.id DESC
    ");
}
$pratos = $stmt_pratos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão do Restaurante</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="main-header">
        <h1>🍽️ Sistema de Gestão do Restaurante</h1>
        <p>Controle centralizado de colaboradores e cardápio</p>
    </header>

    <main>

        <section class="container">
            
      
            <div class="card">
                <div class="card-header">
                    <h2>👤 Cadastrar Colaborador</h2>
                </div>
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="nome_usuario">Nome Completo</label>
                        <input type="text" id="nome_usuario" name="nome" placeholder="Ex: Maria Silva" required>
                    </div>
                    <div class="form-group">
                        <label for="email_usuario">E-mail Profissional</label>
                        <input type="email" id="email_usuario" name="email" placeholder="exemplo@restaurante.com" required>
                    </div>
                    <button type="submit" name="cadastrar_usuario" class="btn-primary">
                        <span>+</span> Cadastrar Colaborador
                    </button>
                </form>
            </div>

  
            <div class="card">
                <div class="card-header">
                    <h2>🍲 Cadastrar Novo Prato</h2>
                </div>
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="nome_prato">Nome do Prato</label>
                        <input type="text" id="nome_prato" name="nome" placeholder="Ex: Risoto de Cogumelos" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao_prato">Descrição</label>
                        <textarea id="descricao_prato" name="descricao" placeholder="Ingredientes e detalhes do prato..." required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="preco_prato">Preço (R$)</label>
                            <input type="number" step="0.01" id="preco_prato" name="preco" placeholder="45.90" required>
                        </div>
                        <div class="form-group">
                            <label for="categoria_prato">Categoria</label>
                            <input type="text" id="categoria_prato" name="categoria" placeholder="Ex: Prato Principal" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="responsavel_prato">Responsável pelo Cadastro</label>
                        <select id="responsavel_prato" name="usuario_id" required>
                            <option value="">Selecione um colaborador...</option>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" name="cadastrar_prato" class="btn-primary">
                        <span>+</span> Cadastrar Prato
                    </button>
                </form>
            </div>

        </section>

        <section class="filtro-box">
            <form action="index.php" method="GET" class="filtro-form">
                <label for="filtro_user">
                    🔍 <strong>Filtrar Cardápio por Responsável:</strong>
                </label>
                <div class="filtro-controls">
                    <select name="usuario_id" id="filtro_user" onchange="this.form.submit()">
                        <option value="">Todos os Colaboradores</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= $usuario_filtro == $u['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($usuario_filtro)): ?>
                        <a href="index.php" class="btn-limpar">Limpar Filtro ✕</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        
        <section class="listagem">
            <h2>📋 Pratos Cadastrados</h2>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Prato</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Categoria</th>
                            <th>Responsável</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($pratos) > 0): ?>
                            <?php foreach ($pratos as $p): ?>
                                <tr>
                                    <td class="font-bold"><?= htmlspecialchars($p['nome']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($p['descricao']) ?></td>
                                    <td class="preco-tag">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                                    <td><span class="badge"><?= htmlspecialchars($p['categoria']) ?></span></td>
                                    <td>
                                        <div class="autor-info">
                                            <span class="avatar-icon">👤</span>
                                            <strong><?= htmlspecialchars($p['autor']) ?></strong>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="editar_prato.php?id=<?= $p['id'] ?>" class="btn-editar" title="Editar Prato">✏️ Editar</a>
                                        <a href="excluir_prato.php?id=<?= $p['id'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este prato?')" title="Excluir Prato">🗑️ Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <p>Nenhum prato encontrado cadastrado no sistema.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>
</html>