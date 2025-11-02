<?php
//session_start();
//if (!isset($_SESSION['user'])) {
//    header("Location: index.php");
//    exit();
//}
$_SESSION['user'] = "Danton" ;

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
            <div class="col-md-4">
                <a href="orcamentos.php" class="card-action text-decoration-none">
                    <div class="card text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-file-earmark-text"></i></div>
                        <h5>Criar Orçamento</h5>
                        <p>Crie novos orçamentos rapidamente e organize suas vendas.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="gerenciar_estoque.php" class="card-action text-decoration-none">
                    <div class="card text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-box-seam"></i></div>
                        <h5>Gerenciar Estoque</h5>
                        <p>Visualize e atualize o estoque de produtos disponíveis.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="relatorios.php" class="card-action text-decoration-none">
                    <div class="card text-center p-4">
                        <div class="feature-icon mb-3"><i class="bi bi-graph-up"></i></div>
                        <h5>Relatórios</h5>
                        <p>Analise relatórios e métricas do seu sistema facilmente.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
