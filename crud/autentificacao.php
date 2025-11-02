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
            

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['user'] = $usuario['nome'];

            header("Location: ../pages/dashboard.php");
            exit();
            
        }else{
            $_SESSION['mensagem'] = "Usuario ou senhas incorretos";
            header('location:../pages/login.php');
            exit();
            echo"Falha ao logar! E-mail ou senha incorretos";

        }

    }
}
?>