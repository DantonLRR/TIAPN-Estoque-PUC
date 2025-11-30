<?php
require('../crud/conexao_DB.php');
session_start();
$require_path = '../crud/verifica_tipo.php';
if (file_exists(__DIR__ . '/../crud/verifica_tipo.php')) {
    require($require_path);
    verifica_tipo(['gerente','vendedor']);
}



include('../crud/pesquisa_clientes.php');
$listaCliente = new pesquisa;
$buscarClientes = $listaCliente->buscarClientes($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_geral.css">
</head>
<body>
    <?php include('../assets/navbar/navbar.php');  ?>
    <div class="container mt-4">
        <?php include('../assets/mensagem/mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4> Clientes
                            <a href="criar_cliente.php" class="btn btn-primary float-end"> Criar Cliente</a>
                        </h4>
                    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>CPF/CNPJ</th>
                    <th>E-mail</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>Data Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
               foreach($buscarClientes as $row):
                    ?>
                <tr>
                    <td> <?= $row['id']?> </td>
                    <td><?= $row['nome']?></td>
                    <td><?= isset($row['tipo_cliente']) ? $row['tipo_cliente'] : '' ?></td>
                    <td><?= (!empty($row['cpf'])) ? $row['cpf'] : $row['cnpj'] ?></td>
                    <td><?= $row['email']?></td>
                    <td><?= $row['cidade']?></td>
                    <td><?= $row['estado']?></td>
                    <td><?= $row['data_cadastro']?></td>
                    <td>
                        <a href="editarOcliente.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    </td>
                </tr>
                <?php
                endforeach
                ?>
            </tbody>
        </table>
                   </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
