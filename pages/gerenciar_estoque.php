<?php
session_start();
require('../crud/conexao_DB.php');
include('../crud/pesquisa_estoque.php');


$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente','vendedor','gerente_estoque']);
}

$listaEstoque = new pesquisa;
$buscaEstoque = $listaEstoque->buscaEstoque($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_geral.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <?php include('../assets/navbar/navbar.php'); ?>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-12">
                <h2 class="text-pink">Estoque de Produtos</h2>
                <a href="criar_item_estoque.php" class="btn btn-pink float-end mb-3">Adicionar Novo Item</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do Item</th>
                                    <th>Descrição</th>
                                    <th>Quantidade</th>
                                    <th>Preço de Custo</th>
                                    <th>Preço de Venda</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($buscaEstoque as $item): ?>
                                    <tr>
                                        <td><?= $item['id'] ?></td>
                                        <td><?= $item['nome_item'] ?></td>
                                        <td><?= $item['descricao'] ?></td>
                                        <td><?= $item['quantidade'] ?></td>
                                        <td>R$ <?= number_format($item['preco_custo'], 2, ',', '.') ?></td>
                                        <td>R$ <?= number_format($item['preco_venda'], 2, ',', '.') ?></td>
                                        <td>
                                            <!-- Botão Editar -->
                                            <a href="editar_item_estoque.php?id=<?= $item['id'] ?>" class="btn btn-secondary btn-sm mb-1">Editar</a>

                                            <!-- Botão Adicionar Quantidade -->
                                            <a href="adicionar_estoque.php?id=<?= $item['id'] ?>" class="btn btn-pink btn-sm mb-1">Adicionar</a>

                                            <!-- Botão Remover Quantidade -->
                                            <a href="remover_estoque.php?id=<?= $item['id'] ?>" class="btn btn-warning btn-sm mb-1">Remover</a>

                                            <!-- Botão Excluir -->
                                            <form action="../crud/estoqueCRUD.php" method="POST" class="d-inline">
                                                <button type="submit" onclick="return confirm('Deseja realmente excluir este item?')" name="deletar_item" value="<?= $item['id'] ?>" class="btn btn-danger btn-sm mb-1">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if(empty($buscaEstoque)) : ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Nenhum item cadastrado no estoque.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
