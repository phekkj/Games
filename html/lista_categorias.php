<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';
$cats = $pdo->query("SELECT id, nome FROM categorias")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<style>
    </style>
    <meta charset="UTF-8">
    <title>lista de categorias</title>
</head>
<body>
    <h2>categorias cadastradas</h2>
    <table border="1">
        <tr><th>nome</th><th>acoes</th></tr>
        <?php foreach ($cats as $cat) : ?>
        <tr>
            <td><?php echo htmlspecialchars($cat['nome']); ?></td>
            <td>
                <a href="editar_categoria.php?id=<?php echo $cat['id']; ?>">editar</a> |
                <a href="excluir_categoria.php?id=<?php echo $cat['id']; ?>"
                   onclick="return confirm('Excluir esta categoria?');">excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="loja.php">voltar</a></p>
</body>
</html>