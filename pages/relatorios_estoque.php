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
    <title>Relatório de Estoque - Gestão de Roupas</title>

    <link rel="stylesheet" href="../css/style_geral.css">
    <link rel="stylesheet" href="../css/style_dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    <style>
        canvas {
            width: 100% !important;
            height: 300px !important;
        }
    </style>
</head>

<body>
    <?php include('../assets/navbar/navbar.php'); ?>

    <div class="container py-5">
        <h2 class="mb-4 text-pink"><i class="bi bi-box-seam"></i> Relatório de Estoque</h2>
        <p>Acompanhe o estoque atual das roupas da sua loja.</p>

        <div class="row g-4 mt-4">
            <div class="col-md-12">
                <div class="card p-4">
                    <h5 class="mb-3">Estoque Atual por Categoria</h5>
                    <canvas id="estoqueCategoria"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
    window.onload = function() {
        const ctxEstoqueCategoria = document.getElementById('estoqueCategoria').getContext('2d');
        new Chart(ctxEstoqueCategoria, {
            type: 'bar',
            data: {
                labels: ['Camisetas', 'Calças Jeans', 'Vestidos', 'Jaquetas', 'Saias'],
                datasets: [{
                    label: 'Quantidade em Estoque',
                    data: [150, 80, 60, 40, 50],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#333' } },
                    x: { ticks: { color: '#333' } }
                }
            }
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
