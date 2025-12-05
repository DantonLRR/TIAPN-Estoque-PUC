<?php
session_start();
require('../crud/conexao_DB.php');
include('../crud/pesquisa_orcamentos.php');


$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente', 'vendedor']);
}

$listaOrcamento = new pesquisa;
$buscaOrcamentos = $listaOrcamento->buscaOrcamentos($conn);


?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orçamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style_geral.css">
</head>

<body>
    <?php include('../assets/navbar/navbar.php'); ?>
    <div class="container mt-4">
        <?php include('../assets/mensagem/mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4> Orçamentos
                            <a href="criar_orcamento.php" class="btn btn-pink float-end"> Criar Orçamento</a>
                        </h4>

                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Item</th>
                                    <th>Quantidade</th>
                                    <th>Valor orçado</th>
                                    <th>Data e Hora</th>
                                    <th>Vendedor</th>
                                    <th>Descriçao</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($buscaOrcamentos as $row):
                                ?>
                                    <tr>
                                        <td> <?= $row['id'] ?></td>
                                        <td> <?= $row['cliente'] ?></td>
                                        <td> <?= $row['nome_item'] ?></td>
                                        <td> <?= $row['quantidade'] ?></td>
                                        <td> <?= $row['valor_orcado'] ?></td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($row['dta_hora_orcamento'])) ?></td>
                                        <td> <?= $row['vendedor'] ?></td>
                                        <td> <?= $row['descricao'] ?></td>
                                        <td> <?= $row['status'] ?></td>
                                        <td>
                                            <form action="../crud/orcamentoCRUD.php" method="POST" class="d-inline">
                                                <button type="submit"
                                                    onclick="return confirm('Deseja realmente excuir este orçamento?')"
                                                    name="cancelar_orcamento" value="<?= $row['id'] ?>"
                                                    class="btn btn-danger btn-sm"> Cancelar</button>
                                            </form>
                                            <form action="../crud/orcamentoCRUD.php" method="POST" class="d-inline">
                                                <button type="submit"
                                                    onclick="return confirm('Deseja realmente aprovar este orçamento?')"
                                                    name="aprovar_orcamento" value="<?= $row['id'] ?>"
                                                    class="btn btn-success btn-sm"> Aprovar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>