<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verifica_tipo($permitidos = []){
    if (!isset($_SESSION['id'])) {
        $_SESSION['mensagem'] = 'Por favor, faça login para acessar esta página.';
        header('Location: ../pages/login.php');
        exit();
    }

    if (empty($permitidos)) {
        return true; // sem restrição de tipo
    }

    $tipo = isset($_SESSION['tipo_usuario']) ? strtolower($_SESSION['tipo_usuario']) : null;
    $permitidos_lower = array_map('strtolower', $permitidos);
    if ($tipo === null || !in_array($tipo, $permitidos_lower)) {
        $_SESSION['mensagem'] = 'Acesso negado: perfil sem permissão.';
        header('Location: ../pages/dashboard.php');
        exit();
    }

    return true;
}

?>