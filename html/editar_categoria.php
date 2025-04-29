<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

$cat_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$cat_id) {
    die("categoria errada");
}

$stmt = $pdo->prepare("select nome from categorias where id = ?");
$stmt->execute([$cat_id]);
$cat = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cat) {
    die("nenhuma categoria encontrada");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<style>
        *{
            text-align: center;
        }
    </style>
    <meta charset="UTF-8">
    <title>editar categoria</title>
</head>
<body>
    <h2>editar categoria</h2>
    <form action="update_categoria.php" method="post">
        <input type="hidden" name="id" value="<?php echo $cat_id; ?>">
        <label>Nome</label><br>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($cat['nome']); ?>" required><br><br>
        <button type="submit">salvar</button>
    </form>
    <p><a href="lista_categorias.php">cancelar</a></p>
</body>
</html>