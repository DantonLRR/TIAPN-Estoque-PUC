<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require('conexao_DB.php');

if (isset($_POST['criar_usuario'])){
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $tipo_usuario = trim($_POST['tipo_usuario']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $data_cadastro = date("Y-m-d H:i:s");
    $data_atualizacao = date("Y-m-d H:i:s");
    $status = trim($_POST['status']);


$sql = "INSERT INTO usuarios(nome,usuario,tipo_usuario,email,senha,data_cadastro,data_atualizacao,status)
                    VALUES('$nome','$usuario','$tipo_usuario','$email','$senha','$data_cadastro','$data_atualizacao','$status')";

mysqli_query($conn, $sql);
if (mysqli_affected_rows($conn) > 0){

    $_SESSION['mensagem'] = 'Usuario criado com sucesso';
    header('location:../pages/usuarios.php');
    exit;
}else{
    $_SESSION['mensagem'] = 'Usuario não pode ser criado, contate nosso suporte';
    header('location:../pages/usuarios.php');
    exit;
}
}

if (isset($_POST['editar_usuario'])){
$id = trim($_POST['id']);
$usuario = trim($_POST['usuario']);
$tipo_usuario = trim($_POST['tipo_usuario']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);
$data_atualizacao = date("Y-m-d H:i:s");
$status = trim($_POST['status']);


$sql = "UPDATE usuarios SET usuario='$usuario',tipo_usuario='$tipo_usuario',email='$email',senha='$senha',data_atualizacao='$data_atualizacao',status='$status' WHERE id='$id'";

mysqli_query($conn, $sql);
if(mysqli_affected_rows($conn) > 0){
    $_SESSION['mensagem'] = 'Usuario editado com Sucesso';
    header('location:../pages/usuarios.php');
    exit;
} else{
    $_SESSION['mensagem'] = "Não foi possivel editar o usuario";
    header('location:../pages/usuarios.php');
    exit;
}
}
