<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

$jogo_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($jogo_id) {

    $delAssoc = $pdo->prepare("delete from jogos_categorias where jogo_id = ?");
    $delAssoc->execute([$jogo_id]);

    $delJogo = $pdo->prepare("delete from jogos where id = ?");
    $delJogo->execute([$jogo_id]);
}
header("Location: lista_jogos.php");
exit;
?>