<?php

session_start();
//var_dump($_SESSION); echo "</br>"; var_dump($_GET); exit();

// Atualiza o custo total do carrinho
function atualizar_custo_carrinho($carrinho_id) {
    $sql = "UPDATE carrinho SET preco_total_carrinho =
            (SELECT SUM(preco_total_itens) FROM carrinho_itens WHERE id_carrinho = $carrinho_id)
            WHERE id_carrinho = $carrinho_id";
    
    global $con;  // Para a função ter acesso à variável global $conexao
    
    $resultado = mysqli_query($con, $sql);
    if (!$resultado) {
        return false;
    }
    return true;
}

require "ligabd.php";

// Verificar se existe uma sessão iniciada
if (!isset($_SESSION["utilizador"])) {
    $_SESSION["mensagem"] = "Inicie sessão para efetuar compras.";
    Header("Location: Loja.php");
    exit();
}

// **** Operações globais sobre um carrinho ****

// Verificar se foi especificada a alteração do estado do carrinho
if (isset($_GET["carrinho_estado"])) {
    $sql = "UPDATE carrinho SET estado = " . $_GET["carrinho_estado"] . ", dthr_compra = now() WHERE id_carrinho = " . $_GET["id_carrinho"];
    $resultado = mysqli_query($con, $sql);
    if (!$resultado) {
        $_SESSION["erro"] = "Não foi possível finalizar o carrinho.";
        Header("Location: carrinho.php");
        exit();
    } else {
        $_SESSION["mensagem"] = "Carrinho finalizado com sucesso.";
        Header("Location: Loja.php");
        exit();
    }
} else if (isset($_GET["remover_carrinho"])) {
    // Remover primeiro os itens do carrinho
    $sql = "DELETE FROM carrinho_itens WHERE id_carrinho = " . $_GET["remover_carrinho"];
    $resultado = mysqli_query($con, $sql);
    if (!$resultado) {
        $_SESSION["erro"] = "Não foi possível remover os itens do carrinho.";
    }

    // Remover o carrinho
    $sql = "DELETE FROM carrinho WHERE id_carrinho = " . $_GET["remover_carrinho"];
    $resultado = mysqli_query($con, $sql);
    if (!$resultado) {
        $_SESSION["erro"] = "Não foi possível remover o carrinho.";
    }

    Header("Location: carrinho.php");
    exit();
}

// **** Operações sobre os itens de um carrinho ****

// Verificar se foi especificada uma quantidade para um item do carrinho
if (isset($_GET["item_quantidade"])) {
    if (!is_numeric($_GET["item_quantidade"]) || $_GET["item_quantidade"] < 0) { 
        $_SESSION["erro"] = "A quantidade do item no carrinho tem de ser positiva ou zero (remoção do item).";
        Header("Location: carrinho.php");
        exit();
    }
} else if (!isset($_GET["adicionar_item"])) {
    Header("Location: Loja.php");
    exit();
}

$produto_id = $_GET["idproduto"];

// Verificar se existe um carrinho ativo
$sql = "SELECT carrinho.id_carrinho FROM carrinho WHERE carrinho.estado = 0 AND 
        carrinho.id_utilizadores = (SELECT id_utilizadores FROM utilizadores WHERE utilizador = '" . $_SESSION["utilizador"] . "')";
    
$resultado = mysqli_query($con, $sql);
if (!$resultado) {
    $_SESSION["erro"] = "Não foi possível alterar o carrinho de compras.";
    Header("Location: carrinho.php");
    exit();
}

if (mysqli_num_rows($resultado) != 0) {
    // Existe um carrinho ativo
    $registo = mysqli_fetch_array($resultado);
    $carrinho_id = $registo["id_carrinho"];
} else {
    // Não existe um carrinho ativo - criá-lo
    $sql = "INSERT INTO carrinho (estado, dthr_compra, preco_total_carrinho, id_utilizadores) 
            VALUES (0, now(), 0, (SELECT id_utilizadores FROM utilizadores WHERE utilizador = '" . $_SESSION["utilizador"] . "'))";
    
    $resultado = mysqli_query($con, $sql);
    if (!$resultado) {
        $_SESSION["erro"] = "Não foi possível alterar o carrinho de compras.";
        Header("Location: carrinho.php");
        exit();    
    }
    
    $sql = "SELECT last_insert_id() AS id_carrinho";  // Obter o id do carrinho criado
    $resultado = mysqli_query($con, $sql);
    if (!$resultado) {
        $_SESSION["erro"] = "Não foi possível alterar o carrinho.";
        Header("Location: carrinho.php");
        exit();    
    }
    
    $registo = mysqli_fetch_array($resultado);
    $carrinho_id = $registo["id_carrinho"];
}

// Verificar se o produto já existe no carrinho
$sql = "SELECT carrinho_itens.id_itens AS carrinho_itens_id, carrinho_itens.quantidade, carrinho_itens.idproduto 
        FROM carrinho_itens WHERE carrinho_itens.id_carrinho = $carrinho_id AND carrinho_itens.idproduto = $produto_id";

$resultado = mysqli_query($con, $sql);
if (!$resultado) {
    $_SESSION["erro"] = "Não foi possível alterar o carrinho.";
    Header("Location: carrinho.php");
    exit();
}

if (mysqli_num_rows($resultado) == 0) {
    // O produto não está no carrinho - adicionar novo item
    $sql = "INSERT INTO carrinho_itens (quantidade, preco_total_itens, id_carrinho, idproduto)
            VALUES (1, (SELECT preco FROM produtos WHERE id_produto = $produto_id), $carrinho_id, $produto_id)";

    $resultado = mysqli_query($con, $sql);
    if (!$resultado) {
        $_SESSION["erro"] = "Não foi possível inserir o item no carrinho.";
    } else if (!atualizar_custo_carrinho($carrinho_id)) {
        $_SESSION["erro"] = "Não foi possível atualizar o custo do carrinho.";
    }
} else {
    // O produto já está no carrinho, atualizar a quantidade
    $registo = mysqli_fetch_array($resultado);
    $carrinho_itens_id = $registo["carrinho_itens_id"];
    $nova_quantidade = isset($_GET["adicionar_item"]) ? $registo["quantidade"] + 1 : $_GET["item_quantidade"];

    // Atualiza a quantidade e o preço total
    $sql = "UPDATE carrinho_itens
            SET quantidade = $nova_quantidade, 
                preco_total_itens = (SELECT preco FROM produtos WHERE id_produto = $produto_id) * $nova_quantidade
            WHERE id_itens = $carrinho_itens_id";
    
    $resultado = mysqli_query($con, $sql);
    if (!$resultado) {
        $_SESSION["erro"] = "Não foi possível alterar a quantidade do item no carrinho.";
    } else if (!atualizar_custo_carrinho($carrinho_id)) {
        $_SESSION["erro"] = "Não foi possível atualizar o custo do carrinho.";
    }
}

Header("Location: carrinho.php");
exit();

?>
