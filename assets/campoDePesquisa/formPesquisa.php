<div class="container mt-3">
    <div class="card shadow-sm border-0">
        <div class="card-header">
            <h4>Pesquisar </h4>
        </div>
        <div class="card-body">
            <form action="pesquisa.php" method="POST">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="cliente" class="form-label">Cliente</label>
                        <input type="text" name="cliente" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label for="vendedor" class="form-label">Vendedor</label>
                        <input type="text" name="vendedor" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label for="dta_inicial" class="form-label">Data Inicial</label>
                        <input type="date" name="dta_inicial" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label for="dta_final" class="form-label">Data Final</label>
                        <input type="date" name="dta_final" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" name="pesquisar_orcamento" class="btn btn-sm btn-primary">Pesquisar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>