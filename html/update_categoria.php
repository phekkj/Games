<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

$cat_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome = htmlspecialchars(filter_input(INPUT_POST, 'nome'), ENT_QUOTES, 'UTF-8');

if ($cat_id && $nome) {
    $up = $pdo->prepare("update categorias set nome = ? where id = ?");
    $up->execute([$nome, $cat_id]);
    echo "Categoria atualizada!. <a href='lista_categorias.php'>Voltar</a>";
} else {
    echo "Dados incorretos.";
}
?>