<?php
session_start();
if(isset($_SESSION["utilizador"]) && isset($_POST["botaologout"]))
{
    session_unset();
    session_destroy();
}
header("Location: Loja.php");
exit();
?>