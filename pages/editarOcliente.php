<?php
require('../crud/conexao_DB.php');
session_start();
$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente','vendedor']);
}

if (!isset($_GET['id'])){
    $_SESSION['mensagem'] = 'Cliente não informado';
    header('location:clientes.php');
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM clientes WHERE id = '$id'";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) == 0){
    $_SESSION['mensagem'] = 'Cliente não encontrado';
    header('location:clientes.php');
    exit;
}
$row = mysqli_fetch_assoc($res);
// incluir helper ViaCEP e preparar dados para preencher o formulário
include_once(__DIR__ . '/../assets/viacep/pegarCep.php');
$json_data = pegarEndereco();
// se não houve busca via POST, preencha json_data com os valores do banco
if (!isset($_POST['cep_busca'])){
    $json_data->cep = isset($row['cep']) ? $row['cep'] : '';
    $json_data->logradouro = isset($row['logradouro']) ? $row['logradouro'] : '';
    $json_data->bairro = isset($row['bairro']) ? $row['bairro'] : '';
    $json_data->localidade = isset($row['cidade']) ? $row['cidade'] : '';
    $json_data->uf = isset($row['estado']) ? $row['estado'] : '';
    $json_data->complemento = isset($row['complemento']) ? $row['complemento'] : '';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Cliente</title>
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
                        <h4>Editar Cliente</h4>
                    </div>
                    <div class="card-body">
                        <!-- formulário pequeno para buscar CEP (POST) colocado fora do form principal -->
                        <form method="post" action="<?= $_SERVER['PHP_SELF'] . '?id=' . $row['id'] ?>" class="mb-3 d-flex" style="gap:8px;">
                            <input type="text" name="cep_busca" id="cep_busca" class="form-control" placeholder="CEP para buscar" value="<?= isset($_POST['cep_busca']) ? htmlspecialchars($_POST['cep_busca']) : '' ?>">
                            <button type="submit" class="btn btn-outline-primary">Buscar CEP</button>
                        </form>

                        <form action="../crud/clientesCRUD.php" method="post">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" name="nome" id="nome" class="form-control" value="<?= $row['nome'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?= $row['email'] ?>">
                            </div>
                            <input type="hidden" name="tipo_cliente" id="tipo_cliente" value="<?= isset($row['tipo_cliente']) ? strtolower($row['tipo_cliente']) : '' ?>">
                            <div class="row">
                                <?php if (isset($row['tipo_cliente']) && strtolower($row['tipo_cliente']) === 'fisica'): ?>
                                <div class="col-md-6 mb-3">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" name="cpf" id="cpf" class="form-control" value="<?= $row['cpf'] ?>">
                                </div>
                                <?php else: ?>
                                <div class="col-md-6 mb-3">
                                    <label for="cnpj" class="form-label">CNPJ</label>
                                    <input type="text" name="cnpj" id="cnpj" class="form-control" value="<?= $row['cnpj'] ?>">
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="logradouro" class="form-label">Logradouro</label>
                                <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= htmlspecialchars($json_data->logradouro) ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <input type="text" name="numero" id="numero" class="form-control" value="<?= $row['numero'] ?>">
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control" value="<?= htmlspecialchars($json_data->complemento) ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" class="form-control" value="<?= htmlspecialchars($json_data->bairro) ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" class="form-control" value="<?= htmlspecialchars($json_data->localidade) ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" name="estado" id="estado" class="form-control" value="<?= htmlspecialchars($json_data->uf) ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" name="cep" id="cep" class="form-control" value="<?= htmlspecialchars($json_data->cep) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tipo_endereco" class="form-label">Tipo Endereço</label>
                                    <select name="tipo_endereco" id="tipo_endereco" class="form-control">
                                        <?php $tipo_end = isset($row['tipo_endereco']) ? strtolower($row['tipo_endereco']) : '' ; ?>
                                        <option value="residencial" <?= $tipo_end === 'residencial' ? 'selected' : '' ?>>Residencial</option>
                                        <option value="comercial" <?= $tipo_end === 'comercial' ? 'selected' : '' ?>>Comercial</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" name="editar_cliente" class="btn btn-primary">Salvar</button>
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

