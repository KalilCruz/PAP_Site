<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Produtos</title>
<link rel="icon" type="image/x-icon" href="Imagens/CF.png">
<link rel="stylesheet" href="estilo3.css">
</head>
<?php
require "menu.php";
require "ligabd.php";
$sql = "select * from produtos";
$resultado = mysqli_query($con, $sql);
if (! $resultado ) {
	$_SESSION["erro"] = "Não foi possível obter os dados dos produtos";
	Header("Location: Loja.php");
	exit();
}
?>

<div class="galeria">

<?php
while ( $registo = mysqli_fetch_array( $resultado ) ) {
?>
	<div class="produto">
		<p> <?php echo $registo["designacao"]; ?> </p>
		<p> <?php echo $registo["preco"] . " €"; ?> </p>
		<p> <a href="detalhe_produto.php?idproduto=<?php echo $registo["id_produto"]; ?>"> Mais informações </a></p>
	</div>
<?php
}
?>
<body>
<body>
</body>