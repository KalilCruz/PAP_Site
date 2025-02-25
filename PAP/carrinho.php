<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Carrinho</title>
<link rel="icon" type="image/x-icon" href="Imagens/CF.png">
<link rel="stylesheet" href="estilo3.css">
<script src="funcoes.js" defer></script>
</head>
<body>
<?php
require "menu.php";
require "ligabd.php";
// obter o carrinho ativo
$sql = "select * from carrinho where carrinho.estado=0 and carrinho.id_utilizadores=
			(select id_utilizadores from utilizadores where utilizador = '" . $_SESSION["utilizador"] . "')";
//echo $sql; exit();		
$resultado = mysqli_query($con, $sql);
if (! $resultado ) {
	$_SESSION["erro"] = "Não foi possível obter os dados do carrinho";
	Header("Location: Loja.php");
	exit();
}

if (mysqli_num_rows($resultado) == 0) {
	echo "<h2 style='text-align: center'> Carrinho vazio </h3>";
	exit();
}

$registo = mysqli_fetch_array( $resultado );
$carrinho_id = $registo["id_carrinho"];
$preco_total_carrinho = $registo["preco_total_carrinho"];

// obter os itens do carrinho ativo
$sql = "select * from carrinho_itens, carrinho, produtos where 
			carrinho.id_carrinho=carrinho_itens.id_carrinho and produtos.id_produto = carrinho_itens.idproduto
			and carrinho.id_carrinho=" . $carrinho_id;
//echo $sql; exit();		
$resultado = mysqli_query($con, $sql);
if (! $resultado ) {
	$_SESSION["erro"] = "Não foi possível obter os dados dos produtos";
	Header("Location: Loja.php");
	exit();
}
?>
<div class="carrinho">

<?php
while ( $registo = mysqli_fetch_array( $resultado ) ) {
?>
	<div class="item_carrinho">
		<div>
			<?php echo $registo["designacao"]; ?>
		</div>
		<div>
			<input id="<?php echo 'item' . $registo['idproduto']?>" type="number" min="0" value="<?php echo $registo["quantidade"]; ?>">
			<button onclick="alterarQuantidadeItem('<?php echo 'item' . $registo['idproduto']?>')"> Alterar </button>
		</div>
		<div>
			<?php echo $registo["preco_total_itens"] . " €"; ?>
		</div>
		<div>
			<a href="gerir-carrinho.php?item_quantidade=0&idproduto=<?php echo $registo['idproduto']?>"> Remover </a>
		</div>
	</div>
<?php
}
?>

	<div class="carrinho_footer">
		<div>
			<a href="gerir-carrinho.php?remover_carrinho=<?php echo $carrinho_id; ?>"> Remover </a>
		</div>

		<div>
			<a href="gerir-carrinho.php?carrinho_estado=1&id_carrinho=<?php echo $carrinho_id; ?>"> Finalizar </a>
		</div>

		<div>
			Total: <?php echo $preco_total_carrinho . " €";?>
		</div>
	</div>

</div>

<div id="mensagem" style="color:blue"></div>
<div id="erro" style="color:red"></div>

<?php

// apresentar eventuais mensagens informativas
if ( isset($_SESSION["mensagem"]) ) {
	echo "<script>
				document.getElementById('mensagem').innerHTML	= '" . $_SESSION["mensagem"] . "';
		 </script>";
	unset($_SESSION["mensagem"]);
}

// verificar se houve erro e dar a respetiva mensagem
if ( isset($_SESSION["erro"]) ) {
	echo "<script>
				document.getElementById('erro').innerHTML	= '" . $_SESSION["erro"] . "';
		 </script>";
	unset($_SESSION["erro"]);
}
?>
</body>
</html>