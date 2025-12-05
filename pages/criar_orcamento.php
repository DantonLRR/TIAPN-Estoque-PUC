<?php
session_start();
// criar a tela de cadastro de orçamento.
require('../crud/conexao_DB.php');
include('../crud/pesquisa_estoque.php'); // Crie uma classe semelhante à de orçamentos
include('../crud/pesquisa_clientes.php');
$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente', 'vendedor']);
}
$listaEstoque = new pesquisaEstoque;
$buscaEstoque = $listaEstoque->buscaEstoque($conn);

$Clientes = new pesquisa;
$listaClientes = $Clientes->buscarClientesSimples($conn);


$vendedorLogado = $_SESSION['user'];

?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar orçamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style_geral.css">
</head>

<body>
    <?php include('../assets/navbar/navbar.php'); ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Criar Orçamento
                            <a href="orcamentos.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="../crud/orcamentoCRUD.php" method="POST">
                            <div class="mb-3">
                                <label for="cliente">Cliente:</label>
                                <select name="cliente" id="cliente" class="form-control" required>
                                    <option value="">Selecione...</option>

                                    <?php foreach ($listaClientes as $c): ?>
                                        <option
                                            value="<?= $c['id'] ?>"
                                            data-tipo="<?= strtolower($c['tipo_cliente']) ?>"
                                            data-cpf="<?= $c['cpf'] ?>"
                                            data-cnpj="<?= $c['cnpj'] ?>">
                                            <?= $c['nome'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="mb-3 mt-2">
                                    <label>CPF/CNPJ:</label>
                                    <input type="text" id="doc_cliente" name="doc_cliente" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="vendedor">Vendedor:</label>
                                    <input type="text" name="vendedor" class="form-control"
                                        value="<?= $vendedorLogado ?>" readonly>
                                </div>

                                <!-- <div class="mb-3">
                                    <label for="item">Item:</label>
                                    <select name="item" id="item" class="form-control" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($buscaEstoque as $item): ?>
                                            <option value="<?= $item['id'] ?>" data-estoque="<?= $item['quantidade'] ?>">
                                                <?= $item['nome_item'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> -->
                                <div class="mb-3">
                                    <label for="item">Item:</label>
                                    <select name="item" id="item" class="form-control" required>
                                        <option value="">Selecione...</option>

                                        <?php foreach ($buscaEstoque as $item): ?>
                                            <option
                                                value="<?= $item['id'] ?>"
                                                data-estoque="<?= $item['quantidade'] ?>"
                                                data-preco="<?= $item['preco_venda'] ?>">
                                                <?= $item['nome_item'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Preço unitário (R$):</label>
                                    <input type="text" id="preco_item" class="form-control" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="quantidade">Quantidade:</label>
                                    <select name="quantidade" id="quantidade" class="form-control" required>
                                        <option value="">Selecione o item primeiro...</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="descricao"> Descrição:</label>
                                    <div>
                                        <textarea name="descricao" id="" maxlength="255" rows="4" cols="50"
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="valor"> Valor orçado:</label>
                                    <input type="text" id="valor" name="valor" class="form-control" required>
                                </div>
                                <?php include('../assets/mensagem/mensagem.php'); ?>
                                <div class="mb-3">
                                    <button type="submit" name="criar_orcamento" class="btn btn-primary"> Salvar </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script src="../js/orcamento.js"></script>
</body>

</html>