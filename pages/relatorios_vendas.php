<?php
require('../crud/conexao_DB.php');
session_start();
require('../crud/verifica_tipo.php');
verifica_tipo(['gerente','vendedor','gerente_estoque']);

require('../crud/Relatorio/vendasCRUD.php');
$vendas = new VendasCRUD;

// Dados reais
$mensal = $vendas->vendasMensais($conn);
$produtos = $vendas->produtosMaisVendidos($conn);

// Transforma arrays do PHP → JS
$vendasMensais = json_encode(array_values($mensal['vendas']));
$custosMensais = json_encode(array_values($mensal['custos']));
$labelsProdutos = json_encode($produtos['labels']);
$dataProdutos = json_encode($produtos['data']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas</title>

    <link rel="stylesheet" href="../css/style_geral.css">
    <link rel="stylesheet" href="../css/style_dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
</head>

<body>
<?php include('../assets/navbar/navbar.php'); ?>

<div class="container py-5">
    <h2 class="mb-4 text-pink"><i class="bi bi-cash-coin"></i> Relatório de Vendas</h2>

    <div class="row g-4 mt-4">

        <!-- Gráfico Vendas Mensais -->
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Vendas Mensais</h5>
                <canvas id="grafVendas"></canvas>
            </div>
        </div>

        <!-- Gráfico Produtos mais vendidos -->
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Produtos Mais Vendidos</h5>
                <canvas id="grafProdutos"></canvas>
            </div>
        </div>

    </div>
</div>

<script>
const vendasMensais = <?= $vendasMensais ?>;
const custosMensais = <?= $custosMensais ?>;
const labelsProdutos = <?= $labelsProdutos ?>;
const dataProdutos = <?= $dataProdutos ?>;

new Chart(document.getElementById('grafVendas'), {
    type: 'line',
    data: {
        labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        datasets: [
            {
                label: 'Valor Vendido (R$)',
                data: vendasMensais,
                borderWidth: 2,
                borderColor: 'rgb(54,162,235)',
                tension: 0.3
            },
            {
                label: 'Custo dos Produtos (R$)',
                data: custosMensais,
                borderWidth: 2,
                borderColor: 'rgb(255,99,132)',
                tension: 0.3
            }
        ]
    }
});

new Chart(document.getElementById('grafProdutos'), {
    type: 'bar',
    data: {
        labels: labelsProdutos,
        datasets: [{
            label: 'Quantidade Vendida',
            data: dataProdutos,
            borderWidth: 1,
            backgroundColor: 'rgba(255,99,132,0.6)'
        }]
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
