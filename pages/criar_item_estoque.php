<?php
session_start();
// Criar tela de cadastro de item no estoque
require('../crud/conexao_DB.php');

$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente','vendedor','gerente_estoque']);
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Item no Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_geral.css">
</head>
 
<body>
    <?php include('../assets/navbar/navbar.php'); ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Criar Novo Item
                            <a href="gerenciar_estoque.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="../crud/estoqueCRUD.php" method="POST">
                            <div class="mb-3">
                                <label for="nome_item">Nome do Item:</label>
                                <input type="text" name="nome_item" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="descricao">Descrição:</label>
                                <textarea name="descricao" rows="4" class="form-control" maxlength="255"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="quantidade">Quantidade Inicial:</label>
                                <input type="number" name="quantidade" class="form-control" min="0" value="0" required>
                            </div>

                            <div class="mb-3">
                                <label for="preco_custo">Preço de Custo:</label>
                                <input type="text" name="preco_custo" class="form-control" value="0.00" required>
                            </div>

                            <div class="mb-3">
                                <label for="preco_venda">Preço de Venda:</label>
                                <input type="text" name="preco_venda" class="form-control" value="0.00" required>
                            </div>
                            <?php include('../assets/mensagem/mensagem.php'); ?>

                            <div class="mb-3">
                                <button type="submit" name="criar_item" class="btn btn-pink">Salvar Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>