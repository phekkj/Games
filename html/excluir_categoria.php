<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

$cat_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($cat_id) {
    $delAssoc = $pdo->prepare("delete from jogos_categorias where categoria_id = ?");
    $delAssoc->execute([$cat_id]);

    $delCat = $pdo->prepare("delete from categorias where id = ?");
    $delCat->execute([$cat_id]);
}
header("Location: lista_categorias.php");
exit;
?>