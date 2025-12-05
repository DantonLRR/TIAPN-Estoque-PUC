<?php
require('../crud/conexao_DB.php');
session_start();
$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente','vendedor','gerente_estoque']);
}

require('../crud/Relatorio/clientesCRUD.php');

$clientesCRUD = new ClientesCRUD();

// Dados reais do banco
$totaisTipo = $clientesCRUD->contarPorTipo($conn);
$totaisEstado = $clientesCRUD->contarPorEstado($conn);

// Preparar JS
$pf = $totaisTipo['fisica'] ?? 0;
$pj = $totaisTipo['juridica'] ?? 0;

// Localidades (estados)
$labelsEstado = json_encode(array_keys($totaisEstado));
$dadosEstado = json_encode(array_values($totaisEstado));
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
        <!-- CLIENTES POR TIPO -->
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Clientes por Tipo de Cadastro</h5>
                <canvas id="clientesTipo"></canvas>
            </div>
        </div>

        <!-- CLIENTES POR ESTADO -->
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Clientes por Estado</h5>
                <canvas id="clientesEstado"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
window.onload = function() {

    // ====== Dados vindos do PHP =======
    const pf = <?= $pf ?>;
    const pj = <?= $pj ?>;
    const labelsEstado = <?= $labelsEstado ?>;
    const dadosEstado = <?= $dadosEstado ?>;

    // ====== GRÁFICO TIPO DE CLIENTE ======
    const ctxTipo = document.getElementById('clientesTipo').getContext('2d');
    new Chart(ctxTipo, {
        type: 'bar',
        data: {
            labels: ['Pessoa Física', 'Pessoa Jurídica'],
            datasets: [{
                label: 'Quantidade',
                data: [pf, pj],
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
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });

    // ====== GRÁFICO POR ESTADO ======
    const ctxEstado = document.getElementById('clientesEstado').getContext('2d');
    new Chart(ctxEstado, {
        type: 'doughnut',
        data: {
            labels: labelsEstado,
            datasets: [{
                label: 'Clientes',
                data: dadosEstado,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderWidth: 1
            }]
        }
    });
};
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
