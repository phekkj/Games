<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

$jogo_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome = trim(
    filter_input(INPUT_POST, 'nome', FILTER_UNSAFE_RAW)
);
$nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
$preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
$cats = filter_input(INPUT_POST, 'categorias', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

if ($jogo_id && $nome && $preco !== false && $cats) {

    $up = $pdo->prepare("update jogos set nome = ?, preco = ? where id = ?");
    $up->execute([$nome, $preco, $jogo_id]);

    $del = $pdo->prepare("delete from jogos_categorias where jogo_id = ?");
    $del->execute([$jogo_id]);

    $ins = $pdo->prepare("insert into jogos_categorias (jogo_id, categoria_id) values (?, ?)");
    foreach ($cats as $cat_id) {
        $ins->execute([$jogo_id, $cat_id]);
    }
    echo "Jogo atualizado. <a href='lista_jogos.php'>Voltar</a>";
} else {
    echo "Dados incorretos.";
}
?>