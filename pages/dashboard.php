<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}


include('../crud/conexao_DB.php');

function showCard($allowed = []){
    if (empty($allowed)) return true;
    if (!isset($_SESSION['tipo_usuario'])) return false;
    $tipo = strtolower($_SESSION['tipo_usuario']);
    $allowed_lower = array_map('strtolower', $allowed);
    return in_array($tipo, $allowed_lower);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Área do Usuário</title>
    <link rel="stylesheet" href="../css/style_geral.css">
    <link rel="stylesheet" href="../css/style_dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     <?php include('../assets/navbar/navbar.php');  ?>

    <div class="container py-5">
        <h2 class="mb-4 text-pink">Bem-vindo, <?= $_SESSION['user'] ?>!</h2>
        <p>Escolha uma das opções abaixo para continuar:</p>

        <div class="row g-4 mt-4">
            <?php if (showCard(['gerente','vendedor'])): ?>
            <div class="col-md-4">
                <a href="orcamentos.php" class="card-action text-decoration-none">
                    <div class="card text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-file-earmark-text"></i></div>
                        <h5>Criar Orçamento</h5>
                        <p>Crie novos orçamentos rapidamente e organize suas vendas.</p>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (showCard(['gerente','vendedor','gerente_estoque'])): ?>
            <div class="col-md-4">
                <a href="gerenciar_estoque.php" class="card-action text-decoration-none">
                    <div class="card text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-box-seam"></i></div>
                        <h5>Gerenciar Estoque</h5>
                        <p>Visualize e atualize o estoque de produtos disponíveis.</p>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (showCard(['gerente','vendedor','gerente_estoque'])): ?>
            <div class="col-md-4">
                <a href="relatorios.php" class="card-action text-decoration-none">
                    <div class="card text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-graph-up"></i></div>
                        <h5>Relatórios</h5>
                        <p>Analise relatórios e métricas do seu sistema facilmente.</p>
                    </div>
                </a>
            </div>
            <?php endif; ?>
            <?php if (showCard(['gerente'])): ?>
            <div class="col-md-4">
               <a href="usuarios.php" class="card-action text-decoration-none">
                   <div class="card text-center p-4">
                       <div class="feature-icon mb-3"><i class="bi bi-graph-up"></i></div>
                       <h5>Usuarios</h5>
                       <p>Gerencie o usuario dos seus funcionarios.</p>
                   </div>
               </a>
           </div>
           <?php endif; ?>
            <?php if (showCard(['gerente','vendedor'])): ?>
            <div class="col-md-4">
                <a href="clientes.php" class="card-action text-decoration-none">
                    <div class="card text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-graph-up"></i></div>
                        <h5>Clientes</h5>
                        <p>Gerencie os seus clientes.</p>
                    </div>   
            </div>
            <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
