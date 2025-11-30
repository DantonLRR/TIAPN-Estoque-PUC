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
    <title>Relatório de Vendas - Gestão de Estoque de Roupas</title>

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
        <h2 class="mb-4 text-pink"><i class="bi bi-cash-coin"></i> Relatório de Vendas</h2>
        <p>Acompanhe o desempenho de vendas e margem da sua loja de roupas com gráficos interativos.</p>

        <div class="row g-4 mt-4">


            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3">Vendas Mensais</h5>
                    <canvas id="vendasMensais"></canvas>
                </div>
            </div>


            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3">Roupas Mais Vendidas</h5>
                    <canvas id="produtosMaisVendidos"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script>
    window.onload = function() {
     
        const ctxVendasMensais = document.getElementById('vendasMensais').getContext('2d');
        new Chart(ctxVendasMensais, {
            type: 'line',
            data: {
                labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
                datasets: [
                    {
                        label: 'Preço de Venda (R$)',
                        data: [12000, 13500, 12500, 14500, 13800, 15500, 16000, 17000, 16500, 18000, 17500, 19000],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Preço de Custo (R$)',
                        data: [8000, 9000, 8500, 9500, 9000, 10000, 10500, 11000, 10700, 11500, 11200, 12000],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: '#d63384' } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#333' } },
                    x: { ticks: { color: '#333' } }
                }
            }
        });


        const ctxProdutos = document.getElementById('produtosMaisVendidos').getContext('2d');
        new Chart(ctxProdutos, {
            type: 'bar',
            data: {
                labels: ['Camiseta', 'Calça Jeans', 'Vestido', 'Jaqueta', 'Saia'],
                datasets: [{
                    label: 'Quantidade Vendida',
                    data: [150, 80, 70, 60, 50],
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
