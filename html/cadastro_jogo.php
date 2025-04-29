<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
    $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
    $categorias = filter_input(INPUT_POST, 'categorias', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    if (!$nome || $preco === false || empty($categorias)) {
        die("Por favor, preencha todos os campos corretamente.");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO jogos (nome, preco, ano_criacao) VALUES (?, ?, YEAR(CURDATE()))");
        $stmt->execute([$nome, $preco]);

        $jogo_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO jogos_categorias (jogo_id, categoria_id) VALUES (?, ?)");
        foreach ($categorias as $categoria_id) {
            $stmt->execute([$jogo_id, $categoria_id]);
        }

        echo "Jogo cadastrado com sucesso!";
    } catch (PDOException $e) {
        die("Erro ao cadastrar o jogo: " . $e->getMessage());
    }
}

$categorias = $pdo->query("SELECT id, nome FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
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
    <title>cadastrar jogo</title>
</head>
<body>
    <h2>cadastrar jogo</h2>
    <form action="cadastro_jogo.php" method="post">
        <label>nome do jogo:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>preco em reais</label><br>
        <input type="number" step="0.01" name="preco" required><br><br>

        <label>categorias</label><br>
        <select name="categorias[]" multiple size="5" required>
            <?php

            foreach ($categorias as $cat) {
                echo "<option value='{$cat['id']}'>".htmlspecialchars($cat['nome'])."</option>";
            }
            ?>
        </select><br><br>

        <button type="submit">cadastrar</button>
    </form>


    <p><a href="loja.php">voltar</a></p>
</body>
</html>