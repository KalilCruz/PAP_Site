<?php
session_start();
require "ligabd.php";

// Reencaminhar para Loja.php se o utilizador não for administrador
if (!isset($_SESSION["utilizador"]) || $_SESSION["id_tipos_utilizador"] != 0) {
    header("Location: Loja.php");
    exit();
}

// Ações para inserir, remover ou atualizar utilizadores
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['botaoInserir'])) {
        // Inserir novo utilizador
        $utilizador = trim($_POST['utilizador']);
        $password = sha1(trim($_POST['password'])); // Encriptação usando sha1
        $email = trim($_POST['email']);
        $id_tipos_utilizador = intval($_POST['id_tipos_utilizador']);
        $danasc = $_POST['danasc'];

        if (empty($utilizador) || empty($password) || empty($email) || empty($danasc)) {
            $_SESSION['ERRO'] = "Todos os campos devem ser preenchidos!";
        } else {
            $sql_insert = "
                INSERT INTO utilizadores (utilizador, password, email, id_tipos_utilizador, danasc) 
                VALUES ('$utilizador', '$password', '$email', $id_tipos_utilizador, '$danasc')
            ";
            if (mysqli_query($con, $sql_insert)) {
                $_SESSION['SUCESSO'] = "Novo utilizador inserido com sucesso!";
            } else {
                $_SESSION['ERRO'] = "Erro ao inserir utilizador.";
            }
        }
    } elseif (isset($_POST['botaoRemover'])) {
        // Remover utilizador
        $id_utilizador = intval($_POST['id_utilizadores']);
        if ($id_utilizador != 1) { // Impedir remoção do admin
            $sql_delete = "DELETE FROM utilizadores WHERE id_utilizadores = $id_utilizador";
            if (mysqli_query($con, $sql_delete)) {
                $_SESSION['SUCESSO'] = "Utilizador removido com sucesso!";
            } else {
                $_SESSION['ERRO'] = "Erro ao remover utilizador.";
            }
        } else {
            $_SESSION['ERRO'] = "Não é permitido remover o administrador.";
        }
    } elseif (isset($_POST['botaoGravar'])) {
        // Encaminhar para o script gravar.php
        include "gravar.php";
        exit();
    }

    header("Location: adm.php");
    exit();
}

// Obter lista de utilizadores
$sql = "SELECT * FROM utilizadores, tipos_utilizador WHERE utilizadores.id_tipos_utilizador = tipos_utilizador.id_tipos_utilizador";
$resultado = mysqli_query($con, $sql);
if (!$resultado) {
    $_SESSION["ERRO"] = "Não foi possível obter os dados dos utilizadores.";
    header("Location: Loja.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar utilizadores</title>
    <link rel="stylesheet" href="estilo3.css">
</head>
<body>
    <h2>Gerenciar Utilizadores</h2>
    <?php if (isset($_SESSION["ERRO"])): ?>
        <div class="erro"><?= htmlspecialchars($_SESSION["ERRO"]); ?></div>
        <?php unset($_SESSION["ERRO"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["SUCESSO"])): ?>
        <div class="sucesso"><?= htmlspecialchars($_SESSION["SUCESSO"]); ?></div>
        <?php unset($_SESSION["SUCESSO"]); ?>
    <?php endif; ?>

    <table>
        <tr>
            <th>Utilizador</th>
            <th>Palavra-passe</th>
            <th>Email</th>
            <th>Tipo de Utilizador</th>
            <th>Ações</th>
        </tr>
        <?php while ($registo = mysqli_fetch_assoc($resultado)): ?>
            <?php if ($registo["utilizador"] == "admin" && $_SESSION["utilizador"] != "admin") continue; ?>
            <form action="gravar.php" method="post">
                <tr>
                    <td><input name="utilizador" type="text" value="<?= $registo["utilizador"]; ?>" readonly></td>
                    <td><input name="password" type="password" value=""></td>
                    <td><input name="email" type="email" value="<?= $registo["email"]; ?>"></td>
                    <td>
                        <select name="id_tipos_utilizador">
                            <option value="0" <?= ($registo["id_tipos_utilizador"] == 0) ? "selected" : ""; ?>>Administrador</option>
                            <option value="1" <?= ($registo["id_tipos_utilizador"] == 1) ? "selected" : ""; ?>>Utilizador</option>
                        </select>
                    </td>
                    <td>
                        <button name="botaoGravar">Gravar</button>
                        <button name="botaoRemover" <?= ($registo["utilizador"] == "admin") ? "disabled" : ""; ?>>Remover</button>
                    </td>
                    <input type="hidden" name="id_utilizadores" value="<?= $registo["id_utilizadores"]; ?>">
                </tr>
            </form>
        <?php endwhile; ?>
    </table>

    <h3>Criar Novo Utilizador</h3>
    <form action="adm.php" method="post">
        <table>
            <tr>
                <td><input name="utilizador" type="text" required placeholder="Nome do Utilizador"></td>
                <td><input name="password" type="password" required placeholder="Senha"></td>
                <td><input name="email" type="text" required placeholder="Email"></td>
                <td>
                    <select name="id_tipos_utilizador">
                        <option value="0">Administrador</option>
                        <option value="1">Utilizador</option>
                    </select>
                </td>
                <td>
                    <input name="danasc" type="date" required>
                </td>
                <td>
                    <button name="botaoInserir">Inserir</button>
                </td>
            </tr>
        </table>
    </form>

    <a href="Loja.php">Voltar à Loja</a>
</body>
</html>
