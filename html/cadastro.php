<?php
include 'conexao.php';

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);
$senha2 = filter_input(INPUT_POST, 'senha2', FILTER_DEFAULT);

if ($nome && $email && $senha && $senha === $senha2) {
    $stmt = $pdo->prepare("select id from usuarios where email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "email ja cadastrado.";
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $ins = $pdo->prepare("insert into usuarios (nome, email, senha_hash) values (?, ?, ?)");
        $ins->execute([$nome, $email, $hash]);
        echo "cadastro feito meu mano <a href='login.html'>Faça login</a>";
    }
} else {
    echo "dados incorretos!";
}
?>
