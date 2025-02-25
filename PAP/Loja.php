<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar</title>
    <link rel="icon" type="image/x-icon" href="Imagens/CF.png">
    <link rel="stylesheet" href="estilo1.css">
    <script src="funcoes.js" defer></script>
</head>
<body>
<?php

    if(isset($_SESSION["utilizador"]))
    {
      require "menu.php";
      echo "<br><br><br><br>";
        echo "<p>".$_SESSION["utilizador"]."</p>";
        if($_SESSION["id_tipos_utilizador"]==0)
        {
            echo "<p><a href='inserir_produto.php'>Adicionar produtos</a></p>";
            echo "<p><a href='adm.php'>Editar utilizadores</a></p>";
        }
        
    ?>
    <form action="logout.php" method="post">
    <a href="historico-compras.php"> Histórico de compras </a>
        <input name="botaologout" type="submit" value="Terminar sessão">
    </form>
    <?php
    }
    else
    {
    ?>
<form action="login.php" method="post">
  <div class="imgcontainer">
    <img src="Imagens/av.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Usuário*</b></label>
    <input type="text" placeholder="Enter Username" name="utilizador" required>

    <label for="psw"><b>Palavra-passe*</b></label>
    <input type="password" id="password" placeholder="Enter Password" name="password" required>
    <img src="Imagens/mostrar.png" alt="Mostrar palavra-passe" id="togglePassword" style="width: 25px; height: 25px; cursor: pointer;" onclick="togglePasswordVisibility(event)"><br>
        
    <button name="botaologin" type="submit">Login</button>
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn"><a href="Pagina.html">Cancelar</a></button>
    <span class="psw"><a href="Conta.php">Não tem conta registada?</a></span>
  </div>
  <?php
    }
    ?>
    <div id="ERRO" style="color:red"></div>
    <?php
    if(isset($_SESSION["ERRO"]))
    {
        echo "<script>document.getElementById('ERRO').innerHTML='".$_SESSION["ERRO"]."'</script>";
        unset($_SESSION["ERRO"]);
    }
    ?>
  </form>
  
</body>

<?php
include "footer.php";
?>
</html>