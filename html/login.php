<?php
session_start();
include 'conexao.php';

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

if ($email && $senha) {
    $stmt = $pdo->prepare("select id, nome, senha_hash from usuarios where email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha_hash'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        header("Location: loja.php");
        exit;
    } else {
        echo "Email ou senha invalidos.";
    }
} else {
    echo "os dados sao invalidos";
}
?>