<?php
require('../crud/conexao_DB.php');
session_start();
require('../crud/verifica_tipo.php');
verifica_tipo(['gerente','vendedor','gerente_estoque']);

require('../crud/Relatorio/estoqueCRUD.php');
$estoque = new EstoqueCRUD;

// Gráfico de quantidade por item
$dadosEstoque = $estoque->estoquePorItem($conn);
$labels = json_encode($dadosEstoque['labels']);
$quantidades = json_encode($dadosEstoque['data']);

// Itens críticos
$itensCriticos = $estoque->itensAbaixoDoMinimo($conn, 5);

// Valores financeiros
$valores = $estoque->valorTotalEstoque($conn);
$grafValor = $estoque->valorPorItem($conn);
$labelsValor = json_encode($grafValor['labels']);
$dataValor = json_encode($grafValor['data']);
//log
$logs = $estoque->listarLogEstoque($conn);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Estoque</title>

    <link rel="stylesheet" href="../css/style_geral.css">
    <link rel="stylesheet" href="../css/style_dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
</head>

<body>

<?php include('../assets/navbar/navbar.php'); ?>

<div class="container py-5">
    <h2 class="mb-4 text-pink"><i class="bi bi-boxes"></i> Relatório de Estoque</h2>

    <div class="row g-4 mt-4">

        <!-- Gráfico Estoque por Item -->
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Estoque por Item</h5>
                <canvas id="grafEstoque"></canvas>
            </div>
        </div>

        <!-- Itens Críticos -->
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3 text-danger">Itens com Estoque Baixo</h5>

                <table class="table table-bordered">
                    <thead class="table-danger">
                        <tr>
                            <th>Item</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($itensCriticos)): ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhum item crítico.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($itensCriticos as $item): ?>
                                <tr>
                                    <td><?= $item['nome_item'] ?></td>
                                    <td><?= $item['quantidade'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    <hr class="my-5">

    <!-- Bloco de Valores Financeiros -->
    <h3 class="mb-4 text-pink"><i class="bi bi-cash-coin"></i> Valor Total do Estoque</h3>

    <div class="row text-center g-4 mb-5">

        <div class="col-md-4">
            <div class="card p-4 bg-light shadow-sm">
                <h5>Custo Total Investido</h5>
                <h3 class="text-danger">R$ <?= number_format($valores['total_custo'], 2, ',', '.') ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 bg-light shadow-sm">
                <h5>Valor Estimado de Venda</h5>
                <h3 class="text-primary">R$ <?= number_format($valores['total_venda'], 2, ',', '.') ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 bg-light shadow-sm">
                <h5>Lucro Potencial</h5>
                <h3 class="text-success">R$ <?= number_format($valores['lucro_potencial'], 2, ',', '.') ?></h3>
            </div>
        </div>

    </div>

    <div class="card p-4">
        <h5 class="mb-3">Itens que Mais Pesam no Custo Total</h5>
        <canvas id="grafValorEstoque"></canvas>
    </div>
        <hr class="my-5">

    <h3 class="mb-4 text-pink"><i class="bi bi-journal-text"></i> Log de Movimentações do Estoque</h3>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                        <th>Qtd. Antes</th>
                        <th>Qtd. Depois</th>
                        <th>Diferença</th>
                        <th>Movimentação</th>
                        <th>Usuário</th>
                        <th>Data/Hora</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="8" class="text-center">Nenhuma movimentação registrada.</td></tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $log['id_log'] ?></td>
                            <td><?= $log['nome_item'] ?? 'Item removido' ?></td>
                            <td><?= $log['quantidade_anterior'] ?></td>
                            <td><?= $log['quantidade_nova'] ?></td>
                            <td><?= $log['diferenca'] ?></td>
                            <td>
                                <?php if ($log['tipo_movimentacao'] === 'entrada'): ?>
                                    <span class="badge bg-success">Entrada</span>
                                <?php elseif ($log['tipo_movimentacao'] === 'saida'): ?>
                                    <span class="badge bg-danger">Saída</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= $log['tipo_movimentacao'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $log['usuario'] ?></td>
                            <td><?= $log['data_hora'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


</div>

<script>
// Gráfico de Quantidade por Item
new Chart(document.getElementById('grafEstoque'), {
    type: 'bar',
    data: {
        labels: <?= $labels ?>,
        datasets: [{
            label: 'Quantidade',
            data: <?= $quantidades ?>,
            borderWidth: 1,
            backgroundColor: 'rgba(54,162,235,0.6)',
            borderColor: 'rgb(54,162,235)'
        }]
    }
});

// Gráfico de Valor por Item
new Chart(document.getElementById('grafValorEstoque'), {
    type: 'bar',
    data: {
        labels: <?= $labelsValor ?>,
        datasets: [{
            label: 'Custo Total (R$)',
            data: <?= $dataValor ?>,
            borderWidth: 1,
            backgroundColor: 'rgba(255,99,132,0.6)',
            borderColor: 'rgb(255,99,132)'
        }]
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
