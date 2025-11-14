<?php
session_start();
// criar a tela de cadastro de orçamento. A tela deve permitir também a edição.
//Criar uma tela de pesquisa de orçamento
//A tela de pesquisa deverá ter filtro por intervalo de datas, nome do cliente e nome do vendedor. 
//A tela deverá conter uma tabela com os dados já filtrados e listados por ordem decrescente da data de cadastro do orçamento
//A tela deverá conter as ações de editar e remover os orçamentos realizados

require('crud/conexao_DB.php');
include('crud/pesquisa_orcamentos.php');
$listaOrcamento = new pesquisa;
$clientePesquisa = $_POST['cliente'];
$vendedor = $_POST['vendedor'];
$dta_inicial = $_POST['dta_inicial'];
$dta_final = $_POST['dta_final'] ;
$buscaOrcamentos = $listaOrcamento->pesquisaDeOrcamento($conn, $clientePesquisa, $vendedor, $dta_inicial, $dta_final);
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar orçamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>
    <?php include('assets/navbar/navbar.php');  ?>
    <?php include('assets/CampoDePesquisa/formPesquisa.php');?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Pesquisa de orçamento
                            <a href="index.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Data e Hora</th>
                                    <th>vendedor</th>
                                    <th>descriçao</th>
                                    <th>valor orçado</th>
                                    <th>Açoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($buscaOrcamentos as $row):
                                ?>
                                    <tr>
                                        <td> <?= $row['id'] ?></td>
                                        <td> <?= $row['cliente'] ?></td>
                                        <td> <?= date('Y-m-d H:i:s', strtotime($row['dta_hora_orcamento'])) ?></td>
                                        <td> <?= $row['vendedor'] ?></td>
                                        <td> <?= $row['descricao'] ?></td>
                                        <td> <?= $row['valor_orcado'] ?></td>
                                        <td>
                                            <a href="editarOrcamento.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Editar</a>
                                            <form action="crud/orcamentoCRUD.php" method="POST" class="d-inline">
                                                <button type="submit" onclick="return confirm('Deseja realmente excuir este orçamento?')" name="deletar_orcamento" value="<?= $row['id'] ?>" class="btn btn-danger btn-sm"> Excluir</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>