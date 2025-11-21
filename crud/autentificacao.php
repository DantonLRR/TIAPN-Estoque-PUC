<?php
include('../crud/conexao_DB.php');
session_start();

if(isset($_POST['usuario']) || isset($_POST['senha'])){

    if(strlen($_POST['usuario'])==0){
        echo"Preencha seu usuario";
    }else if(strlen($_POST['senha']) == 0){
        echo"Preencha sua senha";
    }else{

        $usuario = $conn->real_escape_string($_POST['usuario']);
        $senha = $conn->real_escape_string($_POST['senha']);
    
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha' ";
        $sql_query = $conn->query($sql) or die("Falha ao conectar:" . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1){

            $usuario = $sql_query->fetch_assoc();

            if ($usuario['status'] != 1) {
                $_SESSION['mensagem'] = "Seu usuário está inativo. Contate o administrador.";
                header("Location: ../pages/login.php");
                exit();
            }            

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['user'] = $usuario['nome'];

            header("Location: ../pages/dashboard.php");
            exit();
            
        }else{
            $_SESSION['mensagem'] = "Usuario ou senha incorretos";
            header('location:../pages/login.php');
            exit();
        }
        if ($usuario['status'] != 1) {
        echo "Seu usuário está inativo. Entre em contato com o administrador.";
        exit();
        }
    }
}


?>