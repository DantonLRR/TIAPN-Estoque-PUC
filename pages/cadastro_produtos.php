<?php
session_start();
// ==================== PROCESSAMENTO DO FORMULÁRIO ====================
// Esta parte do código será executada *antes* de o HTML ser carregado

// Verifica se o formulário foi enviado via método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Coleta os valores enviados do formulário
    $nomeProduto = $_POST["nomeProduto"];
    $sku = $_POST["sku"];
    $descricao = $_POST["descricao"];
    $categoria = $_POST["categoria"];
    $fornecedor = $_POST["fornecedor"];
    $quantidade = $_POST["quantidade"];
    $precoCusto = $_POST["precoCusto"];
    $precoVenda = $_POST["precoVenda"];

    // ==================== CONEXÃO COM O BANCO DE DADOS ====================
    // (ajuste o nome do seu banco, usuário e senha conforme seu ambiente)
    $conn = new mysqli("localhost", "root", "", "estoque_db");

    // Verifica se deu erro na conexão
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // ==================== INSERÇÃO NO BANCO ====================
    $sql = "INSERT INTO produtos 
            (nome, sku, descricao, categoria, fornecedor, quantidade, preco_custo, preco_venda)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara o comando SQL (mais seguro contra SQL Injection)
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssi dd",
        $nomeProduto,
        $sku,
        $descricao,
        $categoria,
        $fornecedor,
        $quantidade,
        $precoCusto,
        $precoVenda
    );

    // Executa o comando
    if ($stmt->execute()) {
        $mensagem = "✅ Produto cadastrado com sucesso!";
    } else {
        $mensagem = "❌ Erro ao cadastrar: " . $stmt->error;
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto - Gerenciamento de Estoque</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/visual/cadastro.css">
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            
            <div class="card card-form">
                <div class="card-header card-header-custom text-center">
                    <h2><i class="bi bi-box-seam"></i> Cadastro de Novo Produto</h2>
                </div>
                
                <div class="card-body p-4 p-md-5">

                    <!-- Exibe mensagem de sucesso/erro -->
                    <?php if (isset($mensagem)) : ?>
                        <div class="alert alert-info">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <!-- O enctype abaixo permite enviar arquivos (imagens) -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="nomeProduto" class="form-label">Nome do Produto *</label>
                                <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="sku" class="form-label">SKU / Código *</label>
                                <input type="text" class="form-control" id="sku" name="sku" placeholder="ex: PROD-001" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição do Produto</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Detalhes, especificações..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">Categoria *</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="" selected disabled>Selecione uma categoria...</option>
                                    <option value="calças">Calças</option>
                                    <option value="bermudas">Bermudas</option>
                                    <option value="óculos">Óculos</option>
                                    <option value="calçados">Calçados</option>
                                    <option value="camiseta">Camiseta</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fornecedor" class="form-label">Fornecedor</label>
                                <input type="text" class="form-control" id="fornecedor" name="fornecedor">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="quantidade" class="form-label">Qtd. em Estoque *</label>
                                <input type="number" class="form-control" id="quantidade" name="quantidade" value="0" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="precoCusto" class="form-label">Preço de Custo (R$)</label>
                                <input type="number" class="form-control" id="precoCusto" name="precoCusto" step="0.01" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="precoVenda" class="form-label">Preço de Venda (R$) *</label>
                                <input type="number" class="form-control" id="precoVenda" name="precoVenda" step="0.01" min="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="imagemProduto" class="form-label">Imagem do Produto</label>
                            <input class="form-control" type="file" id="imagemProduto" name="imagemProduto" accept="image/png, image/jpeg">
                        </div>

                        <hr>

                        <div class="form-buttons text-end">
                            <button type="reset" class="btn btn-secondary me-2">
                                <i class="bi bi-x-lg"></i> Limpar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Cadastrar Produto
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
