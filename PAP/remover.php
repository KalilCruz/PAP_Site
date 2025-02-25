<?php
session_start();

// Verificar se o usuário está logado como administrador e se a requisição foi feita corretamente
if (!isset($_POST["botaoGravar"]) || !isset($_SESSION["utilizador"]) || $_SESSION["id_tipos_utilizador"] != 0) {
    header("Location: Loja.php");
    exit();
}

// Impedir a edição do "admin" se o usuário não for o próprio "admin"
if ($_POST["utilizador"] == "admin" && $_SESSION["utilizador"] != "admin") {
    header("Location: Loja.php");
    exit();
}

require "ligabd.php";

// Obter os dados do formulário de forma segura
$utilizador = trim($_POST["utilizador"]);
$password = $_POST["password"];
$email = trim($_POST["email"]);
$id_tipos_utilizador = $_POST["id_tipos_utilizador"];

// Verificar se a senha foi informada, se não, mantém a senha atual
if (!empty($password)) {
    // Se uma nova senha foi fornecida, vamos hasheá-la
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
} else {
    // Caso contrário, manter a senha atual
    $password_hash = null;
}

// Preparar a consulta para evitar SQL Injection
if ($password_hash !== null) {
    $sql_gravar = "UPDATE utilizadores SET password = ?, email = ?, id_tipos_utilizador = ? WHERE utilizador = ?";
    $stmt = $con->prepare($sql_gravar);
    $stmt->bind_param("ssis", $password_hash, $email, $id_tipos_utilizador, $utilizador);
} else {
    $sql_gravar = "UPDATE utilizadores SET email = ?, id_tipos_utilizador = ? WHERE utilizador = ?";
    $stmt = $con->prepare($sql_gravar);
    $stmt->bind_param("sis", $email, $id_tipos_utilizador, $utilizador);
}

// Executar a consulta
if ($stmt->execute()) {
    $_SESSION["SUCESSO"] = "Dados do utilizador atualizados com sucesso!";
} else {
    $_SESSION["ERRO"] = "Não foi possível atualizar os dados do utilizador.";
}

// Redirecionar de volta para a página de administração
header("Location: adm.php");
exit();
?>
