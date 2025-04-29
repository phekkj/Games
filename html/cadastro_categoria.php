<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (!$nome) {
    die("nome da categoria");
}

try {

    $stmt = $pdo->prepare("INSERT INTO categorias (nome) VALUES (?)");
    $stmt->execute([$nome]);

    echo "categoria cadastrada";
    echo "<p><a href='lista_categorias.php'>Ver lista de categorias</a></p>";
} catch (PDOException $e) {
    die("erro ao cadastrar categoria: " . $e->getMessage());
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadastro de categoria</title>
</head>
<body>

<h1>cadastro de categoria</h1>

<form action="cadastro_categoria.php" method="POST">
    <label for="nome">nome da categoria:</label><br>
    <input type="text" id="nome" name="nome" required><br><br>
    <input type="submit" value="Cadastrar Categoria">
</form>

</body>
</html>