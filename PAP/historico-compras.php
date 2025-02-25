<?php
session_start();
if ( ! isset($_SESSION["utilizador"]) ) {
	$_SESSION["mensagem"] = "Inicie sessão para ver o histórico de compras.";
	Header("Location: Loja.php");
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Histórico de compras</title>
<link rel="icon" type="image/x-icon" href="Imagens/CF.png">
<link rel="stylesheet" href="estilo3.css">
</head>
<body>
<?php
require "menu.php";
require "ligabd.php";
// obter os carrinhos finalizados
$sql_historico = "select * from carrinho where carrinho.estado=1 and carrinho.id_utilizadores=
			(select id_utilizadores from utilizadores where utilizador = '" . $_SESSION["utilizador"] . "')
			order by dthr_compra desc";

$resultado_historico = mysqli_query($con, $sql_historico);
if (! $resultado_historico ) {
	$_SESSION["erro"] = "Não foi possível obter o histórico de compras.1";
	Header("Location: Loja.php");
	exit();
}

if (mysqli_num_rows($resultado_historico) == 0) {
	echo "<h2 style='text-align: center'> Sem compras efetuadas. </h3>";
	exit();
}

// listar todos os carrinhos finalizados
while ( $registo_historico = mysqli_fetch_array( $resultado_historico) ) {
	// obter os itens de cada carrinho
	$sql = "select * from carrinho_itens, carrinho, produtos where 
				carrinho.id_carrinho=carrinho_itens.id_carrinho and produtos.id_produto = carrinho_itens.idproduto
				and carrinho.id_carrinho=" . $registo_historico["id_carrinho"];
		
	$resultado = mysqli_query($con, $sql);
	if (! $resultado ) {
		$_SESSION["erro"] = "Não foi possível obter o histórico de compras.2";
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
			<?php echo $registo["quantidade"]; ?>				
			</div>
			<div>
				<?php echo $registo["preco_total_itens"] . " €"; ?>
			</div>
		</div>
	<?php
	}
	?>

		<div class="carrinho_footer">
			<div>
				Total: <?php echo $registo_historico["preco_total_carrinho"] . " €";?>
			</div>
			<div>
				Data: <?php echo $registo_historico["dthr_compra"];?>
			</div>
		</div>

	</div>

<?php
}  // while exterior
?>

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