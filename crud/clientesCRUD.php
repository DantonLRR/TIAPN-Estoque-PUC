<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
require('conexao_DB.php');



if (isset($_POST['criar_cliente'])){
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $tipo_cliente = trim($_POST['tipo_cliente']);
    $cpf = trim($_POST['cpf']);
    $cnpj = trim($_POST['cnpj']);
    $logradouro = trim($_POST['logradouro']);
    $numero = trim($_POST['numero']);
    $complemento = trim($_POST['complemento']);
    $bairro = trim($_POST['bairro']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);
    $cep = trim($_POST['cep']);
    $tipo_endereco = trim($_POST['tipo_endereco']);
    $data_cadastro = date("Y-m-d H:i:s");
    $data_atualizacao = date("Y-m-d H:i:s");

    $sql = "INSERT INTO clientes (
        nome, email, tipo_cliente, cpf, cnpj, logradouro, numero, complemento, bairro, cidade, estado, cep, tipo_endereco, data_cadastro, data_atualizacao
    ) VALUES (
        '$nome', '$email', '$tipo_cliente', '$cpf', '$cnpj', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$cep', '$tipo_endereco', '$data_cadastro', '$data_atualizacao'
    )";

    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0){
        $_SESSION['mensagem'] = 'Cliente criado com sucesso';
        header('location:../pages/clientes.php');
        exit;
    } else{
        $_SESSION['mensagem'] = 'Cliente não pode ser criado, contate nosso suporte';
        header('location:../pages/clientes.php');
        exit;
    }
}

if (isset($_POST['editar_cliente'])){
    $id = trim($_POST['id']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $tipo_cliente = trim($_POST['tipo_cliente']);
    $cpf = trim($_POST['cpf']);
    $cnpj = trim($_POST['cnpj']);
    $logradouro = trim($_POST['logradouro']);
    $numero = trim($_POST['numero']);
    $complemento = trim($_POST['complemento']);
    $bairro = trim($_POST['bairro']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);
    $cep = trim($_POST['cep']);
    $tipo_endereco = trim($_POST['tipo_endereco']);
    $data_atualizacao = date("Y-m-d H:i:s");

    $sql = "UPDATE clientes SET 
        nome='$nome',
        email='$email',
        tipo_cliente='$tipo_cliente',
        cpf='$cpf',
        cnpj='$cnpj',
        logradouro='$logradouro',
        numero='$numero',
        complemento='$complemento',
        bairro='$bairro',
        cidade='$cidade',
        estado='$estado',
        cep='$cep',
        tipo_endereco='$tipo_endereco',
        data_atualizacao='$data_atualizacao'
        WHERE id='$id'";

    mysqli_query($conn, $sql);
    if(mysqli_affected_rows($conn) > 0){
        $_SESSION['mensagem'] = 'Cliente editado com sucesso';
        header('location:../pages/clientes.php');
        exit;
    } else{
        $_SESSION['mensagem'] = 'Não foi possível editar o cliente';
        header('location:../pages/clientes.php');
        exit;
    }
}

