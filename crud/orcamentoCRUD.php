<?php
session_start();
require('conexao_DB.php');
//essa pagina só será acessada via requisiçao POST
//verificando se a chave criar_orcamento existe no meu POST quando a pagina for chamada
if (isset($_POST['criar_orcamento'])) {
    $cliente = trim($_POST['cliente']);
    $vendedor = trim($_POST['vendedor']);
    $descricao = trim($_POST['descricao']);
    $valor = trim($_POST['valor']);
    // Verifica se é um número válido 
    if (!is_numeric($valor)) {
        // Retorna mensagem informando o erro
        $_SESSION['mensagem'] = 'Valor orçado inválido. Digite apenas números.';
        header('Location: ../index.php');
        exit;
    }
    $sql = "INSERT INTO orcamento_estoque(cliente, dta_hora_orcamento, vendedor, descricao, valor_orcado) 
                                    VALUES('$cliente', SYSDATE(), '$vendedor', '$descricao', '$valor')";
    //echo $sql;
    // Faço o insert no banco 
    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0) {
        // redireciono para index e crio uma mensagem para ser exibida para o usuario 
        $_SESSION['mensagem'] = 'Orçamento Criado com sucesso';
        header('location:../index.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Orçamento não pode ser criado Procure o administrador';
        header('location:../index.php');
        exit;
    }
}

if (isset($_POST['editar_orcamento'])) {
    $id = trim($_POST['id']);
    $cliente = trim($_POST['cliente']);
    $vendedor = trim($_POST['vendedor']);
    $descricao = trim($_POST['descricao']);
    $valor = trim($_POST['valor']);
    $valorDigitado = $_POST['valor'];
    $valor = str_replace(',', '.', $valorDigitado);

    // Verifica se é um número válido 
    if (!is_numeric($valor)) {
        // Retorna mensagem informando o erro
        $_SESSION['mensagem'] = 'Valor orçado inválido. Digite apenas números.';
        header('Location: ../index.php');
        exit;
    }

    $sql = "UPDATE orcamento_estoque 
    SET cliente = '$cliente', 
    dta_hora_orcamento= SYSDATE(),
     vendedor= '$vendedor',
      descricao = '$descricao', 
      valor_orcado = '$valor' 
      WHERE id='$id'";

    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = 'Orçamento editado com Sucesso';
        header('location:../index.php');
        exit;
    } else {
        $_SESSION['mensagem'] = "O orçamento NÃO foi editado";
        header('location:../index.php');
        exit;
    }
}

if (isset($_POST['deletar_orcamento'])) {
    $id = trim($_POST['deletar_orcamento']);
    $sql = "DELETE FROM orcamento_estoque WHERE id='$id'";
    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['mensagem'] = "Orçamento excluido com sucesso";
        header('location:../index.php');
        exit;
    } else {
        $_SESSION['mensagem'] = "Orçamento não foi excuido";
        header('location:../index.php');
        exit;
    }
}
