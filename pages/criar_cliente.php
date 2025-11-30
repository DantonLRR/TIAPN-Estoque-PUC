<?php
require('../crud/conexao_DB.php');
session_start();
$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente','vendedor']);
}

include_once(__DIR__ . '/../assets/viacep/pegarCep.php');
$json_data = pegarEndereco();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_geral.css">
</head>
<body>
    <?php include('../assets/navbar/navbar.php'); ?>
    <div class="container mt-4">
        <?php include('../assets/mensagem/mensagem.php'); ?>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Criar Cliente</h4>
                    </div>
                    <div class="card-body">
                        <?php
                       
                        $tipo = isset($_GET['tipo']) ? strtolower($_GET['tipo']) : 'fisica';
                        ?>
                        <form method="get" class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Pessoa (mostrar campo)</label>
                            <div class="d-flex gap-2">
                                <select name="tipo" id="tipo" class="form-control" style="max-width:300px;">
                                    <option value="fisica" <?= $tipo === 'fisica' ? 'selected' : '' ?>>Pessoa Física</option>
                                    <option value="juridica" <?= $tipo === 'juridica' ? 'selected' : '' ?>>Pessoa Jurídica</option>
                                </select>
                                <button class="btn btn-outline-secondary" type="submit">Aplicar</button>
                            </div>
                        </form>

                        <!-- formulário pequeno para buscar CEP (POST) colocado fora do form principal para evitar aninhamento -->
                        <form method="post" action="<?= $_SERVER['PHP_SELF'] . '?tipo=' . $tipo ?>" class="mb-3 d-flex" style="gap:8px;">
                            <input type="text" name="cep_busca" id="cep_busca" class="form-control" placeholder="CEP para buscar" value="<?= isset($_POST['cep_busca']) ? htmlspecialchars($_POST['cep_busca']) : '' ?>">
                            <button type="submit" class="btn btn-outline-primary">Buscar CEP</button>
                        </form>

                        <form action="../crud/clientesCRUD.php" method="post">
                            <input type="hidden" name="tipo_cliente" value="<?= $tipo ?>">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" name="nome" id="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <?php if ($tipo === 'fisica'): ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" name="cpf" id="cpf" class="form-control" required>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cnpj" class="form-label">CNPJ</label>
                                    <input type="text" name="cnpj" id="cnpj" class="form-control" required>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label for="logradouro" class="form-label">Logradouro</label>
                                <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?php echo $json_data->logradouro?>">
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <input type="text" name="numero" id="numero" class="form-control">
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" class="form-control" value="<?php echo $json_data->bairro?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" class="form-control" value="<?php echo isset($json_data->localidade) ? $json_data->localidade : '' ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" name="estado" id="estado" class="form-control" value="<?php echo isset($json_data->uf) ? $json_data->uf : '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" name="cep" id="cep" class="form-control" value="<?php echo isset($json_data->cep) ? $json_data->cep : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tipo_endereco" class="form-label">Tipo Endereço</label>
                                    <select name="tipo_endereco" id="tipo_endereco" class="form-control">
                                        <?php $tipo_endereco_selected = isset($_POST['tipo_endereco']) ? strtolower($_POST['tipo_endereco']) : 'residencial'; ?>
                                        <option value="residencial" <?= $tipo_endereco_selected === 'residencial' ? 'selected' : '' ?>>Residencial</option>
                                        <option value="comercial" <?= $tipo_endereco_selected === 'comercial' ? 'selected' : '' ?>>Comercial</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" name="criar_cliente" class="btn btn-primary">Criar</button>
                            <a href="clientes.php" class="btn btn-secondary">Voltar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
