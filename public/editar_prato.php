<?php

include '../infra/connect.php';
if (!isset($conn) || $conn === null) {
    die('Erro ao conectar com o banco de dados.');
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT * FROM pratos WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$resultadoPrato = mysqli_stmt_get_result($stmt);
$prato = mysqli_fetch_assoc($resultadoPrato);

if (!$prato) {
    die('Prato não encontrado.');
}

$sql = "SELECT * FROM usuarios";
$resultado = mysqli_query($conn, $sql);

if ($resultado === false) {
    die('Erro ao consultar usuários: ' . mysqli_error($conn));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = $_POST['preco'];
    $categoria = trim($_POST['categoria']);
    $usuario_id = (int) $_POST['usuario'];

    $sql = "UPDATE pratos SET nome = ?, descricao = ?, preco = ?, categoria = ?, id_usuario = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssdsi', $nome, $descricao, $preco, $categoria, $usuario_id, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Prato atualizado com sucesso!";
        echo "<br><a href='../index.php'>Voltar</a>";
        exit();
    } else {
        echo "Erro ao atualizar prato: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Prato</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <form method="POST">

        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($prato['nome']); ?>" required>
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" id="descricao" value="<?php echo htmlspecialchars($prato['descricao']); ?>" required>
        <label for="preco">Preço:</label>
        <input type="number" name="preco" id="preco" value="<?php echo htmlspecialchars($prato['preco']); ?>" step="0.01" required>
        <label for="categoria">Categoria:</label>
        <input type="text" name="categoria" id="categoria" value="<?php echo htmlspecialchars($prato['categoria']); ?>" required>
        <label for="usuario">Usuário:</label>
        <select name="usuario" id="usuario" required>
            <option value="">Selecione um usuário</option>
            <?php
            while ($row = mysqli_fetch_assoc($resultado)) {
                $selected = ($row['id'] == $prato['id_usuario']) ? 'selected' : '';
                echo "<option value='{$row['id']}' {$selected}>{$row['nome']}</option>";
            }
            ?>
        </select>
        <button type="submit">Atualizar Prato</button>
    </form>
    <button type="button" onclick="window.location.href='../index.php'">Voltar</button>

</body>

</html>