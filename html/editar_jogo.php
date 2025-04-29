<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

$jogo_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$jogo_id) {
    die("jogo errado man");
}

$stmt = $pdo->prepare("select nome, preco from jogos where id = ?");
$stmt->execute([$jogo_id]);
$jogo = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$jogo) {
    die("o sistema nao achou jogos (ass: sistema)");
}

$stmt = $pdo->prepare("select categoria_id from jogos_categorias where jogo_id = ?");
$stmt->execute([$jogo_id]);
$cats_associadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

$allCats = $pdo->query("select id, nome from categorias")->fetchAll();
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
    <title>jogoeditar</title>
</head>
<body>
    <h2>editar jogo</h2>
    <form action="update_jogo.php" method="post">
        <input type="hidden" name="id" value="<?php echo $jogo_id; ?>">
        <label>nome</label><br>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($jogo['nome']); ?>" required><br><br>
        <label>preco em reais</label><br>
        <input type="number" step="0.01" name="preco" value="<?php echo $jogo['preco']; ?>" required><br><br>
        <label>categorias</label><br>
        <select name="categorias[]" multiple size="5" required>
            <?php foreach ($allCats as $cat) : ?>
                <option value="<?php echo $cat['id']; ?>"
                    <?php if (in_array($cat['id'], $cats_associadas)) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cat['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit">calvar</button>
    </form>
    <p><a href="lista_jogos.php">cancelar</a></p>
</body>
</html>