<?php
session_start();
require "ligabd.php"; // Arquivo de conexão ao banco de dados

// Verifica se o botão de login foi clicado
if (!isset($_POST["botaologin"])) {
    $_SESSION["ERRO"] = "Acesso inválido.";
    header("Location: Loja.php");
    exit();
}

// Obtém os dados do formulário
$utilizador = mysqli_real_escape_string($con, $_POST["utilizador"]);
$password = $_POST["password"]; // Senha do formulário (não escapada)

// Encripta a senha fornecida com SHA-1
$hashed_password = sha1($password);

// Consulta para buscar o utilizador e verificar a senha
$sql = "SELECT * FROM utilizadores WHERE utilizador = '$utilizador'";
$resultado = mysqli_query($con, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $registo = mysqli_fetch_assoc($resultado);

    // Verifica se a senha encriptada coincide com a armazenada
    if ($registo["password"] === $hashed_password) {
        // Login bem-sucedido
        $_SESSION["utilizador"] = $registo["utilizador"];
        $_SESSION["id_tipos_utilizador"] = $registo["id_tipos_utilizador"];
        header("Location: Loja.php");
        exit();
    } else {
        // Senha incorreta
        $_SESSION["ERRO"] = "A palavra-passe está incorreta.";
        header("Location: Loja.php");
        exit();
    }
} else {
    // Utilizador não encontrado
    $_SESSION["ERRO"] = "O utilizador não existe.";
    header("Location: Loja.php");
    exit();
}
?>
