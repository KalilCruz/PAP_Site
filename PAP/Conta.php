<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova conta</title>
    <link rel="icon" type="image/x-icon" href="Imagens/CF.png">
    <link rel="stylesheet" href="estilo2.css">
    <script src="funcoes.js" defer></script>
</head>

<body>
<header>Registar conta</header>

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
    <?php unset($_SESSION["SUCESSO"]); ?>
<?php endif; ?>

<form id="formulario" action="Registado.php" method="POST" onsubmit="validarFormulario(event)">
    <label for="name">Nome para o Utilizador:*</label>
    <input type="text" id="name" name="utilizador" required><br><br>
    
    <label for="email">Email:*</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Palavra-passe:*</label>
    <input type="password" id="password" name="password" required>
    <img src="Imagens/mostrar.png" alt="Mostrar palavra-passe" id="togglePassword" style="width: 25px; height: 25px; cursor: pointer;"><br><br>

    <label for="confirm_password">Confirmar:*</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <img src="Imagens/mostrar.png" alt="Mostrar palavra-passe" id="toggleConfirmPassword" style="width: 25px; height: 25px; cursor: pointer;"><br><br>

    <p>Requisitos para a Palavra-passe</p>
    <ul id="password-requirements">
        <li id="char-count" class="fail">Mínimo de 8 dígitos</li>
        <li id="lowercase" class="fail">Pelo menos uma letra minúscula</li>
        <li id="uppercase" class="fail">Pelo menos uma letra maiúscula</li>
        <li id="number" class="fail">Pelo menos um número</li>
        <li id="special-char" class="fail">Pelo menos um caráter especial</li>
    </ul>

    <label for="birth_date">Data de Nascimento:*</label>
    <input type="date" id="birth_date" name="danasc" required><br><br>

    <button type="submit">Registar</button><br>
    <button type="button"><a href="Loja.php">Voltar</a></button>
</form>
</body>

</html>
