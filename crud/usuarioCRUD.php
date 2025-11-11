<?php
session_start();
require('conexao_DB.php');

if (isset($_POST['criar_usuario.php'])){
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $data_cadastro = date("Y-m-d H:i:s");
    $data_atualizacao = date("Y-m-d H:i:s");
    $status = 0;  


$sql = "INSERT INTO usuarios(nome,usuario,email,senha,data_cadastro,data_atualizacao,status)
                    VALUES('$nome','$usuario','email','senha','$data_cadastro','$data_atualizacao','$status')";

mysqli_query($conn, $sql);
if (mysqli_affected_rows($conn) > 0){

    $_SESSION['mensagem'] = 'Usuario criado com sucesso';
    header('location:../pages/criar_usuario.php');
    exit;
}else{
    $_SESSION['mensagem'] = 'Usuario não pode ser criado, contate nosso suporte';
        header('location:../pages/criar_usuario.php');
    exit;
}
}

if (isset($_POST['editar_usuario'])){
$id = trim($_POST['id']);
$usuario = trim($_POST['usuario']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);
$data_atualizacao = date("Y-m-d H:i:s");
$status = 0;


$sql = "UPDATE usuarios SET usuario='$usuario',email='$email',senha='$senha',data_atualizacao='$data_atualizacao' WHERE id='$id'";

mysqli_query($conn, $sql);
if(mysqli_affected_rows($conn) > 0){
    $_SESSION['mensagem'] = 'Usuario editado com Sucesso';
    header('location:../index.php');
    exit;
} else{
    $_SESSION['mensagem'] = "Não foi possivel editar o usuario";
    header('location:../index.php');
    exit;
}
}

if(isset($_POST['deletar_usuario'])){
    $id = trim($_POST['deletar_usuario']);
    $sql = "DELETE FROM usuario WHERE id='$id'";
    mysqli_query($conn,$sql);
    if(mysqli_affected_rows($conn) > 0){
        $_SESSION['mensagem'] = "Usuario excluido com sucesso";
        header('location:../index.php');
        exit;
    }else{
        $_SESSION['mensagem'] = "Não foi posivel excluir o usuario";
        header('location:../index.php')
    }
}