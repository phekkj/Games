<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
include 'conexao.php';

$query = "
    select j.id, j.nome, j.preco, group_concat(c.nome SEPARATOR ', ') as categorias
    from jogos j
    left join jogos_categorias jc on j.id = jc.jogo_id
    left join categorias c on jc.categoria_id = c.id
    group by j.id, j.nome, j.preco
";
$jogos = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jogos lista</title>
</head>
<body>
    <h2>jogos cadastrados</h2>
    <table>
        <thead>
            <tr>
                <th>nome</th>
                <th>preco</th>
                <th>categorias</th>
                <th>acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($jogos)): ?>
                <tr>
                    <td colspan="4">nao tem jogo</td>
                </tr>
            <?php else: ?>
                <?php foreach ($jogos as $j): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($j['nome']); ?></td>
                        <td><?php echo number_format($j['preco'], 2, ',', '.'); ?></td>
                        <td>
                            <?php
                            if (empty($j['categorias'])) {
                                echo "Sem categorias";
                            } else {
                                echo htmlspecialchars($j['categorias']);
                            }
                            ?>
                        </td>
                        <td class="actions">
                            <a href="editar_jogo.php?id=<?php echo $j['id']; ?>">editar</a> |
                            <a href="excluir_jogo.php?id=<?php echo $j['id']; ?>" onclick="return confirm('Excluir este jogo?');">excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <p><a href="loja.php">voltar</a></p>
</body>
</html>
