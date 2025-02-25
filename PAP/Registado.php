<?php
session_start();
require "ligabd.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obter e limpar os dados do formulário
    $utilizador = trim(mysqli_real_escape_string($con, $_POST["utilizador"]));
    $password = sha1($_POST["password"]);
    $email = trim(mysqli_real_escape_string($con, $_POST["email"]));
    $data_nasc = trim(mysqli_real_escape_string($con, $_POST["danasc"]));

    // Validar campos obrigatórios
    if (empty($utilizador) || empty($password) || empty($email) || empty($data_nasc)) {
        $_SESSION["ERRO"] = "Todos os campos são obrigatórios!";
        header("Location: Conta.php");
        exit();
    }

    // Validar o formato do email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["ERRO"] = "Formato de email inválido!";
        header("Location: Conta.php");
        exit();
    }

    // Verificar se o utilizador já existe
    $sql_existe = "SELECT * FROM utilizadores WHERE utilizador = '$utilizador'";
    $resultado = mysqli_query($con, $sql_existe);

    if (!$resultado) {
        $_SESSION["ERRO"] = "Erro ao verificar o utilizador!";
        header("Location: Conta.php");
        exit();
    }

    if (mysqli_num_rows($resultado) > 0) {
        $_SESSION["ERRO"] = "O utilizador já se encontra registado!";
        header("Location: Conta.php");
        exit();
    }


    // Inserir o novo utilizador
    $sql_inserir = "
        INSERT INTO utilizadores (utilizador, password, email, id_tipos_utilizador, danasc)
        VALUES ('$utilizador', '$password', '$email', 1, '$data_nasc')
    ";
    $resultado_inserir = mysqli_query($con, $sql_inserir);

    if ($resultado_inserir) {
        $_SESSION["SUCESSO"] = "Utilizador registado com sucesso!";
    } else {
        $_SESSION["ERRO"] = "Erro ao inserir o utilizador!";
    }
} else {
    $_SESSION["ERRO"] = "Método de requisição inválido.";
    header("Location: Conta.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registado</title>
    <link rel="icon" type="image/x-icon" href="Imagens/CF.png">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!-- Exibir mensagens de erro ou sucesso -->
    <?php if (isset($_SESSION["ERRO"])): ?>
        <div class="erro">
            <?= htmlspecialchars($_SESSION["ERRO"]); ?>
        </div>
        <?php unset($_SESSION["ERRO"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["SUCESSO"])): ?>
        <div class="sucesso">
            <?= htmlspecialchars($_SESSION["SUCESSO"]); ?>
        </div>
        <button onclick="window.location.href='login.php'" name="botaologin" type="submit">Avançar para a loja</button>
        <button onclick="window.location.href='Conta.php'">Criar outro utilizador</button>
        <?php unset($_SESSION["SUCESSO"]); ?>
    <?php endif; ?>
</body>
</html>
