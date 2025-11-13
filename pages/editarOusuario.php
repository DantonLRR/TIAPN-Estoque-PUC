<?php

include('../crud/pesquisa_usuarios.php');
include('../crud/usuarioCRUD.php');

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
    <?php include('../assets/navbar/navbar.php');  ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Editar Usuario
                            <a href="index.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if(isset($_GET['id'])){
                           $usuario_id = $_GET['id'];
                           $PesquisaUsuario = new pesquisa();
                           $pesquisaComID = $PesquisaUsuario->buscarUsuarios($conn, $usuario_id);
                           foreach($pesquisaComID as $rowUsuarioPorID):
                            ?> 
                            <form action="crud/usuarioCRUD.php" method="POST">
                            <input type="hidden" name="id" class="form-control" value="<?=$rowUsuarioPorID['id']?>" required>


                            <div class="mb-3">
                                <label for="Nome">Nome:</label><br>
                                <input type="text" id="nome" name="nome" class="form-control" value="<?=$rowUsuarioPorID['nome']?>" required>
                             </div>

                            <div class="mb-3">
                                <label for="usuario">Usuario:</label><br>
                                <input type="text" id="usuario" name="usuario" class="form-control" value="<?=$rowUsuarioPorID['usuario']?>"required>
                            </div>

                            <div class="mb-3">
                                <label for="tipoUsuario">Tipo de usuario:</label>
                                <select name="tipoUsuario" id="tipo_usuario">
                                    <option value="gerente" <?= ($rowUsuarioPorID['tipo_usuario'] == 'gerente') ? 'selected' : '' ?>>Gerente</option>
                                    <option value="vendedor">Vendedor</option>
                                    <option value="gerente_estoque">Gerente de estoque</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="email">E-mail:</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?=$rowUsuarioPorID['email']?>"required>
                            </div>

                            <div class="mb-3">
                                <label for="senha">Senha: </label><br>
                                <input type="password" id="senha" name="senha" class="form-control" value="<?=$rowUsuarioPorID['senha']?>"required>
                            </div>

                            <div class="mb-3">
                                <button type="submit" name="editar_usuario" class="btn btn-primary"> Salvar </button>
                            </div>
                            </form>
                            <?php
                           endforeach;
                          }else{
                            echo "<h5>Usuario não encontrado</h5>";
                          }
                            ?>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>
