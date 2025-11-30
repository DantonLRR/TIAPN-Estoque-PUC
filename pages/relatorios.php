<?php
require('../crud/conexao_DB.php');
session_start();
$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente','vendedor','gerente_estoque']);
}

include('../crud/pesquisa_usuarios.php');
$listaUsuario = new pesquisa;
$buscarUsuarios = $listaUsuario->buscarUsuarios($conn);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Área do Usuário</title>

    <link rel="stylesheet" href="../css/style_geral.css">
    <link rel="stylesheet" href="../css/style_dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>

    <?php include('../assets/navbar/navbar.php'); ?>

    <div class="container py-5">

        <h2 class="mb-4 text-pink"><i class="bi bi-graph-up-arrow"></i> Relatórios</h2>
        <p>Acompanhe os principais indicadores, desempenho do estoque, vendas e métricas da sua padaria.</p>

        <div class="row g-4 mt-4">

            <!-- KPI: Vendas -->
            <div class="col-md-4">
                <div class="card text-center p-4 card-action">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <h5>Relatório de Vendas</h5>
                    <p>Veja gráficos e dados sobre o volume de vendas e faturamento.</p>
                    <a href="relatorios_vendas.php" class="btn btn-pink mt-2">Ver Relatório</a>
                </div>
            </div>

            <!-- KPI: Estoque -->
            <div class="col-md-4">
                <div class="card text-center p-4 card-action">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5>Relatório de Estoque</h5>
                    <p>Analise níveis de estoque, rupturas e produtos com baixa rotatividade.</p>
                    <a href="relatorios_estoque.php" class="btn btn-pink mt-2">Ver Relatório</a>
                </div>
            </div>

            <!-- KPI: Clientes -->
            <div class="col-md-4">
                <div class="card text-center p-4 card-action">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5>Relatório de Clientes</h5>
                    <p>Acompanhe comportamento de compra e níveis de satisfação (NPS).</p>
                    <a href="relatorios_clientes.php" class="btn btn-pink mt-2">Ver Relatório</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
