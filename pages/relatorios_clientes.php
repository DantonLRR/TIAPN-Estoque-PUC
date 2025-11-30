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
    <title>Relatório de Clientes - Gestão de Roupas</title>

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
        <h2 class="mb-4 text-pink"><i class="bi bi-people"></i> Relatório de Clientes</h2>
        <p>Acompanhe o perfil dos clientes da sua loja de roupas.</p>

        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3">Clientes por Tipo de Cadastro</h5>
                    <canvas id="clientesTipo"></canvas>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3">Clientes por Localidade</h5>
                    <canvas id="clientesLocalidade"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
    window.onload = function() {
        const totalClientes = 180 + 40;
        const percentPF = (180 / totalClientes * 100).toFixed(1);
        const percentPJ = (40 / totalClientes * 100).toFixed(1);

        const ctxTipo = document.getElementById('clientesTipo').getContext('2d');
        new Chart(ctxTipo, {
            type: 'bar',
            data: {
                labels: ['Pessoa Física', 'Pessoa Jurídica'],
                datasets: [{
                    label: 'Clientes (%)',
                    data: [percentPF, percentPJ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: '#333', callback: function(value) { return value + '%'; } }
                    },
                    y: {
                        ticks: { color: '#333' }
                    }
                }
            }
        });

        const ctxLocalidade = document.getElementById('clientesLocalidade').getContext('2d');
        new Chart(ctxLocalidade, {
            type: 'doughnut',
            data: {
                labels: ['São Paulo', 'Rio de Janeiro', 'Minas Gerais', 'Outros'],
                datasets: [{
                    label: 'Número de Clientes',
                    data: [70, 50, 60, 40],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: '#d63384' } } }
            }
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
