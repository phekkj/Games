<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();
if(!isset($_SESSION['usuario_id'])){
    header("Location: login.html");
    exit;
}
try{
    include 'conexao.php';
}catch(PDOException $e){
    die($e->getMessage());
}
try{
    $categorias=$pdo->query("select id,nome from categorias")->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    die($e->getMessage());
}
$jogos=[];
$valor_total=0.0;
$categoria_nome='';
if(!empty($_POST['categoria_id'])){
    $categoria_id=(int)$_POST['categoria_id'];
    try{
        $stmt=$pdo->prepare("select nome from categorias where id=?");
        $stmt->execute([$categoria_id]);
        $categoria_nome=$stmt->fetchColumn()?:'';
    }catch(PDOException $e){
        die($e->getMessage());
    }
    try{
        $stmt=$pdo->prepare("select j.nome,j.preco from jogos j inner join jogos_categorias jc on j.id=jc.jogo_id where jc.categoria_id=?");
        $stmt->execute([$categoria_id]);
        $jogos=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        die($e->getMessage());
    }
    try{
        $stmt=$pdo->prepare("select sum(j.preco) as valor_total from jogos j inner join jogos_categorias jc on j.id=jc.jogo_id where jc.categoria_id=? group by jc.categoria_id");
        $stmt->execute([$categoria_id]);
        $valor_total=$stmt->fetchColumn()?:0.0;
    }catch(PDOException $e){
        die($e->getMessage());
    }
    if(isset($_POST['gerar_relatorio'])){
        $relatorio="relatorio: {$categoria_nome}\n\n";
        foreach($jogos as $jogo){
            $relatorio.="nome: {$jogo['nome']}\n";
            $relatorio.="preco: R$ ".number_format($jogo['preco'],2,',','.');
            $relatorio.="\n ----------------------------- \n";
        }
        $relatorio.="\ntotal: R$ ".number_format($valor_total,2,',','.');
        $arquivo='relatorio_categoria.txt';
        file_put_contents($arquivo,$relatorio);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$arquivo.'"');
        header('Content-Length:'.filesize($arquivo));
        readfile($arquivo);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>jogos por categoria</title>
</head>
<body>
<h2>escolha uma categoria</h2>
<form method="post">
<select name="categoria_id" required>
<option value="">-- Selecione --</option>
<?php foreach($categorias as $cat):?>
<option value="<?=htmlspecialchars($cat['id'],ENT_QUOTES)?>"<?=isset($_POST['categoria_id'])&&$_POST['categoria_id']==$cat['id']?' selected':''?>><?=htmlspecialchars($cat['nome'],ENT_QUOTES)?></option>
<?php endforeach;?>
</select>
<button type="submit">mostrar jogos</button>
<?php if(!empty($jogos)):?>
<button type="submit" name="gerar_relatorio">Gerar Relatorio</button>
<?php endif;?>
</form>
<?php if(!empty($jogos)):?>
<h3>categoria: <?=htmlspecialchars($categoria_nome,ENT_QUOTES)?></h3>
<table border="1">
<tr><th>jogo</th><th>preco</th></tr>
<?php foreach($jogos as $j):?>
<tr><td><?=htmlspecialchars($j['nome'],ENT_QUOTES)?></td><td>R$ <?=number_format($j['preco'],2,',','.')?></td></tr>
<?php endforeach;?>
<tr><td><strong>total</strong></td><td><strong>R$ <?=number_format($valor_total,2,',','.')?></strong></td></tr>
</table>
<?php elseif(isset($_POST['categoria_id'])):?>
<p>nenhum jogo nessa categoria vei</p>
<?php endif;?>
<p><a href="loja.php">voltar</a></p>
</body>
</html>