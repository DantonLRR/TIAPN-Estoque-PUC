<?php
session_start();
include('conexao_DB.php');

if (isset($_POST['usuario']) && isset($_POST['senha'])) {

    $usuario = $conn->real_escape_string($_POST['usuario']);
    $senha = $conn->real_escape_string($_POST['senha']);

    if (empty($usuario) || empty($senha)) {
        echo "Preencha todos os campos!";
        exit();
    }

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $dados_usuario = $result->fetch_assoc();
        $_SESSION['id'] = $dados_usuario['id'];
        $_SESSION['nome'] = $dados_usuario['nome'];

        header("Location: ../dashboard.php");
        exit();
    } else {
        // Redireciona de volta com alerta de erro
        header("Location: ../paginas/login.php?erro=1");
        exit();
    }
} else {
    echo "Erro: Requisição inválida!";
}
