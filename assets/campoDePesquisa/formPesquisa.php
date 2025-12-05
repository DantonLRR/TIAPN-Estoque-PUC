<?php
// Buscar clientes
$clientesPesquisa = mysqli_query($conn, "SELECT id, nome FROM clientes ORDER BY nome ASC");

// Buscar vendedores/gerentes
$vendedoresPesquisa = mysqli_query($conn, "SELECT nome FROM usuarios WHERE tipo_usuario IN ('gerente', 'vendedor') ORDER BY nome ASC");
?>

<div class="container mt-3">
    <div class="card shadow-sm border-0">
        <div class="card-header">
            <h4>Pesquisar</h4>
        </div>

        <div class="card-body">
            <form action="orcamentos.php" method="GET">
                <div class="row g-2 align-items-end">

                    <!-- CLIENTE -->
                    <div class="col-md-3">
                        <label class="form-label">Cliente</label>
                        <select name="cliente" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <?php while ($c = mysqli_fetch_assoc($clientesPesquisa)) : ?>
                                <option value="<?= $c['nome'] ?>"><?= $c['nome'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- VENDEDOR -->
                    <div class="col-md-3">
                        <label class="form-label">Vendedor</label>
                        <select name="vendedor" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <?php while ($v = mysqli_fetch_assoc($vendedoresPesquisa)) : ?>
                                <option value="<?= $v['nome'] ?>"><?= $v['nome'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- DATA INICIAL -->
                    <div class="col-md-2">
                        <label class="form-label">Data Inicial</label>
                        <input type="date" name="dta_inicial" class="form-control form-control-sm">
                    </div>

                    <!-- DATA FINAL -->
                    <div class="col-md-2">
                        <label class="form-label">Data Final</label>
                        <input type="date" name="dta_final" class="form-control form-control-sm">
                    </div>

                    <!-- STATUS -->
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="Pendente">Pendente</option>
                            <option value="Aprovado">Aprovado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-sm btn-primary">Pesquisar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
