<?php

include 'infra/connect.php';
$sql = "SELECT * FROM pratos";
$resultado = mysqli_query($conn, $sql);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario'] ?? null;

    if ($usuario_id) {
        $sql = "SELECT * FROM pratos WHERE id_usuario = $usuario_id";
        $resultado = mysqli_query($conn, $sql);
    } else {
        $sql = "SELECT * FROM pratos";
        $resultado = mysqli_query($conn, $sql);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Pratos</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <main>
        <h1>Gerenciador de Pratos</h1>
        <a href="public/cad_prato.php"> Novo Prato</a>
        <a href="public/cad_user.php"> Novo Usuário</a>
        <br>
        <br>
        <form method="POST">
            <label for="usuario">Filtro por Usuário</label>
            <select id="usuario" name="usuario">
                <option value="">Todos</option>
                <?php
                $sqlUsuarios = "SELECT * FROM usuarios";
                $resultadoUsuarios = mysqli_query($conn, $sqlUsuarios);
                while ($usuario = mysqli_fetch_assoc($resultadoUsuarios)) {
                    echo "<option value='{$usuario['id']}'>{$usuario['nome']}</option>";
                }

                ?>
            </select>
            <button type="submit">Filtrar</button>
            <br>
            <br>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>ID do Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php

                    while ($prato = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$prato['nome']}</td>";
                        echo "<td>{$prato['descricao']}</td>";
                        echo "<td>{$prato['preco']}</td>";
                        echo "<td>{$prato['categoria']}</td>";
                        echo "<td>{$prato['id_usuario']}</td>";
                        echo "<td>
                                <a href='public/editar_prato.php?id={$prato['id']}'>Editar</a> |
                                <a href='public/excluir_prato.php?id={$prato['id']}'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </main>


</body>

</html>