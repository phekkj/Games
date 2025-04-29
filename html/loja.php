<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
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
    <title>Lojinha tri</title>
</head>
<body>
    <h2>seja bem vindo <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>
    <ul>
        <li><a href="cadastro_jogo.php">Cadastrar jogo</a></li>
        <li><a href="lista_jogos.php">lista de jogos</a></li>
        <li><a href="cadastro_categoria.html">cadastrar categoria</a></li>
        <li><a href="lista_categorias.php">lista de categorias</a></li>
        <li><a href="relatorio.php">jogos por categorias (relatorio)</a></li>
        <li><a href="logout.php">sair</a></li>
    </ul>
</body>
</html>