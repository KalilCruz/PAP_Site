<?php
session_start();
if (isset($_GET["id_produto"])) {
    Header("Location: Loja.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do produto</title>
    <link rel="icon" type="image/x-icon" href="Imagens/CF.png">
    <link rel="stylesheet" href="estilo3.css">
</head>
<body>

<?php
require "menu.php";

require "ligabd.php";

$sql = "SELECT * FROM produtos WHERE id_produto = " . $_GET["idproduto"];

$resultado = mysqli_query($con, $sql);
if (!$resultado) {
    $_SESSION["erro"] = "Não foi possível obter os dados do produto";
    Header("Location: produtos.php");
    exit();
}

if ($registo = mysqli_fetch_array($resultado)) {
    $imagem_produto = $registo["imagem"];  // Aqui pegamos o caminho da imagem do produto
?>
    <div class="galeria">
        <div class="produto">
            <p> <?php echo $registo["designacao"]; ?> </p>
            <p> <?php echo $registo["preco"] . " €"; ?> </p>
            <p> <a href="gerir-carrinho.php?adicionar_item=true&idproduto=<?php echo $_GET['idproduto']?>"> Adicionar ao carrinho </a></p>
            
            <!-- Exibindo a imagem do produto -->
            <?php if (!empty($imagem_produto)) { ?>
                <img src="<?php echo $imagem_produto; ?>" alt="Imagem do produto" style="max-width: 300px; max-height: 300px;">
            <?php } else { ?>
                <p>Imagem não disponível</p>
            <?php } ?>
        </div>
    </div>
<?php
}
?>

<div id="mensagem" style="color:blue"></div>
<div id="erro" style="color:red"></div>

<?php

// apresentar eventuais mensagens informativas
if (isset($_SESSION["mensagem"])) {
    echo "<script>
                document.getElementById('mensagem').innerHTML = '" . $_SESSION["mensagem"] . "'; 
          </script>";
    unset($_SESSION["mensagem"]);
}

// verificar se houve erro e dar a respetiva mensagem
if (isset($_SESSION["erro"])) {
    echo "<script>
                document.getElementById('erro').innerHTML = '" . $_SESSION["erro"] . "'; 
          </script>";
    unset($_SESSION["erro"]);
}
?>

</body>
</html>
