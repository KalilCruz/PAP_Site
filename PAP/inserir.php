<?php
session_start();

// Verificar se o utilizador está logado como administrador e se a requisição foi feita corretamente
if (!isset($_SESSION["utilizador"]) || !isset($_POST["botaoInserir"]) || $_SESSION["id_tipos_utilizador"] != 0) {
    header("Location: Loja.php");
    exit();
}

require "ligabd.php";

// Verificar se o utilizador já existe
$utilizador = trim($_POST["utilizador"]);
$sql_existe = "SELECT * FROM utilizadores WHERE utilizador = '$utilizador'";
$resultado_existe = mysqli_query($con, $sql_existe);

if ($resultado_existe && mysqli_num_rows($resultado_existe) > 0) {
    $_SESSION["ERRO"] = "O utilizador já se encontra registado!";
    header("Location: adm.php");
    exit();
}

// Preparar os dados para inserção
$password = sha1(trim($_POST["password"])); // Encriptação com sha1
$email = trim($_POST["email"]);
$id_tipos_utilizador = intval($_POST["id_tipos_utilizador"]);
$danasc = $_POST["danasc"]; // Data de nascimento

// Inserir o utilizador
$sql_inserir = "
    INSERT INTO utilizadores (utilizador, password, email, id_tipos_utilizador, danasc) 
    VALUES ('$utilizador', '$password', '$email', $id_tipos_utilizador, '$danasc')
";

if (mysqli_query($con, $sql_inserir)) {
    $_SESSION["SUCESSO"] = "Utilizador inserido com sucesso!";
    header("Location: adm.php");
    exit();
} else {
    $_SESSION["ERRO"] = "Não foi possível inserir o utilizador.";
    header("Location: adm.php");
    exit();
}
?>
