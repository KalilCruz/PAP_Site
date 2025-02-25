<?php
session_start();

if (!isset($_SESSION["utilizador"]) || $_SESSION["id_tipos_utilizador"] != 0) {
    $_SESSION["ERRO"] = "Acesso negado. Apenas administradores podem adicionar produtos.";
    header("Location: Loja.php");
    exit();
}

require "ligabd.php";

if (isset($_POST["botaoInserir"])) {
    $designacao = $_POST["designacao"];
    $preco = $_POST["preco"];

    if (!is_numeric($preco) || $preco <= 0) {
        $_SESSION["ERRO"] = "Preço inválido. Insira um número maior que 0.";
        header("Location: inserir_produto.php");
        exit();
    }

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $tipo_arquivo = mime_content_type($_FILES['imagem']['tmp_name']);
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($tipo_arquivo, $tipos_permitidos)) {
            $_SESSION["ERRO"] = "O arquivo enviado não é uma imagem válida. Apenas JPG, PNG e GIF são permitidos.";
            header("Location: inserir_produto.php");
            exit();
        }

        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_temp = $_FILES['imagem']['tmp_name'];
        $imagem_destino = "Imagens/" . uniqid() . "_" . basename($imagem_nome);

        if (move_uploaded_file($imagem_temp, $imagem_destino)) {
            $designacao = mysqli_real_escape_string($con, $designacao);
            $preco = mysqli_real_escape_string($con, $preco);
            $imagem_destino = mysqli_real_escape_string($con, $imagem_destino);

            $sql_inserir = "INSERT INTO produtos (designacao, preco, imagem) VALUES ('$designacao', '$preco', '$imagem_destino')";
            $resultado = mysqli_query($con, $sql_inserir);

            if ($resultado) {
                $_SESSION["SUCESSO"] = "Produto inserido com sucesso!";
                header("Location: inserir_produto.php");
                exit();
            } else {
                $_SESSION["ERRO"] = "Erro ao inserir o produto.";
            }
        } else {
            $_SESSION["ERRO"] = "Erro ao mover a imagem para o servidor.";
        }
    } else {
        $_SESSION["ERRO"] = "Imagem não foi enviada ou ocorreu um erro.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Produto</title>
    <link rel="icon" type="image/x-icon" href="Imagens/CF.png">
    <link rel="stylesheet" href="estilo3.css">
</head>
<body>
    <h2>Inserir Novo Produto</h2>

    <?php if (isset($_SESSION["ERRO"])): ?>
        <div class="erro"><?= htmlspecialchars($_SESSION["ERRO"]); ?></div>
        <?php unset($_SESSION["ERRO"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["SUCESSO"])): ?>
        <div class="sucesso"><?= htmlspecialchars($_SESSION["SUCESSO"]); ?></div>
        <?php unset($_SESSION["SUCESSO"]); ?>
    <?php endif; ?>

    <form action="inserir_produto.php" method="post" enctype="multipart/form-data">
        <label for="designacao">Designação:</label>
        <input type="text" name="designacao" required><br><br>

        <label for="preco">Preço:</label>
        <input type="text" name="preco" required><br><br>

        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem" accept="image/*" required><br><br>

        <button name="botaoInserir">Inserir Produto</button>
    </form>

    <a href="Loja.php">Voltar à Loja</a>
</body>
</html>
