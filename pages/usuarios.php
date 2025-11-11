<?php

require('../crud/conexao_DB.php');

?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
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
                        <h4> Usuarios
                            <a href="criar_usuarios.php" class="btn btn-primary float-end"> Criar Usuario</a>
                        </h4>          
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                     <th>Usuario</th>
                     <th>Nome</th>
                     <th>Tipo usuario</th>
                     <th>E-mail</th>
                     <th>Senha</th>
                     <th>Status</th>
                     <th>Açoes</th>
                    </tr>
            </thead>
            <tbody>
                <tr>
                    <td> <?= $row['id']?> </td>
                    <td><?= $row['usuario']?></td>
                    <td><?= $row['nome']?></td>
                    <td><?= $row['tipo_usuario']?></td>
                    <td><?= $row['email']?></td>
                    <td><?= $row['senha']?></td>
                    <td><?= $row['status']?></td>
                    <td>
                        <a href="editarOusuario.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                    </td>
                </tr>









            </tbody>
        </table>


    </div>
</body>
</html>