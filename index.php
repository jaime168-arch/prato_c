<?php
// Exibe os erros diretamente na tela para facilitar o diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão via PDO
require_once 'infra/conexao.php';

// Processamento do Cadastro de Usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_usuario'])) {
    $nome = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($nome) && !empty($email)) {
        try {
            $sql = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                // Redireciona para a tela de cadastrar pratos conforme a navegação original
                header("Location: public/tela_pratos.php");
                exit();
            }
        } catch (PDOException $e) {
            die("Erro ao cadastrar usuário: " . $e->getMessage());
        }
    } else {
        $erro_msg = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Gestão do Restaurante</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>

    <header class="main-header">
        <h1>Sistema de Gestão do Restaurante</h1>
        <p>Controle centralizado de colaboradores e cardápio</p>
    </header>

    <main>
        <section class="container">
            <div class="card">
                <div class="card-header">
                    <h2>Cadastrar Usuário</h2>
                </div>

                <?php if (isset($erro_msg)): ?>
                    <p style="color: red; padding: 10px;"><?= $erro_msg ?></p>
                <?php endif; ?>

                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="usuario">Usuário:</label>
                        <input type="text" name="usuario" id="usuario" placeholder="Ex: Maria Silva" required>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" name="email" id="email" placeholder="exemplo@restaurante.com" required>
                    </div>

                    <button type="submit" name="cadastrar_usuario" class="btn-primary">
                        Cadastrar
                    </button>
                </form>
            </div>
        </section>
    </main>

    <footer>
    </footer>

</body>

</html>