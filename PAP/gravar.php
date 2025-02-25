<?php
session_start();

if (!isset($_POST["botaoGravar"]) || !isset($_SESSION["utilizador"]) || $_SESSION["id_tipos_utilizador"] != 0) {
    header("Location: Loja.php");
    exit();
}

require "ligabd.php";

// Capturar os dados do formulário
$id_utilizador = intval($_POST["id_utilizadores"]);
$password = sha1(trim($_POST["password"])); // Encriptação usando sha1
$email = trim($_POST["email"]);
$id_tipos_utilizador = intval($_POST["id_tipos_utilizador"]);

// Impedir alterações no administrador por outro utilizador
if ($_POST["utilizador"] == "admin" && $_SESSION["utilizador"] != "admin") {
    header("Location: Loja.php");
    exit();
}

// Verificar campos obrigatórios
if (empty($email) || empty($password)) {
    $_SESSION["ERRO"] = "Todos os campos são obrigatórios!";
    header("Location: adm.php");
    exit();
}

// Query de atualização
$sql_gravar = "
    UPDATE utilizadores 
    SET 
        password = '$password', 
        email = '$email', 
        id_tipos_utilizador = $id_tipos_utilizador 
    WHERE 
        id_utilizadores = $id_utilizador
";

// Executar a query
if (!mysqli_query($con, $sql_gravar)) {
    $_SESSION["ERRO"] = "Erro ao atualizar os dados do utilizador.";
    header("Location: adm.php");
    exit();
}

// Sucesso
$_SESSION["SUCESSO"] = "Utilizador atualizado com sucesso!";
header("Location: adm.php");
exit();
?>
