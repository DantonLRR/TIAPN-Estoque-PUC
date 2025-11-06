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

}
$sql = "INSERT INTO usuarios(nome,usuario,email,senha,data_cadastro,data_atualizacao,status)VALUES() "